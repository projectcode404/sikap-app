<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Atk\Item;
use App\Models\Atk\OutRequest;
use App\Models\Atk\OutRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class OutRequestController extends Controller
{
    public function getOutRequests(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Not Found');
        }

        $out_requests = OutRequest::with([
            'employee:id,full_name',
            'workUnit:id,name',
            'createdBy.employee:id,full_name',
            'approvedBy.employee:id,full_name'
        ])->get();

        $data = $out_requests->map(function ($req) {
            return [
                'id' => $req->id,
                'employee_name' => $req->employee->full_name ?? '-',
                'position_name' => $req->position_name,
                'work_unit' => $req->workUnit->name ?? '-',
                'request_date' => Carbon::parse($req->request_date)->translatedFormat('d F Y'), // e.g. 08 June 2025
                'period' => Carbon::createFromFormat('Y-m', $req->period)->translatedFormat('F Y'), // e.g. June 2025
                'created_by' => $req->createdBy->employee->full_name ?? '-',
                'approved_by' => $req->approvedBy->employee->full_name ?? '-',
                'status' => $req->status,
                'request_note' => $req->request_note,
            ];
        });

        return response()->json($data);
    }

    public function handleAction(Request $request, OutRequest $outRequest)
    {
        $action = $request->input('action');

        if ($action === 'approve') {
            // 1. Validasi standar terlebih dahulu
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.qty_approved' => 'required|integer|min:0',
                'approval_note' => 'nullable|string',
            ]);

            // 2. Validasi logika bisnis qty_approved <= stock tersedia
            $itemsInput = $validated['items'];
            $items = $outRequest->items()->with('atkItem')->get()->keyBy('id');
            $errors = [];

            foreach ($itemsInput as $itemId => $itemData) {
                $approved = (int)($itemData['qty_approved'] ?? 0);
                $available = (int)($items[$itemId]?->atkItem?->current_stock ?? 0);
                $name = $items[$itemId]?->atkItem?->name ?? 'Unknown Item';

                if ($approved > $available) {
                    $errors[] = "$name: Approved ($approved) > Available ($available)";
                }
            }

            if (!empty($errors)) {
                return redirect()
                    ->route('atk.out-requests.review', $outRequest)
                    ->with('approval_errors', $errors)
                    ->withInput();
            }

            $this->approveRequest($request, $outRequest);
            return redirect()->route('atk.out-requests.index')->with('success', 'Request approved.');
        }

        if ($action === 'reject') {
            $request->validate([
                'approval_note' => 'required|string',
            ]);

            $this->rejectRequest($request, $outRequest);
            return redirect()->route('atk.out-requests.index')->with('warning', 'Request rejected.');
        }

        return back()->with('error', 'Invalid action.');
    }

    protected function approveRequest(Request $request, OutRequest $outRequest): void
    {
        // Update status utama
        $outRequest->update([
            'status'        => 'approved',
            'approved_by'   => auth()->user()->employee_id,
            'approved_at'   => now(),
            'approval_note' => $request->input('approval_note'),
        ]);

        // Ambil dan siapkan data
        $updateData = collect($request->input('items', []))
            ->filter(fn($item) => isset($item['qty_approved']))
            ->map(fn($item, $id) => [
                'id' => (int) $id,
                'qty_approved' => (int) $item['qty_approved'],
            ]);

        // Extract arrays untuk digunakan di query
        $ids = $updateData->pluck('id')->all();
        $qtys = $updateData->pluck('qty_approved')->all();

        // Jalankan bulk update jika ada data
        if (!empty($ids) && !empty($qtys)) {
            $idsString = '{' . implode(',', $ids) . '}';
            $qtysString = '{' . implode(',', $qtys) . '}';

            DB::statement("
                UPDATE atk_out_request_items AS a
                SET qty_approved = b.qty_approved
                FROM (
                    SELECT UNNEST(:ids::int[]) AS id, UNNEST(:qtys::int[]) AS qty_approved
                ) AS b
                WHERE a.id = b.id
                AND a.atk_out_request_id = :out_request_id
            ", [
                'ids' => $idsString,
                'qtys' => $qtysString,
                'out_request_id' => $outRequest->id,
            ]);
        }
    }

    protected function rejectRequest(Request $request, OutRequest $outRequest): void
    {
        DB::transaction(function () use ($request, $outRequest) {
            $outRequest->update([
                'status'           => 'rejected',
                'rejected_by'      => auth()->user()->employee_id,
                'rejected_at'      => now(),
                'approval_note'    => $request->input('approval_note'),
            ]);

            $outRequest->items()->update(['qty_approved' => 0]);
        });
    }

    public function index()
    {
        return view('atk.out-requests.index');
    }

    public function show(OutRequest $outRequest)
    {
        $outRequest->load([
            'items.atkItem',
            'employee',
            'workUnit',
            'createdBy.employee',
            'approvedBy.employee'
        ]);
        $atkItems = Item::all()->keyBy('id');
        $user = auth()->user()->load('employee.workUnit');
        $userWorkUnit = $user->employee?->workUnit;

        return view('atk.out-requests.show', [
            'outRequest' => $outRequest,
            'atkItem' => fn($id) => $atkItems[$id] ?? null,
            'userWorkUnit' => $userWorkUnit,
        ]);
    }

    public function print(OutRequest $outRequest)
    {
        $allowedStatuses = ['approved', 'realized', 'received'];

        if (!in_array($outRequest->status, $allowedStatuses)) {
            return redirect()->route('atk.out-requests.index')
                            ->with('error', 'Tanda terima hanya bisa dicetak untuk permintaan yang sudah disetujui.');
        }

        $outRequest->load(['items.atkItem', 'createdBy.employee', 'workUnit']);

        return view('atk.out-requests.print', compact('outRequest'));
    }

    public function review(OutRequest $outRequest)
    {
        if ($outRequest->status !== 'submitted') {
            return redirect()
                ->route('atk.out-requests.index')
                ->with('error', 'Cannot review Request with status ' . $outRequest->status);
        }

        $outRequest->load(['items', 'employee', 'workUnit']);
        $atkItems = Item::all()->keyBy('id');

        return view('atk.out-requests.review', [
            'outRequest' => $outRequest,
            'atkItem' => fn($id) => $atkItems[$id] ?? null,
        ]);
    }

    public function create()
    {
        $atkItems = Item::all()->keyBy('id');
        $user = auth()->user()->load('employee.workUnit');

        $periods = collect([
            now()->subMonths(1)->format('Y-m'),
            now()->format('Y-m'),
            now()->addMonth()->format('Y-m'),
        ]);
        
        return view('atk.out-requests.create', [
            'atkItem' => fn($id) => $atkItems[$id] ?? null,
            'periods' => $periods->map(fn($p) => [
                'value' => $p,
                'label' => \Carbon\Carbon::createFromFormat('Y-m', $p)->translatedFormat('F Y'),
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $periods = collect([
            now()->subMonths(1)->format('Y-m'),
            now()->format('Y-m'),
            now()->addMonth()->format('Y-m'),
        ]);

        $validated = $request->validate([
            'work_unit_id' => 'required|exists:work_units,id',
            'request_date' => 'required|date',
            'period' => ['required', Rule::in($periods)],
            'request_note' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.current_stock' => 'nullable|integer|min:0',
        ]);

        $status = $request->input('action') === 'submit';
        $user = auth()->user();

        DB::beginTransaction();

        try {
            $employee = $user->employee;
            if (!$employee) {
                throw new \Exception("Account is not linked to employee data.");
            }

            $outRequest = OutRequest::create([
                'id' => Str::uuid(),
                'employee_id' => $employee->id,
                'position_name' => optional($employee->position)->name ?? '-',
                'work_unit_id' => $validated['work_unit_id'],
                'request_date' => Carbon::parse($validated['request_date']),
                'period' => $validated['period'],
                'request_note' => $validated['request_note'] ?? null,
                'status' => $status ? 'submitted' : 'draft',
                'created_by' => $user->employee->id,
                'created_at' => now(),
            ]);

            $atkItems = Item::whereIn('id', collect($validated['items'])->pluck('atk_item_id'))
                            ->get()
                            ->keyBy('id');

            foreach ($validated['items'] as $formItem) {
                $atkItem = $atkItems[$formItem['atk_item_id']] ?? null;

                if (!$atkItem) {
                    throw new \Exception("ATK Item not found for ID: {$formItem['atk_item_id']}");
                }

                OutRequestItem::create([
                    'atk_out_request_id' => $outRequest->id,
                    'atk_item_id' => $atkItem->id,
                    'current_stock_at_request' => $formItem['current_stock'] ?? 0,
                    'qty' => $formItem['qty'],
                ]);
            }

            DB::commit();
            return redirect()->route('atk.out-requests.index')
                ->with('success', $status
                    ? 'Request submitted successfully.'
                    : 'Request draft saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ATK Request Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to save: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(OutRequest $outRequest)
    {
        if ($outRequest->status !== 'draft') {
            return redirect()
                ->route('atk.out-requests.index')
                ->with('error', 'Cannot edit Request with status ' . $outRequest->status);
        }

        $outRequest->load(['items', 'employee', 'workUnit']);
        $atkItems = Item::all()->keyBy('id');

        $periods = collect([
            now()->subMonths(1)->format('Y-m'),
            now()->format('Y-m'),
            now()->addMonth()->format('Y-m'),
        ]);

        return view('atk.out-requests.create', [
            'outRequest' => $outRequest,
            'atkItem' => fn($id) => $atkItems[$id] ?? null,
            'periods' => $periods->map(fn($p) => [
                'value' => $p,
                'label' => \Carbon\Carbon::createFromFormat('Y-m', $p)->translatedFormat('F Y'),
            ]),
        ]);
    }

    public function update(Request $request, OutRequest $outRequest)
    {
        $periods = collect([
            now()->subMonths(1)->format('Y-m'),
            now()->format('Y-m'),
            now()->addMonth()->format('Y-m'),
        ]);

        $validated = $request->validate([
            'work_unit_id' => 'required|exists:work_units,id',
            'request_date' => 'required|date',
            'period' => ['required', Rule::in($periods)],
            'request_note' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.atk_item_id' => 'required|exists:atk_items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.current_stock' => 'nullable|integer|min:0',
        ]);

        $status = $request->input('action') === 'submit';
        $user = auth()->user();

        DB::beginTransaction();

        try {
            // Only allow editing if status is draft
            if ($outRequest->status !== 'draft') {
                throw new \Exception("Only draft requests can be edited.");
            }

            $employee = $user->employee;
            if (!$employee) {
                throw new \Exception("Account is not linked to employee data.");
            }

            $outRequest->update([
                'employee_id' => $employee->id,
                'position_name' => optional($employee->position)->name ?? '-',
                'work_unit_id' => $validated['work_unit_id'],
                'request_date' => Carbon::parse($validated['request_date']),
                'period' => $validated['period'],
                'request_note' => $validated['request_note'] ?? null,
                'status' => $status ? 'submitted' : 'draft',
                'updated_by' => $user->id,
                'updated_at' => now(),
            ]);

            // Remove old items
            OutRequestItem::where('atk_out_request_id', $outRequest->id)->delete();

            $atkItems = Item::whereIn('id', collect($validated['items'])->pluck('atk_item_id'))
                            ->get()
                            ->keyBy('id');

            foreach ($validated['items'] as $formItem) {
                $atkItem = $atkItems[$formItem['atk_item_id']] ?? null;

                if (!$atkItem) {
                    throw new \Exception("ATK Item not found for ID: {$formItem['atk_item_id']}");
                }

                OutRequestItem::create([
                    'atk_out_request_id' => $outRequest->id,
                    'atk_item_id' => $atkItem->id,
                    'current_stock_at_request' => $formItem['current_stock'] ?? 0,
                    'qty' => $formItem['qty'],
                ]);
            }

            DB::commit();
            return redirect()->route('atk.out-requests.index')
                ->with('success', $status
                    ? 'Request updated and submitted successfully.'
                    : 'Request draft updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ATK Request Update Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(OutRequest $outRequest)
    {
        try {
            DB::transaction(function () use ($outRequest) {
                // Only allow deletion if status is draft
                if ($outRequest->status !== 'draft') {
                    throw new \Exception('Cannot delete Out Request with status ' . $outRequest->status);
                }

                // Delete all related items
                $outRequest->items()->delete();

                // Delete the OutRequest itself
                $outRequest->delete();
            });

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json(['message' => 'Out Request successfully deleted!'], 200);
            }

            return redirect()->route('atk.out-requests.index')
                ->with('success', 'Out Request successfully deleted!');
        } catch (\Exception $e) {
            return redirect()->route('atk.out-requests.index')->with('error', 'Failed to delete Out Request: ' . $e->getMessage());
        }
    }
}

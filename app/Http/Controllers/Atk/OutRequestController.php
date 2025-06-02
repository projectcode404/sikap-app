<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Atk\Item;
use App\Models\Atk\OutRequest;
use App\Models\Atk\OutRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
                'employee_name' => $req->employee->full_name ?? null,
                'position_name' => $req->position_name,
                'work_unit' => $req->workUnit->name ?? '-',
                'request_date' => $req->request_date,
                'period' => $req->period,
                'created_by' => $req->createdBy->employee->full_name ?? null,
                'approved_by' => $req->approvedBy->employee->full_name ?? null,
                'status' => $req->status,
                'remarks' => $req->remarks,
            ];
        });

        return response()->json($data);
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

    public function create()
    {
        $atkItems = Item::all()->keyBy('id');
        $user = auth()->user()->load('employee.workUnit');
        $userWorkUnit = $user->employee?->workUnit;
        
        return view('atk.out-requests.create', [
            'atkItem' => fn($id) => $atkItems[$id] ?? null,
            'userWorkUnit' => $userWorkUnit,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_unit_id' => 'required|exists:work_units,id',
            'request_date' => 'required|date',
            'remarks' => 'required|string',
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
                'period' => Carbon::parse($validated['request_date'])->format('Y-m'),
                'remarks' => $validated['remarks'] ?? null,
                'status' => $status ? 'submitted' : 'draft',
                'created_by' => $user->id,
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
        $user = auth()->user()->load('employee.workUnit');
        $userWorkUnit = $user->employee?->workUnit;

        return view('atk.out-requests.create', [
            'outRequest' => $outRequest,
            'atkItem' => fn($id) => $atkItems[$id] ?? null,
            'userWorkUnit' => $userWorkUnit,
        ]);
    }

    public function update(Request $request, OutRequest $outRequest)
    {
        $validated = $request->validate([
            'work_unit_id' => 'required|exists:work_units,id',
            'request_date' => 'required|date',
            'remarks' => 'required|string',
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
                'period' => Carbon::parse($validated['request_date'])->format('Y-m'),
                'remarks' => $validated['remarks'] ?? null,
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

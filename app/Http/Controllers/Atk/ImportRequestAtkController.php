<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Master\Employee;
use App\Models\Atk\RequestAtk;
use App\Models\Atk\RequestAtkItem;

class ImportRequestAtkController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'period' => 'required|date_format:Y-m'
        ]);

        $file = $request->file('csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_map('strtolower', $data[0]);
        unset($data[0]);

        // Ambil semua ID Formester dari file
        $formesterIdsFromCsv = array_map(fn($row) => trim($row[0]), $data);

        // Ambil ID yang sudah ada di DB
        $existingIds = AtkOutRequest::whereIn('id_formester', $formesterIdsFromCsv)->pluck('id_formester')->toArray();
        $existingMap = array_flip($existingIds);

        $newRequests = [];
        $newItems = [];

        foreach ($data as $row) {
            $idFormester = trim($row[1]);
            if (isset($existingMap[$idFormester])) {
                continue; // Skip jika sudah ada
            }

            $employeeId = trim($row[2]);
            $workUnitId = trim($row[3]);
            $requestDate = Carbon::parse(trim($row[4]));
            $period = $requestDate->format('Y-m');
            $remarks = trim($row[6]);
            $atkItemIds = json_decode($row[7], true);
            $atkQtys = json_decode($row[8], true);
            $kertasItemIds = json_decode($row[9], true);
            $kertasQtys = json_decode($row[10], true);

            $itemIds = array_merge($atkItemIds ?? [], $kertasItemIds ?? []);
            $qtys = array_merge($atkQtys ?? [], $kertasQtys ?? []);

            if (!$employeeId || !$workUnitId || !is_array($itemIds) || !is_array($qtys) || empty($itemIds)) {
                continue;
            }
            
            $employee = Employee::where('employee_id', $employeeId)->with('position')->first();
            $positionName = optional($employee->position)->name ?? '-';

            // Merge item ID yang sama
            $mergedItems = [];
            foreach ($itemIds as $i => $itemId) {
                $itemId = (int) $itemId;
                $qty = (int) ($qtys[$i] ?? 0);
                if ($itemId > 0 && $qty > 0) {
                    $mergedItems[$itemId] = ($mergedItems[$itemId] ?? 0) + $qty;
                }
            }

            $requestId = Str::uuid();

            $newRequests[] = [
                'id' => $requestId,
                'id_formester' => $idFormester,
                'employee_id' => $employeeId,
                'work_unit_id' => $workUnitId,
                'position_name' => $positionName,
                'request_date' => $requestDate,
                'created_by' => Auth::id(),
                'period' => $period,
                'remarks' => $remarks,
                'status' => 'outstanding',
                'created_at' => now(),
                'updated_at' => now()
            ];

            foreach ($mergedItems as $itemId => $qty) {
                $newItems[] = [
                    'atk_out_request_id' => $requestId,
                    'stock_atk_id' => $itemId,
                    'qty' => $qty,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Bulk insert
        AtkOutRequest::insert($newRequests);
        AtkOutItem::insert($newItems);

        return back()->with('success', count($newRequests) . ' ATK request(s) imported successfully.');
    }
}

<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeExport implements FromView
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function view(): View
    {
        return view('employees.excel', [
            'employees' => $this->employees
        ]);
    }
}
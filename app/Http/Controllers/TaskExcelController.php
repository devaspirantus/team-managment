<?php

namespace App\Http\Controllers;
use App\Exports\TasksExport;
use App\Imports\TasksImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TaskExcelController extends Controller
{
    public function export()
    {
        return Excel::download(new TasksExport, 'tasks-report-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new TasksImport, $request->file('excel_file'));

        return back()->with('success', 'Tasks imported successfully from Excel!');
    }
}

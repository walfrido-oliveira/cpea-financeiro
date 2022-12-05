<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLog;
use Illuminate\Http\Request;

class EmployeeLogController extends Controller
{
    /**
     * get log list
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listLog($id, $name)
    {
        $employeeLogs = EmployeeLog::where("employee_id", $id)->where("name", $name)->get();

        return response()->json([
            'modal' => view('employees.log-modal', compact('employeeLogs'))->render()
        ]);
    }
}

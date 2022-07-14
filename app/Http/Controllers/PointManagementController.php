<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PointManagement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PointManagementController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function action(Request $request, $month, $year)
    {
        $validator = Validator::make($request->all(), [
            'employee_id.*' => ['exists:employees, id'],
            'employee_id' => ['required', 'string'],
            'action' => ['required', 'in:approved,disapproved'],
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();
        $input['employee_id'] = explode(",", $input['employee_id']);

        foreach ($input['employee_id'] as $value)
        {
            PointManagement::create([
                'user_id' => auth()->user()->id,
                'year' => $year,
                'month' => $month,
                'employee_id' => $value,
                'balance' => Employee::findOrFail($value)->balanceByMonthAndYear($month, $year),
                'status' => $input['action']
            ]);
        }


        return response()->json([
            'message' => __('Ponto Aprovado com Sucesso!'),
            'alert-type' => 'success'
        ]);
    }
}

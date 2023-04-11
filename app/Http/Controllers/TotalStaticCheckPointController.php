<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountingConfig;
use App\Models\AccountingControl;
use App\Models\TotalStaticCheckPoint;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\AccountingClassification;
use Illuminate\Support\Facades\Validator;

class TotalStaticCheckPointController extends Controller
{
    /**
    * Display a listing of the user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->all();

        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'accounting_classification_id';
        $year = isset($query['year']) ? $query['year'] : now()->year;
        $years = AccountingConfig::groupBy('year')->get()->pluck('year', 'year');

        $totalStaticCheckPoints = TotalStaticCheckPoint::where('year', $year)->groupBy('classification_id')->get();

        $months = months();

        return view('total-static-check-point.index',
        compact('ascending', 'orderBy', 'totalStaticCheckPoints', 'months', 'year', 'years'));
    }

     /**
     * Create the specified resource in storage.
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'result' => ['required', 'numeric'],
            'justification' => ['required', 'string'],
            'id' => ['required'],
            'month' => ['required'],
            'year' => ['required'],
            'type' => ['required'],
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();

        $totalStaticCheckPoint = TotalStaticCheckPoint::where('year', $input['year'])
        ->where('month',$input['month'])
        ->where('classification_id', $id)
        ->where('type', $input['type'])->first();

        $accountControl = AccountingControl::where('year', $input['year'])
        ->where('month',$input['month'])
        ->where('type', $input['type'])->first();

        $accountingClassification = AccountingClassification::where("classification", $id);
        if($accountingClassification && $accountControl) {
            $accountAnalytics = $accountControl->accountingAnalytics()->where("accounting_classification_id", $accountingClassification->id);
            if($accountAnalytics) {
                $accountAnalytics->update([
                    'value' => $input['result'],
                    'justification' => $input['justification'],
                ]);
            }
        }

        if($totalStaticCheckPoint) {
            $totalStaticCheckPoint->update([
                'result' => $input['result'],
                'justification' => $input['justification']
            ]);



            return response()->json([
                'message' => __('Valores atualizados com sucesso!'),
                'alert-type' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => __('Lançamento não encontrado.'),
                'alert-type' => 'success'
            ]);
        }
    }
}

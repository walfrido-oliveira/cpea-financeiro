<?php

namespace App\Http\Controllers;

use App\Models\Dre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountingConfig;
use Illuminate\Support\Facades\Validator;

class DREController extends Controller
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

        $accountingClassifications = collect([]);
        $accountingConfigs = AccountingConfig::where("year", $year)->where("month", 1)->get();
        if($accountingConfigs)
        {
            foreach ($accountingConfigs  as $key => $accountingConfig)
            {
                $accountingClassifications = $accountingClassifications->merge($accountingConfig->accountingClassifications()
                ->where('type_classification', 'DRE Ajustável')
                ->where('accounting_classifications.accounting_classification_id', null)
                ->orderBy("accounting_classifications.order")
                ->get());
            }
        }

        $months = months();

        return view('dre.index',
        compact('ascending', 'orderBy', 'accountingClassifications', 'months', 'year', 'years', 'accountingConfigs'));
    }

     /**
     * Create the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => ['required', 'numeric'],
            'justification' => ['required', 'string'],
            'month' => ['required'],
            'year' => ['required'],
            'accounting_classification_id' => ['required', 'exists:accounting_classifications,id']
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();

        $dre = Dre::create([
            'value' => $input['value'],
            'justification' => $input['justification'],
            'year' => $input['year'],
            'month' => $input['month'],
            'accounting_classification_id' => $input['accounting_classification_id'],
        ]);

        return response()->json([
            'message' => __('Valores atualizados com sucesso!'),
            'alert-type' => 'success'
        ]);
    }
}

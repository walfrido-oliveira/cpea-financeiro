<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingConfig;

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
                $accountingClassifications = $accountingClassifications->merge($accountingConfig->accountingClassifications()->where('type_classification', 'DRE Ajustável')
                ->orderBy("accounting_classifications.id")
                ->get());
            }
        }

        $months = months();

        return view('dre.index',
        compact('ascending', 'orderBy', 'accountingClassifications', 'months', 'year', 'years'));
    }
}

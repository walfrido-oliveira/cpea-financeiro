<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingClassification;

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
        $accountingClassifications = AccountingClassification::where('type_classification', 'DRE Ajustável')->get();
        $months = months();

        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'accounting_classification_id';
        $year = isset($query['year']) ? $query['year'] : now()->year;

        return view('dre.index',
        compact('ascending', 'orderBy', 'accountingClassifications', 'months', 'year'));
    }
}

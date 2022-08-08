<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingConfig;
use App\Models\TotalStaticCheckPoint;

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

        $totalStaticCheckPoints = TotalStaticCheckPoint::where('year', $year)->get();

        $months = months();

        return view('total-static-check-point.index',
        compact('ascending', 'orderBy', 'totalStaticCheckPoints', 'months', 'year', 'years'));
    }
}

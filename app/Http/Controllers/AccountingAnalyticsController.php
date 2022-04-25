<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountingAnalytics;
use Illuminate\Support\Facades\Validator;

class AccountingAnalyticsController extends Controller
{
    /**
     * Validate fields
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private function validating(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric'],
            'classification' => ['required', 'string']
        ]);
    }

     /**
    * Display a listing of the user.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $accountingAnalytics =  AccountingAnalytics::filter($request->all());
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'name';

        return view('accounting-analytics.index', compact('accountingAnalytics', 'ascending', 'orderBy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounting-analytics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validating($request);

        $input = $request->all();

       AccountingAnalytics::create([
            'name' => $input['name'],
            'classification' => $input['classification'],
            'value' => $input['value'],
        ]);

        $resp = [
            'message' => __('Analítico Contábil Cadastrado com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('accounting-analytics.index')->with($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accountingClassification = AccountingAnalytics::findOrFail($id);
        return view('accounting-analytics.show', compact('accountingClassification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accountingClassification = AccountingAnalytics::findOrFail($id);
        return view('accounting-analytics.edit', compact('accountingClassification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $accountingClassification = AccountingAnalytics::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'value' => ['required', 'numeric'],
            'justification' => ['required', 'string']
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();

        $accountingClassification->update([
            'value' => $input['value'],
            'justification' => $input['justification'],
        ]);

        return response()->json([
            'message' => __('Valores atualizados com sucesso!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accountingClassification = AccountingAnalytics::findOrFail($id);

        $accountingClassification->delete();

        return response()->json([
            'message' => __('Analítico Contábil Apagado com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Filter accountingClassification
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $accountingAnalytics = AccountingAnalytics::filter($request->all());
        $accountingAnalytics = $accountingAnalytics->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('accounting-analytics.filter-result', compact('accountingAnalytics', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $accountingAnalytics,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }
}

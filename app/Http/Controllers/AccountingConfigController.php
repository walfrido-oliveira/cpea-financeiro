<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingConfig;

class AccountingConfigController extends Controller
{
    /**
     * Validate fields
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private function validating(Request $request)
    {
        $request->validate([
            'month' => ['required'],
            'year' => ['required'],
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
        $accountingConfigs =  AccountingConfig::filter($request->all());
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'created_at';
        $months = months();

        return view('accounting-configs.index', compact('accountingConfigs', 'ascending', 'orderBy', 'months'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounting-configs.create');
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

       AccountingConfig::create([
            'month' => $input['month'],
            'year' => $input['year']
        ]);

        return response()->json([
            'message' => __('Configuração Adicionada com Sucesso!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $accountingConfig = AccountingConfig::findOrFail($id);
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'asc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'id';

        return view('accounting-configs.show', compact('accountingConfig', 'ascending', 'orderBy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accountingConfig = AccountingConfig::findOrFail($id);
        return view('accounting-configs.edit', compact('accountingConfig'));
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
        $accountingConfig = AccountingConfig::findOrFail($id);

        $this->validating($request);

        $input = $request->all();

        $accountingConfig->update([
            'month' => $input['month'],
            'year' => $input['year']
        ]);

        return response()->json([
            'message' => __('Configuração Atualizado com Sucesso!'),
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
        $accountingConfig = AccountingConfig::findOrFail($id);

        $accountingConfig->delete();

        return response()->json([
            'message' => __('Configuração Apagada com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Filter accountingConfig
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $accountingConfigs = AccountingConfig::filter($request->all());
        $accountingConfigs = $accountingConfigs->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('accounting-configs.filter-result', compact('accountingConfigs', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $accountingConfigs,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }
}

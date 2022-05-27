<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingConfig;
use App\Models\AccountingClassification;
use App\Models\Formula;

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
        $accountingClassifications = AccountingClassification::all()->pluck('description', 'id');
        $formulas = Formula::all()->pluck('formula', 'id');
        $accountingClassificationTypes = AccountingClassification::getTypesClassifications2();

        return view('accounting-configs.index',
        compact('accountingConfigs', 'ascending', 'orderBy', 'months', 'accountingClassifications', 'formulas', 'accountingClassificationTypes'));
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

        $accountingConfig = AccountingConfig::where('month', $input['month'])->where('year', $input['year'])->get();

        if(count($accountingConfig) == 0)
        {
            AccountingConfig::create([
                'month' => $input['month'],
                'year' => $input['year']
            ]);

            return response()->json([
                'message' => __('Configuração Adicionada com Sucesso!'),
                'alert-type' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => __('Essa configuração já foi adicionada'),
                'alert-type' => 'error'
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $year
     * @param  int  $month
     * @return \Illuminate\Http\Response
     */
    public function addClassification(Request $request, $year, $month)
    {
        $accountingConfig = AccountingConfig::where('month', $month)->where('year', $year)->first();

       if($accountingConfig)
       {
            $input = $request->all();
            $input['accounting_classification_type'] = explode(",", $input['accounting_classification_type']);

            if($input['accounting_classification_type']) {
                $accountingClassifications = AccountingClassification::whereIn('type_classification', $input['accounting_classification_type'])->get()->pluck('id');
                $accountingConfig->accountingClassifications()->attach($accountingClassifications);
            } else if($input['accounting_classification_id']) {
                $accountingConfig->accountingClassifications()->attach($input['accounting_classification_id']);
            }

            return response()->json([
                'message' => __('Configuração Atualizada com Sucesso!'),
                'alert-type' => 'success'
            ]);
       } else {
        return response()->json([
            'message' => __('Configuração não encontrada!'),
            'alert-type' => 'error'
        ]);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $reques
     * @param  int  $id
     * @param  int  $config
     * @return \Illuminate\Http\Response
     */
    public function deleteClassification($id, $config)
    {
        $accountingConfig = AccountingConfig::findOrFail($config);

        $accountingConfig->accountingClassifications()->detach($id);

        $resp = [
            'message' => __('Configuração Apagada com Sucesso!!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('accounting-configs.index')->with($resp);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $year
     * @param  int  $month
     * @return \Illuminate\Http\Response
     */
    public function addFormula(Request $request, $year, $month)
    {
        $accountingConfig = AccountingConfig::where('month', $month)->where('year', $year)->first();

        if($accountingConfig)
        {
            $input = $request->all();

            $accountingConfig->formulas()->attach($input['formula_id']);

            return response()->json([
                'message' => __('Configuração Atualizada com Sucesso!'),
                'alert-type' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => __('Configuração não encontrada!'),
                'alert-type' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $reques
     * @param  int  $id
     * @param  int  $config
     * @return \Illuminate\Http\Response
     */
    public function deleteFormula($id, $config)
    {
        $accountingConfig = AccountingConfig::findOrFail($config);

        $accountingConfig->formulas()->detach($id);

        $resp = [
            'message' => __('Configuração Apagada com Sucesso!!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('accounting-configs.index')->with($resp);
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

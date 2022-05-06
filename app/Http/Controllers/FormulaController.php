<?php

namespace App\Http\Controllers;

use App\Models\AccountingClassification;
use App\Models\Formula;
use App\Models\MonthFormula;
use Illuminate\Http\Request;

class FormulaController extends Controller
{
    /**
     * Validate fields
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private function validating(Request $request)
    {
        $request->validate([
            'accounting_classification_id' => ['required', 'exists:accounting_classifications,id'],
            'type_classification' => ['required', 'string'],
            'formula' => ['required', 'string']
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
        $formulas =  Formula::filter($request->all());
        $accountingClassifications = AccountingClassification::all()->pluck('description', 'id');
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'accounting_classification_id';

        return view('formulas.index', compact('formulas', 'ascending', 'orderBy', 'accountingClassifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accountingClassifications = AccountingClassification::all()->pluck('description', 'id');
        $accountingClassificationsCalc = AccountingClassification::all()->pluck('description', 'calc_name');
        $types = AccountingClassification::getTypesClassifications2();
        $months = months();
        $conditionalTypes = Formula::conditionalTypes();

        return view('formulas.create', compact('accountingClassifications', 'accountingClassificationsCalc', 'types', 'months', 'conditionalTypes'));
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

       $formula = Formula::create([
            'accounting_classification_id' => $input['accounting_classification_id'],
            'type_classification' => $input['type_classification'],
            'formula' => $input['formula'],
            'obs' => $input['obs'],
            'conditional' => $input['conditional'],
            'conditional_type' => $input['conditional_type'],
            'conditional_value' => $input['conditional_value'],
            'conditional_formula' => $input['conditional_formula'],
        ]);

        foreach ($input['months'] as $value)
        {
            if(!$value) continue;
            MonthFormula::create([
                'formula_id' => $formula->id,
                'month' => $value
            ]);
        }

        $resp = [
            'message' => __('Formula Cadastrada com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('formulas.index')->with($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $formula = Formula::findOrFail($id);
        return view('formulas.show', compact('formula'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formula = Formula::findOrFail($id);
        $accountingClassifications = AccountingClassification::all()->pluck('description', 'id');
        $accountingClassificationsCalc = AccountingClassification::all()->pluck('description', 'calc_name');
        $types = AccountingClassification::getTypesClassifications2();
        $conditionalTypes = Formula::conditionalTypes();
        $months = months();
        $monthsFormula = [];

        foreach ($formula->months as $key => $value)
        {
            $monthsFormula[] = $value->month;
        }

        return view('formulas.edit',
        compact('formula', 'accountingClassifications', 'accountingClassificationsCalc', 'types', 'months','monthsFormula', 'conditionalTypes'));
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
        $formula = Formula::findOrFail($id);

        $this->validating($request);

        $input = $request->all();

        $formula->update([
            'accounting_classification_id' => $input['accounting_classification_id'],
            'type_classification' => $input['type_classification'],
            'formula' => $input['formula'],
            'obs' => $input['obs'],
            'conditional' => $input['conditional'],
            'conditional_type' => $input['conditional_type'],
            'conditional_value' => $input['conditional_value'],
            'conditional_formula' => $input['conditional_formula'],
        ]);

        foreach ($formula->months as $value)
        {
           $value->delete();
        }

        foreach ($input['months'] as $value)
        {
            if(!$value) continue;
            MonthFormula::create([
                'formula_id' => $formula->id,
                'month' => $value
            ]);
        }

        $resp = [
            'message' => __('Formula Atualizada com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('formulas.index')->with($resp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $formula = Formula::findOrFail($id);

        $formula->delete();

        return response()->json([
            'message' => __('Formula Apagada com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Filter formula
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $formulas = Formula::filter($request->all());
        $formulas = $formulas->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('formulas.filter-result', compact('formulas', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $formulas,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }
}

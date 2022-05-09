<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AccountingClassification;

class AccountingClassificationController extends Controller
{
    /**
     * Validate fields
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private function validating(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('accounting_classifications', 'name', )->ignore($request->name, 'name')],
            'level' => ['required', 'max:10', 'min:1', 'integer'],
            'classification' => ['required', 'string'],
            'type_classification' => ['required', Rule::in(AccountingClassification::getTypesClassifications())],
            'unity' => ['nullable', Rule::in(AccountingClassification::getUnitys())],
            'accounting_classification_id' => ['nullable', 'exists:accounting_classifications,id']
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
        $accountingClassifications =  AccountingClassification::filter($request->all());
        $types = AccountingClassification::getTypesClassifications2();
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'asc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'id';

        return view('accounting-classifications.index', compact('accountingClassifications', 'ascending', 'orderBy', 'types', 'accountingClassifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = AccountingClassification::getTypesClassifications2();
        $unitys = AccountingClassification::getUnitys2();
        $accountingClassifications =  AccountingClassification::all()->pluck('description', 'id');

        return view('accounting-classifications.create', compact('types', 'accountingClassifications', 'unitys'));
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

       AccountingClassification::create([
            'name' => $input['name'],
            'classification' => $input['classification'],
            'level' => $input['level'],
            'obs' => $input['obs'],
            'type_classification' => $input['type_classification'],
            'featured' => isset($input['featured']) ? true : false,
            'bolder' => isset($input['bolder']) ? true : false,
            'visible' => isset($input['visible']) ? true : false,
            'color' => $input['color'],
            'accounting_classification_id' => $input['accounting_classification_id'],
            'unity' => $input['unity']
        ]);

        $resp = [
            'message' => __('Classificação Contábel Cadastrado com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('accounting-classifications.index')->with($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accountingClassification = AccountingClassification::findOrFail($id);
        return view('accounting-classifications.show', compact('accountingClassification'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accountingClassification = AccountingClassification::findOrFail($id);
        $types = AccountingClassification::getTypesClassifications2();
        $unitys = AccountingClassification::getUnitys2();
        $accountingClassifications =  AccountingClassification::all()->pluck('description', 'id');

        return view('accounting-classifications.edit', compact('accountingClassification', 'types', 'accountingClassifications', 'unitys'));
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
        $accountingClassification = AccountingClassification::findOrFail($id);

        $this->validating($request);

        $input = $request->all();

        $accountingClassification->update([
            'name' => $input['name'],
            'classification' => $input['classification'],
            'level' => $input['level'],
            'obs' => $input['obs'],
            'type_classification' => $input['type_classification'],
            'featured' => isset($input['featured']) ? true : false,
            'bolder' => isset($input['bolder']) ? true : false,
            'visible' => isset($input['visible']) ? true : false,
            'color' => $input['color'],
            'accounting_classification_id' => $input['accounting_classification_id'],
            'unity' => $input['unity']
        ]);

        $resp = [
            'message' => __('Classificação Contábel Atualizado com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('accounting-classifications.index')->with($resp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accountingClassification = AccountingClassification::findOrFail($id);

        $accountingClassification->delete();

        return response()->json([
            'message' => __('Classificação Contábel Apagado com Sucesso!!'),
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
        $accountingClassifications = AccountingClassification::filter($request->all());
        $accountingClassifications = $accountingClassifications->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('accounting-classifications.filter-result', compact('accountingClassifications', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $accountingClassifications,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }
}

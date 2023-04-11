<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountingConfig;
use App\Models\AccountingControl;
use App\Models\AccountingClassification;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends Controller
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
        $months = months();

        $accountingClassifications = AccountingClassification::where('type_classification', 'Resultado do Exercicio')->get()->pluck('description', 'id');
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'accounting_classification_id';
        $year = isset($query['year']) ? $query['year'] : now()->year;
        $years = AccountingConfig::groupBy('year')->get()->pluck('year', 'year');

        $accountingClassifications1 = collect([]);
        $accountingClassifications2 = collect([]);
        $accountingConfigs = AccountingConfig::where("year", $year)->where("month", 1)->get();

        if($accountingConfigs)
        {
            foreach ($accountingConfigs  as $key => $accountingConfig)
            {
                $accountingClassifications1 = $accountingClassifications1->merge($accountingConfig->accountingClassifications()
                ->where('type_classification', 'Retiradas Gerenciais')
                ->where('accounting_classifications.accounting_classification_id', null)
                ->orderBy("accounting_classifications.order")
                ->get());

                $accountingClassifications2 = $accountingClassifications2->merge($accountingConfig->accountingClassifications()
                ->where('type_classification', 'Resultados do Exercicio')
                ->where('accounting_classifications.accounting_classification_id', null)
                ->orderBy("accounting_classifications.order")
                ->get());
            }
        }

        return view('withdrawals.index',
        compact('ascending', 'orderBy', 'accountingClassifications', 'accountingClassifications1', 'months',
                'year', 'accountingClassifications2', 'years', 'accountingConfigs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accountingClassifications = AccountingClassification::all()->pluck('description', 'id');
        return view('withdrawals.create', compact('accountingClassifications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'accounting_classification_id' => ['required', 'exists:accounting_classifications,id'],
            'month' => ['required', 'numeric'],
            'year' => ['required', 'numeric'],
            'value' => ['required', 'numeric']
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

       Withdrawal::create([
            'accounting_classification_id' => $input['accounting_classification_id'],
            'month' => $input['month'],
            'year' => $input['year'],
            'value' => $input['value']
        ]);

        return response()->json([
            'message' => __('Retirada Cadastrada com Sucesso!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        return view('withdrawals.show', compact('withdrawal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $accountingClassifications = AccountingClassification::all()->pluck('description', 'id');
        return view('withdrawals.edit', compact('withdrawal', 'accountingClassifications'));
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
        $validator = Validator::make($request->all(), [
            'value' => ['required', 'numeric'],
            'justification' => ['required', 'string'],
            'id' => ['required'],
            'month' => ['required'],
            'year' => ['required'],
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();

        $withdrawal = Withdrawal::where('year', $input['year'])
        ->where('month',$input['month'])
        ->where('accounting_classification_id', $id)->first();

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

        if($withdrawal) {
            $withdrawal->update([
                'value' => $input['value'],
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $withdrawal = Withdrawal::findOrFail($id);

        $withdrawal->delete();

        return response()->json([
            'message' => __('Retirada Apagada com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Filter withdrawal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $withdrawals = Withdrawal::filter($request->all());
        $withdrawals = $withdrawals->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('withdrawals.filter-result', compact('withdrawals', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $withdrawals,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }
}

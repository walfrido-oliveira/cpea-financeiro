<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use Illuminate\Http\Request;
use App\Models\AccountingConfig;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\AccountingClassification;
use Illuminate\Support\Facades\Validator;

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
        $maxYear = AccountingConfig::max('year');
        $maxMonth = AccountingConfig::where('year', $maxYear)->max('month');

        $years = AccountingConfig::groupBy('year')->get()->pluck('year', 'year');

        $accountingConfigs =  AccountingConfig::filter([
            'month' => $request->has('month') ? $request->get('month') : $maxMonth,
            'year' => $request->has('year') ? $request->get('year') : $maxYear,
            ]
        );

        $ascending = $request->has('ascending') ? $request->get('ascending') : 'desc';
        $orderBy = $request->has('order_by') ? $request->get('order_by') : 'created_at';
        $months = months();
        $accountingClassifications = AccountingClassification::all()->pluck('description', 'id');
        $formulas = Formula::all()->pluck('full_name', 'id');
        $accountingClassificationTypes = AccountingClassification::getTypesClassifications2();

        return view('accounting-configs.index',
        compact('accountingConfigs', 'ascending', 'orderBy',
        'months', 'accountingClassifications', 'formulas', 'accountingClassificationTypes', 'years',
        'maxMonth', 'maxYear'));
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
     * Duplicate a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request)
    {
        $this->validating($request);

        $input = $request->all();

        $accountingConfig = AccountingConfig::where('month', $input['month'])->where('year', $input['year'])->get();
        $accountingConfigRef = AccountingConfig::where('month', $input['month_ref'])->where('year', $input['year_ref'])->first();

        if(count($accountingConfig) == 0)
        {
            $result = AccountingConfig::create([
                'month' => $input['month'],
                'year' => $input['year']
            ]);
        } else {
            $result = AccountingConfig::where('month', $input['month'])->where('year', $input['year'])->first();
        }

        if($input['retiradas_gerenciais'] == 'true')
        {
            foreach ($accountingConfigRef->accountingClassifications()->where('type_classification', 'Retiradas Gerenciais')->get() as $accountingClassification)
            {
                $result->accountingClassifications()->attach($accountingClassification->id);
            }
        }

        if($input['resultado_exercicio'] == 'true')
        {
            foreach ($accountingConfigRef->accountingClassifications()->where('type_classification', 'Resultados do Exercício')->get() as $accountingClassification)
            {
                $result->accountingClassifications()->attach($accountingClassification->id);
            }
        }

        if($input['dre'] == 'true')
        {
            foreach ($accountingConfigRef->accountingClassifications()->where('type_classification', 'DRE')->get() as $accountingClassification)
            {
                $result->accountingClassifications()->attach($accountingClassification->id);
            }
        }

        if($input['dre_ajustavel'] == 'true')
        {
            foreach ($accountingConfigRef->accountingClassifications()->where('type_classification', 'DRE Ajustável')->get() as $accountingClassification)
            {
                $result->accountingClassifications()->attach($accountingClassification->id);
            }
        }

        if($input['formula'] == 'true')
        {
            foreach ($accountingConfigRef->formulas as $formula)
            {
                $result->formulas()->attach($formula->id);
            }
        }

        return response()->json([
            'message' => __('Configuração duplicada com sucesso!'),
            'alert-type' => 'success'
        ]);


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

            if($input['accounting_classification_type']) {
                $input['accounting_classification_type'] = explode(",", $input['accounting_classification_type']);
                $accountingClassifications = AccountingClassification::whereIn('type_classification', $input['accounting_classification_type'])->get()->pluck('id');
                $accountingConfig->accountingClassifications()->attach($accountingClassifications);
            } else if($input['accounting_classification_id']) {

                $input['accounting_classification_id'] = explode(",", $input['accounting_classification_id']);
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
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteClassifications(Request $request, $id)
    {
        $accountingConfig = AccountingConfig::findOrFail($id);

        if($request->has('accounting_classification'))
        {
            $input = $request->all();
            $input['accounting_classification'] = explode(",", $input['accounting_classification']);

            foreach ($input['accounting_classification'] as $value)
            {
                $accountingConfig->accountingClassifications()->detach($value);
            }

            return response()->json([
                'message' => __('Configurações Apagadas com Sucesso!!'),
                'alert-type' => 'success'
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
    public function addFormula(Request $request, $year, $month)
    {
        $accountingConfig = AccountingConfig::where('month', $month)->where('year', $year)->first();

        if($accountingConfig)
        {
            $input = $request->all();

            if($input['all_formulas'] == "true")
            {
              $formulas = Formula::all()->pluck('id');
              $accountingConfig->formulas()->attach($formulas);

            } else {
                $input['formula_id'] = explode(",", $input['formula_id']);
                $accountingConfig->formulas()->attach($input['formula_id']);
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
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormulas(Request $request, $id)
    {
        $accountingConfig = AccountingConfig::findOrFail($id);

        if($request->has('formula'))
        {
            $input = $request->all();
            $input['formula'] = explode(",", $input['formula']);

            foreach ($input['formula'] as $value)
            {
                $accountingConfig->formulas()->detach($value);
            }
        }

        return response()->json([
            'message' => __('Configurações Apagadas com Sucesso!!'),
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

    /**
   * Import formulas
   *
   * @param  Request  $request
   * @param int $year
   * @param int $month
   * @return \Illuminate\Http\Response
   */
  public function importFormula(Request $request, $year, $month)
  {
    $validator = Validator::make(
        $request->all(),
        [
          'file' => 'required|mimes:xls,xlsx|max:4096',
        ]
      );

    if ($validator->fails()) {
    return response()->json([
        'message' => implode("<br>", $validator->messages()->all()),
        'alert-type' => 'error'
    ], 403);
    }

    $accountingConfig = AccountingConfig::where('month', $month)->where('year', $year)->first();

    if(!$accountingConfig) return response()->json([
        'message' => "ano/mês não encontrado",
        'alert-type' => 'error'
    ], 403);

    $spreadsheet = IOFactory::load($request->file->path());
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    foreach ($rows as $key => $value)
    {
        if ($key == 0) continue;

        try {
            $formula = Formula::updateOrCreate([
                'accounting_classification_id' => $value[0],
                'type_classification' => $value[1],
                'formula' => $value[2],
                'obs' => $value[3],
            ]);

            $accountingConfig->formulas()->attach($formula->id);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    return response()->json([
        'message' => __('Formulas importadas com Sucesso!!'),
        'alert-type' => 'success'
    ]);
  }
}

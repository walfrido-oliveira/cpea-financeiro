<?php

namespace App\Http\Controllers;

use stdClass;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountingControl;
use App\Models\AccountingAnalytics;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class AccountingControlController extends Controller
{
    /**
     * Validate fields
     *
     * @param  \Illuminate\Http\Request  $request
     */
    private function validating(Request $request)
    {
        $request->validate([
            'month' => ['required', 'string'],
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
        $accountingControls =  AccountingControl::filter($request->all());
        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'created_at';

        return view('accounting-controls.index', compact('accountingControls', 'ascending', 'orderBy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accounting-controls.create');
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

       AccountingControl::create([
            'month' => $input['month'],
            'obs' => $input['obs']
        ]);

        $resp = [
            'message' => __('Controle Contábil Cadastrado com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('accounting-controls.index')->with($resp);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accountingControl = AccountingControl::findOrFail($id);
        $accountingAnalytics = AccountingAnalytics::filter(['accounting_control_id' => $id]);

        $ascending = isset($query['ascending']) ? $query['ascending'] : 'desc';
        $orderBy = isset($query['order_by']) ? $query['order_by'] : 'created_at';

        return view('accounting-controls.show', compact('accountingControl', 'accountingAnalytics', 'ascending', 'orderBy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accountingControl = AccountingControl::findOrFail($id);
        return view('accounting-controls.edit', compact('accountingControl'));
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
        $accountingControl = AccountingControl::findOrFail($id);

        $this->validating($request);

        $input = $request->all();

        $accountingControl->update([
            'month' => $input['month'],
            'obs' => $input['obs']
        ]);

        $resp = [
            'message' => __('Controle Contábel Atualizado com Sucesso!'),
            'alert-type' => 'success'
        ];

        return redirect()->route('accounting-controls.index')->with($resp);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accountingControl = AccountingControl::findOrFail($id);

        $accountingControl->delete();

        return response()->json([
            'message' => __('Controle Contábel Apagado com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Filter accountingControl
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $accountingControls = AccountingControl::filter($request->all());
        $accountingControls = $accountingControls->setPath('');
        $orderBy = $request->get('order_by');
        $ascending = $request->get('ascending');
        $paginatePerPage = $request->get('paginate_per_page');

        return response()->json([
            'filter_result' => view('accounting-controls.filter-result', compact('accountingControls', 'orderBy', 'ascending'))->render(),
            'pagination' => view('layouts.pagination', [
                'models' => $accountingControls,
                'order_by' => $orderBy,
                'ascending' => $ascending,
                'paginate_per_page' => $paginatePerPage,
                ])->render(),
            ]);
    }

    /**
     * Import Resource
     *
     * * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx|max:4096',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $inputs = $request->all();

        if($request->file)
        {

            $accountingControl = AccountingControl::create([
                'month' => $inputs['month'],
                'obs' => $inputs['obs'],
            ]);

            $spreadsheet = IOFactory::load($request->file->path());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            foreach($rows as $key => $value)
            {
                if($key <= 1) continue;
                $validator = Validator::make([
                    'classification' => $value[0],
                    'name' => $value[1],
                    'value' => $value[2],
                ],
                [
                    'name' => ['required', 'string', 'max:255'],
                    'value' => ['required', 'numeric'],
                    'classification' => ['required', 'string']
                ]);

                if (!$validator->fails())
                {
                    AccountingAnalytics::create([
                        'classification' => $value[0],
                        'name' => $value[1],
                        'value' => $value[2],
                        'accounting_control_id' => $accountingControl->id
                    ]);
                }
            }

            return response()->json([
                'message' => __('Arquivo importado com sucesso!'),
                'alert-type' => 'success'
            ]);
        }

        return response()->json([
            'message' => __('Arquivo não importado!'),
            'alert-type' => 'error'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountingConfig;
use App\Models\TotalStaticCheckPoint;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\AccountingClassification;
use Illuminate\Support\Facades\Validator;

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

        $totalStaticCheckPoints = TotalStaticCheckPoint::where('year', $year)->groupBy('classification_id')->get();

        $months = months();

        return view('total-static-check-point.index',
        compact('ascending', 'orderBy', 'totalStaticCheckPoints', 'months', 'year', 'years'));
    }

     /**
     * Create the specified resource in storage.
     *
     * @param int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'result' => ['required', 'numeric'],
            'justification' => ['required', 'string'],
            'id' => ['required'],
            'month' => ['required'],
            'year' => ['required'],
            'type' => ['required'],
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();

        $totalStaticCheckPoint = TotalStaticCheckPoint::where('year', $input['year'])
        ->where('month',$input['month'])
        ->where('classification_id', $input['id'])
        ->where('type', $input['type'])->first();

        if($totalStaticCheckPoint) {
            $totalStaticCheckPoint->update([
                'result' => $input['result'],
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
     * Import Resource
     *
     * * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:4096',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        if($request->file)
        {

            $spreadsheet = IOFactory::load($request->file->path());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $notImport = [];
            $totalImported=0;

            foreach($rows as $key => $value)
            {
                if($key < 1) continue;
                if(!$value[0]) continue;

                $validator = Validator::make([
                    'year' => $value[0],
                    'month' => $value[1],
                    'type' => $value[2],
                    'classification' => $value[3],
                    'classification_id' => $value[4],
                    'result' => Str::replace(',', '', $value[5])
                ],
                [
                    'year' => ['required'],
                    'month' => ['required'],
                    'type' => ['required'],
                    'classification' => ['required'],
                    'classification_id' => ['required'],
                    'result' => ['required'],
                ]);

                if (!$validator->fails())
                {
                    $totalStaticCheckPoint = TotalStaticCheckPoint::firstOrCreate([
                        'year' => $value[0],
                        'month' => $value[1],
                        'type' => $value[2],
                        'classification' => $value[3],
                        'classification_id' => $value[4],
                    ]);

                    $totalStaticCheckPoint->update([
                        'result' => Str::replace(',', '', $value[5]),
                    ]);

                    //dd(Str::replace(',', '', $value[5]));

                    $totalImported++;
                }else{
                  $notImport[] = $value[0];
                }
            }

            return response()->json([
                'message' => __('Arquivo importado com sucesso!'),
                'alert-type' => 'success',
                'not_imported' => $notImport,
                'total_imported' =>  $totalImported
            ]);
        }

        return response()->json([
            'message' => __('Arquivo não importado!'),
            'alert-type' => 'error'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AccountingClassification;
use App\Models\Dre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountingConfig;
use Illuminate\Support\Facades\Validator;

class DREController extends Controller
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

        $accountingClassifications = collect([]);
        $accountingConfigs = AccountingConfig::where("year", $year)->where("month", 1)->get();
        if($accountingConfigs)
        {
            foreach ($accountingConfigs  as $key => $accountingConfig)
            {
                $accountingClassifications = $accountingClassifications->merge($accountingConfig->accountingClassifications()
                ->where('type_classification', 'DRE Ajustável')
                ->where('accounting_classifications.accounting_classification_id', null)
                ->where('visible', true)
                ->orderBy("accounting_classifications.order")
                ->get());
            }
        }
        $months = [];
        $routeMonthParams = "";

        if(isset($_GET['month']) && is_array($_GET['month'])) {
            $months = [];
            foreach ($_GET['month'] as $key => $value) {
                if($value == -1) {
                    $months = months();
                    $routeMonthParams = "&month[]=-1";
                    break;
                }
                if($value != "") {
                    $months[$value] = months()[$value];
                    $routeMonthParams .= "&month[]=$value";
                }
            }
        }

        $monthsArr = months();
        $monthsArr[-1] = "Todos";

        return view('dre.index',
        compact('ascending', 'orderBy', 'accountingClassifications', 'months', 'year', 'years', 'accountingConfigs', 'monthsArr', 'routeMonthParams'));
    }

     /**
     * Create the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => ['required', 'numeric'],
            'justification' => ['required', 'string'],
            'month' => ['required'],
            'year' => ['required'],
            'accounting_classification_id' => ['required', 'exists:accounting_classifications,id']
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }

        $input = $request->all();

        $dre = Dre::firstOrCreate([
            'year' => $input['year'],
            'month' => $input['month'],
            'accounting_classification_id' => $input['accounting_classification_id'],
        ]);

        $dre->update([
            'value' => $input['value'],
            'justification' => $input['justification'],
        ]);

        $accountingClassification = AccountingClassification::findOrFail($input['accounting_classification_id']);
        $month = $input['month'];
        $year = $input['year'];
        $result = "-";
        $total = $input['value'];
        $decimal = $accountingClassification->unity == '%' ? 2 : 0;

        if ($total > 0) {
            $result =  ($accountingClassification->unity == 'R$' ? $accountingClassification->unity  : '') .  number_format($total, $decimal, ',', '.') . ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '');
        } elseif($total < 0) {
            $result = ($accountingClassification->unity == 'R$' ? $accountingClassification->unity  : '')  . '(' . number_format($total * -1, $decimal, ',', '.') . ')' . ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '');
        }

        return response()->json([
            'message' => __('Valores atualizados com sucesso!'),
            'renderized' => view('dre.total-classification', compact('accountingClassification', 'dre', 'month', 'year', 'result', 'total'))->render(),
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
        $dre = Dre::findOrFail($id);

        $dre->delete();

        return response()->json([
            'message' => __('DRE apagado com Sucesso!!'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * get total
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function total(Request $request)
    {
        $inputs = $request->all();
        $months =  $inputs['months'];
        $year = $inputs['year'];
        $results = [];

        foreach ($months as $key => $month) {
            $dre = Dre::where("accounting_classification_id", $inputs['id'])->where("month", $key)->where("year", $year)->latest('created_at')->first();
            $accountingClassification = AccountingClassification::findOrFail($inputs['id']);
            $result = "-";
            $decimal = $accountingClassification->unity == '%' ? 2 : 0;

            if($dre){
                $total = $dre->value;
            } else {
                $total = $accountingClassification->getTotalClassificationDRE($key, $year);
            }

            if ($total > 0) {
                $result =  ($accountingClassification->unity == 'R$' ? $accountingClassification->unity  : '') .  number_format($total, $decimal, ',', '.') . ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '');
            } elseif($total < 0) {
                $result = ($accountingClassification->unity == 'R$' ? $accountingClassification->unity  : '')  . '(' . number_format($total * -1, $decimal, ',', '.') . ')' . ($accountingClassification->unity == '%' ? $accountingClassification->unity  : '');
            }

            $results[$month] = view('dre.total-classification', compact('accountingClassification', 'dre', 'month', 'year', 'result', 'total'))->render();
        }


        return response()->json($results);
    }

    /**
     * get total RL
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function totalRL(Request $request)
    {
        $inputs = $request->all();
        $year = $inputs['year'];
        $accountingClassification = AccountingClassification::findOrFail($inputs['id']);
        $total = $accountingClassification->getEspecialFomulas($year, 'RL');
        $result = "-";

        if ($total > 0) {
            $result =  number_format($total, 2, ',', '.') . '%';
        } elseif($total < 0) {
            $result = '(' . number_format($total * -1, 2, ',', '.') . ')' . '%';
        }

        return response()->json($result);
    }

    /**
     * get total NSR
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function totalNSR(Request $request)
    {
        $inputs = $request->all();
        $year = $inputs['year'];
        $accountingClassification = AccountingClassification::findOrFail($inputs['id']);
        $total = $accountingClassification->getEspecialFomulas($year, 'NSR');
        $result = "-";

        if ($total > 0) {
            $result =  number_format($total, 2, ',', '.') . '%';
        } elseif($total < 0) {
            $result = '(' . number_format($total * -1, 2, ',', '.') . ')' . '%';
        }

        return response()->json($result);
    }

    /**
     * get amount
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function amount(Request $request)
    {
        $inputs = $request->all();
        $year = $inputs['year'];
        $accountingClassification = AccountingClassification::findOrFail($inputs['id']);
        $total = $accountingClassification->getTotal($year);
        $result = "-";

        if ($total > 0) {
            $result =  'R$' .  number_format($total, 0, ',', '.');
        } elseif($total < 0) {
            $result = 'R$'  . '(' . number_format($total * -1, 0, ',', '.') . ')';
        }

        return response()->json($result);
    }
}

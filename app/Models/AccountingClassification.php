<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use ChrisKonnertz\StringCalc\StringCalc;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingClassification extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'obs', 'level', 'classification', 'type_classification', 'featured',
        'color', 'bolder', 'accounting_classification_id'
    ];

     /**
     * The Accounting Control
     */
    public function parent()
    {
        return $this->belongsTo(AccountingClassification::class);
    }

    /**
     * Returns the depth of an Instance
     * @param $idToFind
     * @return int
     */
    public static function DepthHelper($idToFind)
    {
        return AccountingClassification::GetParentHelper($idToFind);
    }

    // Recursive Helper function
    public static function  GetParentHelper($id, $depth = 0)
    {
        $model = AccountingClassification::find($id);

        if ($model->accounting_classification_id != null) {
            $depth++;

            return AccountingClassification::GetParentHelper($model->accounting_classification_id, $depth);
        } else {
            return $depth;
        }
    }

    /**
     * Get the depth of this instance from the top-level instance.
     */
    public function getDepthAttribute()
    {
        return AccountingClassification::DepthHelper($this->id);
    }

    /**
     * Get types
     *
     * @return Array
     */
    public static function getTypesClassifications()
    {
        return ['DRE', 'Retiradas Gerenciais', 'Resultado do Exercicio', 'DRE Ajustável'];
    }

    /**
     * Get types
     *
     * @return Array
     */
    public static function getTypesClassifications2()
    {
        return [
            'DRE' => 'DRE',
            'Retiradas Gerenciais' => 'Retiradas Gerenciais',
            'Resultado do Exercicio' => 'Resultado do Exercicio',
            'DRE Ajustável' => 'DRE Ajustável',
        ];
    }

    public function getDescriptionAttribute()
    {
        return $this->classification . '-' . $this->name;
    }

    /**
     * Get calc name
     *
     * @return string
     */
    public function getCalcNameAttribute()
    {
        return '{' . $this->classification . '&' . $this->name . '}';
    }

        /**
     * Get total by classification
     *
     * @param int $month
     * @param int $year
     */
    public function getTotalClassification($month, $year)
    {
        $formula = Formula::where('accounting_classification_id', $this->id)->first();

        if($formula)
        {
            $re = '/{(.*?)}/m';
            $formulaText = $formula->formula;
            preg_match_all($re, $formulaText, $matches, PREG_SET_ORDER, 0);
            $sum = 0;

            foreach ($matches as $key2 => $value2)
            {
                $result = explode("&", $value2[1]);

                $classification = self::where('classification', $result[0])->first();

                if($classification)
                {
                    $sum=0;
                    $withdrawals = Withdrawal::where('accounting_classification_id', $classification->id)
                    ->where('month', $month)
                    ->where(DB::raw('YEAR(created_at)'), '=', $year)
                    ->get();

                    foreach ($withdrawals as $withdrawal)
                    {
                        $sum += $withdrawal->value;
                    }
                }
                $formulaText = Str::replace($value2[0], $sum, $formulaText);
            }
            $stringCalc = new StringCalc();
            $result = $stringCalc->calculate($formulaText);
            return $result;
        }
        return 0;
    }

        /**
     * Get total by classification by month
     *
     * @param int $month
     * @param int $year
     */
    public static function getTotalClassificationByMonth($month, $year)
    {
        $total = 0;
        $accountingClassifications = self::where('type_classification', 'Retiradas Gerenciais')->get();
        foreach ($accountingClassifications as $key => $accountingClassification)
        {
            $total += $accountingClassification->getTotalClassification($month, $year);
        }
        return $total;
    }

    /**
     * Find users in dabase
     *
     * @param Array
     *
     * @return object
     */
    public static function filter($query)
    {
        $perPage = isset($query['paginate_per_page']) ? $query['paginate_per_page'] : DEFAULT_PAGINATE_PER_PAGE;
        $ascending = isset($query['ascending']) ? $query['ascending'] : DEFAULT_ASCENDING;
        $orderBy = isset($query['order_by']) ? $query['order_by'] : DEFAULT_ORDER_BY_COLUMN;

        $result = self::where(function($q) use ($query) {
            if(isset($query['id']))
            {
                if(!is_null($query['id']))
                {
                    $q->where('id', $query['id']);
                }
            }

            if(isset($query['name']))
            {
                if(!is_null($query['name']))
                {
                    $q->where('name', 'like','%' . $query['name'] . '%');
                }
            }

            if(isset($query['level']))
            {
                if(!is_null($query['level']))
                {
                    $q->where('level', $query['level']);
                }
            }

            if(isset($query['classification']))
            {
                if(!is_null($query['classification']))
                {
                    $q->where('classification', 'like','%' . $query['classification'] . '%');
                }
            }

            if(isset($query['type_classification']))
            {
                if(!is_null($query['type_classification']))
                {
                    $q->where('type_classification', $query['type_classification']);
                }
            }
        });

        $result->orderBy($orderBy, $ascending);

        return $result->paginate($perPage);
    }
}

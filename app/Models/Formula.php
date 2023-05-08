<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accounting_classification_id', 'type_classification', 'formula', 'obs',
        'conditional', 'conditional_type', 'conditional_value', 'conditional_formula'
    ];

    public function getFullNameAttribute()
    {
        return "[$this->id] - " . ($this->accountingClassification ? $this->accountingClassification->description : '') . ' = ' . $this->formula;
    }

     /**
     * The Accounting Classification
     */
    public function accountingClassification()
    {
        return $this->belongsTo(AccountingClassification::class);
    }

    /**
     * The months
     */
    public function months()
    {
        return $this->hasMany(MonthFormula::class);
    }

    /**
     * Get conditional types
     */
    public static function conditionalTypes()
    {
        return [
            "==" => "Igual",
            "!=" => "Diferente",
            ">" => "Maior",
            "<" => "Menor",
            ">=" => "Maior Igual",
            "<=" => "Menor Igual",
        ];
    }

    /**
     * Retur value in conditional
     *
     * @param string $token
     * @return bool
     */
    public static function conditionalCalc($token, $a, $b)
    {
        switch ($token)
        {
            case '==':
                return $a == $b;
                break;

            case '!=':
                return $a != $b;
                break;

            case '>':
                return $a > $b;
                break;

            case '<':
                return $a < $b;
                break;

            case '>=':
                return $a >= $b;
                break;

            case '<=':
                return $a <= $b;
                break;

            default:
                return false;
                break;
        }
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

            if(isset($query['accounting_classification_id']))
            {
                if(!is_null($query['accounting_classification_id']))
                {
                    $q->where('accounting_classification_id', $query['accounting_classification_id']);
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

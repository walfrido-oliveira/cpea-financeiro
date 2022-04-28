<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountingClassification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Withdrawal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accounting_classification_id', 'month', 'value'
    ];

    /**
     * The Accounting Classification
     */
    public function accountingClassification()
    {
        return $this->belongsTo(AccountingClassification::class);
    }

    /**
     * Get total by mont and year
     *
     * @param int $month
     * @param int $year
     */
    public static function getTotalByMonth($month, $year)
    {
        $result = self::where('month', $month)->where(DB::raw('YEAR(created_at)'), '=', $year)->get();
        $sum = 0;

        foreach ($result as $key => $value)
        {
            $sum += $value->value;
        }
        return $sum;
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

            if(isset($query['month']))
            {
                if(!is_null($query['month']))
                {
                    $q->where('month', $query['month']);
                }
            }

            if(isset($query['year']))
            {
                if(!is_null($query['year']))
                {
                    $q->where(DB::raw('YEAR(created_at)'), '=', $query['year']);
                }
            }
        });

        $result->orderBy($orderBy, $ascending);

        return $result->paginate($perPage);
    }
}

<?php

namespace App\Models;

use App\Models\AccountingControl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountingAnalytics extends Model
{
    use HasFactory;


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value', 'classification', 'accounting_control_id', 'justification'
    ];

    /**
     * The Accounting Control
     */
    public function accountingControl()
    {
        return $this->belongsTo(AccountingControl::class);
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

            if(isset($query['classification']))
            {
                if(!is_null($query['classification']))
                {
                    $q->where('classification', 'like','%' . $query['classification'] . '%');
                }
            }

            if(isset($query['accounting_control_id']))
            {
                if(!is_null($query['accounting_control_id']))
                {
                    $q->where('accounting_control_id', $query['accounting_control_id']);
                }
            }

            if(isset($query['q']))
            {
                if(!is_null($query['q']))
                {
                    $q->where('name', 'like', '%' . $query['q'] . '%')
                    ->orWhere('classification', 'like', '%' . $query['q'] . '%');
                }
            }
        });

        $result->orderBy($orderBy, $ascending);

        return $result->paginate($perPage);
    }
}

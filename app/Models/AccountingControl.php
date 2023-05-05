<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingControl extends Model
{
    use HasFactory;

    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'month', 'year', 'obs', 'type'
    ];

    public static function getTypes()
    {
        return [
            'Contábil' => 'Contábil',
            'Retiradas' => 'Retiradas',
            "Horas Projetos" => "Horas Projetos",
            "Horas Administrativas" => "Horas Administrativas",
        ];
    }

    /**
     * The Accounting Analytics
     */
    public function accountingAnalytics()
    {
        return $this->hasMany(AccountingAnalytics::class);
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

            if(isset($query['month']))
            {
                if(!is_null($query['month']))
                {
                    $q->where('month', $query['month']);
                }
            }

            if(isset($query['type']))
            {
                if(!is_null($query['type']))
                {
                    $q->where('type',  $query['type'] );
                }
            }

            if(isset($query['year']))
            {
                if(!is_null($query['year']))
                {
                    $q->where('year',  $query['year'] );
                }
            }

        });

        $result->orderBy($orderBy, $ascending);

        return $result->paginate($perPage);
    }
}

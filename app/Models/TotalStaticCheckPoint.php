<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalStaticCheckPoint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year', 'month', 'type', 'classification', 'resut', 'classification_id'
    ];

    public static function getTypes()
    {
        return ['Horas Projetos', 'Horas Administrativas'];
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
                    $q->where('month', $query['yemonthar']);
                }
            }

            if(isset($query['year']))
            {
                if(!is_null($query['year']))
                {
                    $q->where('year', $query['year']);
                }
            }
        });

        $result->orderBy($orderBy, $ascending);

        return $result->paginate($perPage);
    }

    /**
     * Get total
     *
     * @param int $year
     * @param int $month
     * @param int $classification_id
     * @param string $type1
     * @param string $type2
     *
     * @return double
     */
    public static function getTotal($year, $month, $classification_id, $type1, $type2)
    {
        $result1 = self::setQueryToral($year, $month, $classification_id, $type1)->sum('result');
        $result2 = self::setQueryToral($year, $month, $classification_id, $type2)->sum('result');
        return $result1 + $result2 > 0 ? round(($result1 / ($result1 + $result2)), 4, PHP_ROUND_HALF_UP)  : 0;
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $classification_id
     * @param string $type1
     * @param string $type2
     */
    private static function setQueryToral($year, $month, $classification_id, $type)
    {
        return self::where(function($q) use ($year, $month, $classification_id, $type) {
            if($year)
            {
                $q->where('year', $year);
            }

            if($month)
            {
                $q->where('month', $month);
            }

            if($classification_id)
            {
                $q->where('classification_id', $classification_id);
            }

            $q->where('type', $type);
        });
    }
}

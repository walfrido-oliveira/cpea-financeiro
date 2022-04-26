<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingClassification extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'obs', 'level', 'classification', 'type_classification'
    ];

    /**
     * Get types
     *
     * @return Array
     */
    public static function getTypesClassifications()
    {
        return ['DRE', 'Retiradas Gerenciais', 'Resultado do Exercicio'];
    }

    /**
     * Get types
     *
     * @return Array
     */
    public static function getTypesClassifications2()
    {
        return ['DRE' => 'DRE',
                'Retiradas Gerenciais' => 'Retiradas Gerenciais',
                'Resultado do Exercicio' => 'Resultado do Exercicio'
            ];
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

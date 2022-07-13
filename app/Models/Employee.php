<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'occupation_id',
        'direction_id',
        'department_id',
        'user_id',
        'manager_id',
        'occupation_type',
        'employee_id',
        'admitted_at',
        'working_day_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'admitted_at' => 'date',
    ];

    /**
     * The user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The manager
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    /**
     * The work day
     */
    public function workingDay()
    {
        return $this->belongsTo(WorkingDay::class);
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

            if(isset($query['employee_id']))
            {
                if(!is_null($query['employee_id']))
                {
                    $q->where('employee_id', 'like','%' . $query['employee_id'] . '%');
                }
            }

            if(isset($query['name']))
            {
                if(!is_null($query['name']))
                {
                    $q->whereHas('user', function($q) use($query) {

                        $q->where('users.name', 'like','%' . $query['name'] . '%');
                    });
                }
            }

            if(isset($query['user_id']))
            {
                if(!is_null($query['user_id']))
                {
                    $q->where('user_id', 'like', $query['user_id'] );
                }
            }
        });

        $result->orderBy($orderBy, $ascending);

        return $result->paginate($perPage);
    }
}

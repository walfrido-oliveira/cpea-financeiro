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
        'working_day_id',
        'hour_cost'
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
     * Get balance of hours
     */
    public function balance($start)
    {
        $hours = 0;
        $minutes = 0;

        foreach ($this->user->checkPoints->where('start', $start) as $checkpoint)
        {
            $minutes += $checkpoint->start->diffInHours($checkpoint->end) * 60;
            $minutes += $checkpoint->start->diff($checkpoint->end)->i;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;

        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

        return "${hours}:${minutes}";
    }

    /**
     * Get balance of hours
     */
    public function balanceByMonthAndYear($month, $year)
    {
        $hours = 0;
        $minutes = 0;

        foreach ($this->user->checkPoints()->whereMonth('start', $month)->whereYear('start', $year)->get() as $checkpoint)
        {
            $minutes += $checkpoint->start->diffInHours($checkpoint->end) * 60;
            $minutes += $checkpoint->start->diff($checkpoint->end)->i;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;

        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

        return "${hours}:${minutes}";
    }

    public static function totalBalanceByMonthAndYear($month, $year)
    {
        $totalHours = 0;
        $totalMinutes = 0;

        $employees = self::all();
        foreach ($employees as $employee)
        {
            $time = explode(":", $employee->balanceByMonthAndYear($month, $year));

            $hours = $time[0];
            $minutes = $time[1];

            $totalMinutes += $hours * 60;
            $totalMinutes += $minutes;
        }

        $totalHours = floor($totalMinutes / 60);
        $totalMinutes -= $totalHours * 60;

        $totalHours = str_pad($totalHours, 2, "0", STR_PAD_LEFT);
        $totalMinutes = str_pad($totalMinutes, 2, "0", STR_PAD_LEFT);

        return "${totalHours}:${totalMinutes}";
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

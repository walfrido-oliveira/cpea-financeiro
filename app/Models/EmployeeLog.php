<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLog extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'value'
    ];

    /**
     * The User
     */
    public function user()
    {
        return $this->belongsTo(user::class);
    }

    /**
     * The Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

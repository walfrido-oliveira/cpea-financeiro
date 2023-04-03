<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'justification',
        'month',
        'year',
        'accounting_classification_id'
    ];

    /**
     * The Accounting Classifications.
     */
    public function accountingClassification()
    {
        return $this->belongsTo(AccountingClassification::class);
    }
}

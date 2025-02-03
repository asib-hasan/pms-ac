<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expense';
    use HasFactory;

    protected $fillable = [
        'expense_criteria',
        'expense_amount',
        'expense_date',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'expense_id',
    ];

    protected $primaryKey = 'expense_id';

    public function expense_head_info(){
        return $this->belongsTo(AccHead::class, 'expense_head_id', 'id');
    }

}

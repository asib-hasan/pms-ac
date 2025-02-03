<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWiseOrder extends Model
{
    protected $table = 'customer_wise_order';
    use HasFactory;

    protected $fillable = [
        'cwo_customer_id',
        'cwo_order_id',
        'cwo_order_total',
        'cwo_due',
        'cwo_date',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'cwo_id',
    ];
    protected $primaryKey = 'cwo_id';

    public function customer_info(){
        return $this->belongsTo(CustomerInfo::class,'cwo_customer_id','customer_id');
    }
}

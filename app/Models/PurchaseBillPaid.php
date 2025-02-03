<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseBillPaid extends Model
{
    protected $table = 'bill_paid';
    use HasFactory;

    protected $fillable = [
        'bp_company_id',
        'bp_purchase_id',
        'bp_amount',
        'bp_date',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'item_category_id',
    ];

    public function admin_info(){
        return $this->belongsTo(User::class, 'created_by','admin_id');
    }
}

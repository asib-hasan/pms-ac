<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseInfo extends Model
{
    protected $table = 'purchase';
    use HasFactory;

    protected $fillable = [
        'purchase_company_id',
        'purchase_date',
        'purchase_invoice_no',
        'amount',
        'purchase_mode',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'purchase_id',
    ];
}

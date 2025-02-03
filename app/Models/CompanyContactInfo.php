<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyContactInfo extends Model
{
    protected $table = 'contact_info';
    use HasFactory;

    protected $fillable = [
        'contact_info_company_id',
        'contact_info_name ',
        'contact_info_email',
        'contact_info_phone',
        'contact_info_fax',
        'contact_info_designation'
    ];


    protected $hidden = [
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    protected $guarded = [
        'contact_info_id ',
    ];

    #Relations
    public function company_info(){
        return $this->belongsTo(Companies::class, 'contact_info_company_id','company_id');
    }
}

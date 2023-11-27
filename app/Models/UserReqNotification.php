<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReqNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'regiReq_id','is_seen'
    ];

    public function registrationReqUser(){
        $this->belongsTo(RegistrationRequest::class,'regiReq_id','id');
    }
}

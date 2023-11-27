<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationRequest extends Model
{
    use HasFactory;

    protected $fillable  = [
        'full_name','library_name','contact_number','library_address','image','status','created_at','email'
    ];
}

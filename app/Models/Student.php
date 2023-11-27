<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id','manager_id','lib_id','name', 'email', 'password', 'personal_number', 'emergency_number', 'dob', 'course', 'current_address', 'permanent_address', 'subscription', 'remark_singnature', 'hall_number', 'vehicle_number', 'aadhar_number', 'aadhar_front_img', 'aadhar_back_img', 'image','status','payment','pending_payment','created_by'];

    public function plan()
    {
        return $this->hasOne(Plan::class);
    }

    public function createdByStudent()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }
}

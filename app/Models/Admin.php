<?php

namespace App\Models;

use App\Concerns\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Admin extends User // add more feature
{
    use HasFactory,
        Notifiable,
        HasApiTokens,
        HasRoles;

    protected $fillable=[
        'name','email','password','phone_number','super_admin','status'
    ];

    public static function booted()
    {
         static::creating(function($model){
             $model->username=Str::slug($model->name);
         });
    }
}

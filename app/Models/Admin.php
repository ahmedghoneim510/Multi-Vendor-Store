<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth;
use Illuminate\Notifications\Notifiable;

class Admin extends User // add more feature
{
    use HasFactory,Notifiable;
    protected $fillable=[
        'name','email','password','phone_number','super_admin','status'
    ];

}

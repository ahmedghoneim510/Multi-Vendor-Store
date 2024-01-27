<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable=['name','slug'];
    public $timestamps=false; // bcuz we deleted it from table
    public function products(){
        return $this->belongsToMany(Product::class);
    }
}

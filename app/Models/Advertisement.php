<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    // public function user()
    // {
    //     return $this->hasOne(User::class, 'id');
    // }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

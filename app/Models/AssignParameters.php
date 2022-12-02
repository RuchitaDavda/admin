<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignParameters extends Model
{
    use HasFactory;
    public function modal()
    {
        return $this->morphTo();
    }
    public function parameter()
    {
        return  $this->belongsTo(parameter::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dmeter extends Model
{

    protected $table = 'd_meter';

    use HasFactory;


    public function contador()
{
    return $this->belongsTo(Contador::class);
}
}

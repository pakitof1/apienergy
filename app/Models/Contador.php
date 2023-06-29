<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    protected $table = 'contadores';

    use HasFactory;

    public function dmeters()
    {
        return $this->hasMany(Dmeter::class, 'contador_id');
    }

    public function contadorMeterResume()
    {
        return $this->hasMany(ContadorMeterResume::class, 'contador_id');
    }
}

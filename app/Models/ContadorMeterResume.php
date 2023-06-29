<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContadorMeterResume extends Model
{

    protected $table = 'contador_meter_resume';

    use HasFactory;

    public function contador()
    {
        return $this->belongsTo(Contador::class);
    }
    
}

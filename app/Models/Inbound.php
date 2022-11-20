<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    use HasFactory;
    protected $table = 'inbound';
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'id_supplier', 'id');
    }
}

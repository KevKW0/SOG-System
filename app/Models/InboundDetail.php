<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundDetail extends Model
{
    use HasFactory;
    protected $table = 'inbound_detail';
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'id_product');
    }
}

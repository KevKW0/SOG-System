<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;
    protected $table = 'retur';
    protected $guarded = [];
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'id_customer', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}

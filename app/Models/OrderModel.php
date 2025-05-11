<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    protected $table = "order_models";
    protected $fillable = [
        'angota_id',
        'amount',
        'simpanan_id',
        'status',
        'jenis',
        'updated_at',
        'created_at',
        
    ];
}

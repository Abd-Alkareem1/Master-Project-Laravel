<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\OrderList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];

    public function orderLists() {
        return $this->hasMany(OrderList::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}

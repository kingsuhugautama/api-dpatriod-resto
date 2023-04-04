<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transOrder extends Model
{
    use HasFactory;
    protected $table="trans_order";
    protected $primaryKey = 'id_order';
    protected $fillable = [
        'uuid',
        'id_customer',
        'total_qty',
        'total_price',
        'name_user',
        'id_type_payment',
        'price_user',
        'return_price_user',
        'discount'
    ];

    public function type_payment(){
        return $this->belongsTo(masterTypePayment::class, 'id_type_payment');
    }
    public function transOrderDetail(){
        return $this->hasMany(transOrderDetail::class, 'id_order');
    }
}

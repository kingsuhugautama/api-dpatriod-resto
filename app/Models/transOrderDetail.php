<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transOrderDetail extends Model
{
    use HasFactory;
    protected $table="trans_order_detail";
    protected $primaryKey = 'id_order_detail';
    protected $fillable = [
        'id_order',
        'id_menu',
        'qty',
        'total_price',
        'note',
        'status',
        'is_paid',
        'price_satuan'
    ];

    public function trans_order(){
        return $this->belongsTo(transOrder::class, 'id_order');
    }

    public function master_menu(){
        return $this->belongsTo(masterMenu::class, 'id_menu');
    }
    public function master_customer(){
        return $this->belongsTo(masterCustomer::class, 'id_customer');
    }
}

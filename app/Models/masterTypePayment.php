<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterTypePayment extends Model
{
    use HasFactory;
    protected $table="master_type_payment";
    protected $primaryKey = 'id_type_payment';
    protected $fillable = [
        'name_payment'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransInvoice extends Model
{
    use HasFactory;
    protected $table="trans_invoice";
    protected $primaryKey = 'id_invoice';
    protected $fillable = [
        'uuid',
        'id_invoice',
        'referenceNo',
        'tXid',
        'status',
        'body',
        'is_active'
    ];
}

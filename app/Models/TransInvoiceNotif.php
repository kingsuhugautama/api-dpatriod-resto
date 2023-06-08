<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransInvoiceNotif extends Model
{
    use HasFactory;
    protected $table="trans_invoice_notif";
    protected $primaryKey = 'id_invoice_notif';
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

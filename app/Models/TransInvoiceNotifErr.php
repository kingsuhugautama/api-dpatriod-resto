<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransInvoiceNotifErr extends Model
{
    use HasFactory;
    protected $table="trans_invoice_notif_err";
    protected $fillable = [
        'message',
        'body',
    ];
}

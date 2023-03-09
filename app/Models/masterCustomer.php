<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterCustomer extends Model
{
    use HasFactory;
    protected $table="master_customer";
    protected $primaryKey = 'id_customer';
    protected $fillable = [
        'name_customer',
        'email_customer',
        'phone_customer',
        'gender_customer',
        'password',
        'image'
    ];

    protected $appends = ['url_image'];
    public function getUrlImageAttribute($image)
    {
        return url('/').'/images/customer/'.$this->image;
    }
}

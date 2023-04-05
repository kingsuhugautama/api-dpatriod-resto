<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class masterCustomer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="master_customer";
    protected $primaryKey = 'id_customer';
    protected $dates = ['deleted_at'];
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
    public function trans_order(){
        return $this->belongsTo(transOrder::class, 'id_order');
    }
    public function transOrderDetail(){
        return $this->hasMany(transOrderDetail::class, 'id_order');
    }
}

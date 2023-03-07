<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterBanner extends Model
{
    use HasFactory;
    protected $table="master_banner";
    protected $primaryKey = 'id_banner';
    protected $fillable =   [
        'uuid',
        'image',
        'title'
    ];
    
    protected $appends = ['url_image'];
    public function getUrlImageAttribute($image)
    {
        return url('/').'/images/banner/'.$this->image;
    }
}

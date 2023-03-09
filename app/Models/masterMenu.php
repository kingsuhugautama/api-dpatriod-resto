<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterMenu extends Model
{
    use HasFactory;
    protected $table="master_menu";
    protected $primaryKey = 'id_menu';
    protected $fillable = [
        'id_category',
        'name',
        'price',
        'image'
    ];

    public function category(){
        return $this->belongsTo(masterCategory::class, 'id_category');
    }

    protected $appends = ['url_image'];
    public function getUrlImageAttribute($image)
    {
        return url('/').'/images/menu/'.$this->image;
    }
}
?>

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class masterMenu extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="master_menu";
    protected $primaryKey = 'id_menu';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id_category',
        'name',
        'price',
        'image',
        'ready'
    ];

    public function category(){
        return $this->belongsTo(masterCategory::class, 'id_category');
    }

    protected $appends = ['url_image'];
    public function getUrlImageAttribute($image)
    {
        return url('/').'/images/menu/'.$this->image;
    }

    public function transOrderDetail(){
        return $this->belongsTo(transOrderDetail::class, 'id_order');
    }
}
?>

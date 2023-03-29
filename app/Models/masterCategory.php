<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class masterCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="master_category";
    protected $dates = ['deleted_at'];
    // 
    protected $fillable = [
        'name_category'
    ];
    protected $primaryKey = 'id_category';
}
?>
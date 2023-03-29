<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class masterPosition extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="master_position";
    protected $primaryKey = 'id_position';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name_position'
    ];
}

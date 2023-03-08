<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterPosition extends Model
{
    use HasFactory;
    protected $table="master_position";
    protected $primaryKey = 'id_position';
    protected $fillable = [
        'name_position'
    ];
}

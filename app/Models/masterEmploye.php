<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterEmploye extends Model
{
    use HasFactory;
    protected $table="master_employe";
    protected $primaryKey = 'id_employe';
    protected $fillable = [
        'id_position',
        'name',
            'gender',
            'image',
            'email',
            'phone',
            'password'
    ];

    public function position(){
        return $this->belongsTo(masterPosition::class, 'id_position');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachProfess extends Model
{
    //
    public $timestamps = true;
    protected $table = 't_teach_profess';
    protected $fillable = [
        'professional','desc','status','profess_type'
    ];

}

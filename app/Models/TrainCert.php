<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainCert extends Model
{
    //
    public $timestamps =true;
    protected $table = 't_train_cert';

    protected $fillable =[
        'order_id','student_id','student_name','train_id','train_name','student_position','park_name','score','number','cert_picture'
    ];
	public function student_cert(){
        return $this->hasOne('App\Models\Students','student_id','student_id');
    }
}

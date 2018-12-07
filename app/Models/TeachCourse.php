<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachCourse extends Model
{
    public $timestamps = true;
    protected $table='t_teach_course';
    protected $fillable =[
        'train_id','course_name','desc','status','course_type'
    ];
    public function get_train(){
        return $this->hasOne('App\Models\Trains','id','train_id')->where('status',2)->select('id','title');
    }
}

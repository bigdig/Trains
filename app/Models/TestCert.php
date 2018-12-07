<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCert extends Model
{
    protected $connection = 'mysql_cert';

    protected $table = 'nt_excel_teacher';
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaves_request extends Model
{
    protected $table = 'leaves-requests'; // Tablo ismini açıkça belirtin

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
    ];
}

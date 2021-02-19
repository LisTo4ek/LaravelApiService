<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $table = "responses";

    protected $primaryKey = 'id';


    protected $fillable = [
        'job_status_id',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];
}

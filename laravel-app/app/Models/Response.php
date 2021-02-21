<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    /**
     * @var string
     */
    protected $table = "responses";

    /**
     * @var string
     */
    protected $primaryKey = 'id';


    /**
     * @var string[]
     */
    protected $fillable = [
        'job_status_id',
        'response',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'response' => 'array',
    ];
}

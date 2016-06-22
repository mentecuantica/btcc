<?php

namespace Btcc\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SupportTicket
 * @package Btcc\Models
 */
class SupportTicket extends Model
{
    use SoftDeletes;

    public $table = 'support_tickets';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'subject', 'message'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'status' => 'integer',
        'operator_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'status' => 'required',
        'message' => 'required',
        'subject'=> 'required',
    ];
}

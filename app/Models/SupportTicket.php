<?php

namespace Btcc\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SupportTicket
 *
 * @package Btcc\Models
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $operator_id
 * @property string $subject
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Btcc\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereOperatorId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\SupportTicket whereDeletedAt($value)
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
        //'user_id' => 'required',

        //'status' => 'required',
        'message' => 'required',
        'subject'=> 'required',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

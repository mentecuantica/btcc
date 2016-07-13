<?php

namespace Btcc\Http\Requests;

use Btcc\Http\Requests\Request;
use Btcc\Models\SupportTicket;
use Btcc\Models\Transaction;
use Btcc\Models\Transaction\UserTransaction;

class CreateSupportTicketRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return SupportTicket::$rules;
    }
}

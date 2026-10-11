<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'customer_id',
        'master_document_id',
        'phone_number',
        'message',
        'document_title',
        'document_url',
        'status',
        'provider_response',
    ];

    protected $casts = [
        'provider_response' => 'array',
    ];
}

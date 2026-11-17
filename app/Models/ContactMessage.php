<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const STATUSES = [
        'new' => 'Baru',
        'read' => 'Sudah dibaca',
        'replied' => 'Sudah dibalas',
        'archived' => 'Diarsipkan',
    ];

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status', 'ip_address',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}

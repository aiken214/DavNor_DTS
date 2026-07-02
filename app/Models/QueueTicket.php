<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class QueueTicket extends Model
{
    use HasFactory;

    protected $table = 'queue_tickets';

    protected $fillable = [
        'ticket_number',
        'client_name',
        'status',
        'transaction_type',
        'push_subscription',
    ];

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}

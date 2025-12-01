<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'entity_type',
        'trigger_event',
        'days_offset',
        'send_time',
        'subject',
        'body',
        'recipient_type',
        'custom_emails',
        'include_completion_report',
        'enabled',
        'last_sent_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'include_completion_report' => 'boolean',
        'last_sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope: Get only enabled reminders
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope: Get reminders by entity type
     */
    public function scopeForEntity($query, $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    /**
     * Scope: Get reminders by trigger event
     */
    public function scopeByTrigger($query, $trigger)
    {
        return $query->where('trigger_event', $trigger);
    }

    /**
     * Get custom recipient emails as array
     */
    public function getCustomEmailsArray()
    {
        return $this->custom_emails 
            ? array_filter(array_map('trim', explode(',', $this->custom_emails)))
            : [];
    }

    /**
     * Set custom emails from array
     */
    public function setCustomEmailsFromArray($emails)
    {
        $this->custom_emails = is_array($emails) ? implode(',', array_filter($emails)) : $emails;
    }
}

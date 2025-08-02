<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class DocumentUserView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'ip_address',
        'viewed_at',
        'user_agent',
        'platform',
        'browser',
        'device'
    ];

    public function getBrowserInfoAttribute()
    {
        $agent = new Agent();
        $agent->setUserAgent($this->user_agent);

        return [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device' => $agent->device(),
        ];
    }

    public static function existsWithinTimeWindow($documentId, $userId, $ipAddress, $minutes = 60)
    {
        return static::where('document_id', $documentId)
            ->where('ip_address', $ipAddress)
            ->where(function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->whereNull('user_id');
                }
            })
            ->where('user_agent', request()->userAgent())
            ->where('viewed_at', '>=', now()->subMinutes($minutes))
            ->exists();
    }
}

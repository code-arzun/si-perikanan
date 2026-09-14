<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyFeedLog extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'batch_id', 'feed_type_id', 'logged_by',
        'feed_date', 'feed_time', 'amount_kg', 'appetite_response', 'notes',
    ];

    public function batch() { return $this->belongsTo(Batch::class); }
    public function feedType() { return $this->belongsTo(FeedType::class); }
    public function user() { return $this->belongsTo(User::class, 'logged_by'); }
}
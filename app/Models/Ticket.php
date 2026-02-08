<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read int $id
 * @property string $subject
 * @property string $text
 * @property string $status
 * @property Carbon $manager_responded
 * @property int $customer_id
 * @property Customer $customer
 * @property string $created_at
 * @property string $updated_at
 */
class Ticket extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'subject',
        'text',
        'status',
        'manager_responded',
        'customer_id',
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $media->name = Hash::make($media->name);
        $this->addMediaConversion('thumb')
            ->sharpen(10);
    }
}

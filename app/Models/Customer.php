<?php

namespace App\Models;

use App\Helpers\PhoneNumber\PhoneNumberHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use libphonenumber\NumberParseException;

/**
 * @property-read $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property Ticket[] $tickets
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone'
    ];

    /**
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'customer_id', 'id');
    }

    /**
     * @throws NumberParseException
     */
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = PhoneNumberHelper::formatPhoneNumber($value);
    }
}

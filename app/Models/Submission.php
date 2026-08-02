<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $fillable = [
        'registration_number',
        'full_name',
        'nik',
        'kk_number',
        'address',
        'email',
        'village_id',
        'product_name',
        'product_description',
        'product_photo_url',
        'nib_url',
        'category',
        'status',
        'village_notes',
        'district_notes',
        'survey_photo_url',
        'rejection_reason',
        'visited',
        'verified_by_village_id',
        'verified_by_district_id',
    ];

    protected function casts(): array
    {
        return ['visited' => 'boolean'];
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
    public function verifiedByVillage(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_village_id');
    }
    public function verifiedByDistrict(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_district_id');
    }

    public static function generateRegistrationNumber(): string
    {
        $year = now()->year;
        $lastNumber = self::whereYear('created_at', $year)->count() + 1;

        return 'LPW-' . $year . '-' . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
    }
}

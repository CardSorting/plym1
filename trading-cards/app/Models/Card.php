<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'image_url',
        'rarity',
        'is_published',
        'mana_cost',
        'card_type',
        'abilities',
        'flavor_text',
        'power_toughness',
        'set_name',
        'card_number'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'mana_cost' => 'array'
    ];

    public static function generateRandomRarity(): string
    {
        $weights = [
            'common' => 60,
            'uncommon' => 25,
            'rare' => 10,
            'legendary' => 5
        ];

        $total = array_sum($weights);
        $roll = random_int(1, $total);
        $current = 0;

        foreach ($weights as $rarity => $weight) {
            $current += $weight;
            if ($roll <= $current) {
                return $rarity;
            }
        }

        return 'common'; // fallback
    }

    public static function generateCardNumber(): string
    {
        $prefix = date('Ymd');
        $random = str_pad((string)random_int(1, 999), 3, '0', STR_PAD_LEFT);
        return $prefix . $random;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function packs(): BelongsToMany
    {
        return $this->belongsToMany(Pack::class);
    }
}

<?php

namespace OpenDominion\Models;

/**
 * OpenDominion\Models\Race
 *
 * @property int $id
 * @property string $name
 * @property string $alignment
 * @property string $home_land_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OpenDominion\Models\Dominion[] $dominions
 * @property-read \Illuminate\Database\Eloquent\Collection|\OpenDominion\Models\RacePerkType[] $perks
 * @property-read \Illuminate\Database\Eloquent\Collection|\OpenDominion\Models\Unit[] $units
 * @method static \Illuminate\Database\Eloquent\Builder|\OpenDominion\Models\Race newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\OpenDominion\Models\Race newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\OpenDominion\Models\Race query()
 * @mixin \Eloquent
 */
class Race extends AbstractModel
{
    public function dominions()
    {
        return $this->hasMany(Dominion::class);
    }

    public function perks()
    {
        return $this->belongsToMany(
            RacePerkType::class,
            'race_perks',
            'race_id',
            'race_perk_type_id'
        )
            ->withTimestamps()
            ->withPivot('value');
    }

    public function units()
    {
        return $this->hasMany(Unit::class)
            ->orderBy('slot');
    }

    /**
     * Gets a Race's perk multiplier.
     *
     * @param string $key
     * @return float
     */
    public function getPerkMultiplier(string $key): float
    {
        $perks = $this->perks->filter(function (RacePerkType $racePerkType) use ($key) {
            return ($racePerkType->key === $key);
        });

        if ($perks->isEmpty()) {
            return 0;
        }

        return ((float)$perks->first()->pivot->value / 100);
    }

    /**
     * Try to get a unit perk value with provided key for a specific slot.
     *
     * @param int $slot
     * @param string|string[] $unitPerkTypes
     * @param mixed $default
     * @return int|int[]
     */
    public function getUnitPerkValueForUnitSlot(int $slot, $unitPerkTypes, $default = 0)
    {
        if (!is_array($unitPerkTypes)) {
            $unitPerkTypes = [$unitPerkTypes];
        }

        $unitCollection = $this->units->where('slot', '=', $slot);
        if ($unitCollection->isEmpty()) {
            return $default;
        }

        $perkCollection = $unitCollection->first()->perks->whereIn('key', $unitPerkTypes);
        if ($perkCollection->isEmpty()) {
            return $default;
        }

        $perkValue = $perkCollection->first()->pivot->value;
        if (str_contains($perkValue, ',')) {
            $perkValue = explode(',', $perkValue);
        }

        return $perkValue;
    }
}

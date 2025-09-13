<?php

namespace Core\Traits;

use Modules\Addresses\Enums\AddressTypeEnum;
use Modules\Addresses\Models\Address;

trait BelongsToCurrentAddress
{
    public function currentAddress()
    {
        if (is_null($this->current_address_id) && $this->id) {
            $this->switchAddress($this->primaryAddress());
        }

        return $this->belongsTo(Address::class, 'current_address_id');
    }

    public function switchAddress($address): bool
    {
        if (!$this->belongsToAddress($address)) {
            return false;
        }

        $this->update([
            'current_address_id' => $address->id,
        ]);

        $this->setRelation('currentAddress', $address);

        return true;
    }

    public function belongsToAddress($address): bool
    {
        return $address && ($this->ownsAddress($address) || $this->addresses?->contains(fn ($t) => $t->id === $address->id));
    }

    public function primaryAddress()
    {
        return $this->addresses->firstWhere('address_type', AddressTypeEnum::PRIMARY);
    }

    public function ownsAddress($address): bool
    {
        return $address && $this->id === $address->{$this->getForeignKey()};
    }
}

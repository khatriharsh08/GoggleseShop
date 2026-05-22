<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Collection;

class AddressService
{
    public function getUserAddresses(User $user): Collection
    {
        return $user->addresses()->orderByDesc('is_default')->get();
    }

    public function createAddress(User $user, array $data): Address
    {
        if (! empty($data['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        return $user->addresses()->create($data);
    }

    public function updateAddress(User $user, Address $address, array $data): Address
    {
        if (! empty($data['is_default'])) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($data);

        return $address;
    }

    public function deleteAddress(Address $address): void
    {
        $address->delete();
    }
}

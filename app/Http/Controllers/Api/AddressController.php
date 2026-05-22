<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    public function __construct(public AddressService $addressService) {}

    public function index(): JsonResponse
    {
        return response()->json(AddressResource::collection($this->addressService->getUserAddresses(request()->user())));
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = $this->addressService->createAddress($request->user(), $request->validated());

        return response()->json(['message' => 'Address created', 'address' => new AddressResource($address)], 201);
    }

    public function update(StoreAddressRequest $request, Address $address): JsonResponse
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        } $address = $this->addressService->updateAddress($request->user(), $address, $request->validated());

        return response()->json(['message' => 'Address updated', 'address' => new AddressResource($address)]);
    }

    public function destroy(Address $address): JsonResponse
    {
        if ($address->user_id !== request()->user()->id) {
            abort(403);
        } $this->addressService->deleteAddress($address);

        return response()->json(['message' => 'Address deleted']);
    }
}

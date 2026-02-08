<?php

namespace App\Services\Customer\Http;

use App\Exceptions\AppLogicException;
use App\Models\Customer;
use App\Services\Customer\Dto\FindOrStoreCustomerDto;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class CustomerService
{
    /**
     * @throws AppLogicException
     */
    public function FindOrStore(FindOrStoreCustomerDto $dto): Customer
    {
        $customer = Customer::query()->where('email', 'like', '%' . $dto->email . '%')->where('phone', 'like', '%' . $dto->phone . '%')->first();

        if ($customer) {
            return $customer;
        }

        if (Customer::query()->orWhere('phone', 'like', '%' . $dto->phone . '%')->orWhere('email', 'like', '%' . $dto->email . '%')->exists()) {
            throw new AppLogicException('Phone or Email already exists', ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer = new Customer();
        $customer->name = $dto->name;
        $customer->email = $dto->email;
        $customer->phone = $dto->phone;
        $customer->save();

        return $customer;
    }
}

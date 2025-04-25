<?php

namespace App\Service;

use App\Repository\Interfaces\CustomerRepositoryInterface;
use App\Exceptions\CustomerException;

class CustomerService
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getCustomer(int $id)
    {
        return $this->customerRepository->find($id);
    }

    public function getAllCustomers()
    {
        return $this->customerRepository->all();
    }

    public function getCustomerByEmail($email)
    {
        return $this->customerRepository->findByEmail($email);
    }

    /**
     * @param string $email search criteria
     * @param array $data to be updated or created
     * @return mixed
     * @throws CustomerException
     */
    public function updateOrCreateCustomer(string $email,array $data)
    {
        $existingCustomers = $this->getCustomerByEmail($email);
        $count = $existingCustomers->count();
        if ($count > 1) {
            throw new CustomerException("Duplicate Customer Found: {$email}");
        }

        $customer = $this->customerRepository->updateOrCreate(
            ['email' => $email], // search criteria
            $data
        );

        return $customer;

    }
}

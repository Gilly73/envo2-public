<?php

namespace App\DTO;

class CustomerDTO
{
    public function __construct (
        public string $first_name,
        public string $last_name,
        public string $date_of_birth,
        public string $email,
        public string $mobile,
        public string $address_1,
        public ?string $address_2,
        public string $city,
        public string $county,
        public string $postcode,
        public string $country,
    ) {
        $this->email = strtolower($this->email);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            first_name: $data['firstName'],
            last_name:  $data['surname'],
            date_of_birth: $data['dob'],
            email:      $data['email'],
            mobile:     $data['mobile'],
            address_1:  $data['address1'],
            address_2:  $data['address2'] ?? null,
            city:       $data['city'],
            county:     $data['county'],
            postcode:   $data['postcode'],
            country:    $data['country']
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name'   => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'email'     => $this->email,
            'mobile'    => $this->mobile,
            'address_1'  => $this->address_1,
            'address_2'  => $this->address_2,
            'city'      => $this->city,
            'county'    => $this->county,
            'postcode'  => $this->postcode,
            'country'   => $this->country,
        ];
    }

  
}

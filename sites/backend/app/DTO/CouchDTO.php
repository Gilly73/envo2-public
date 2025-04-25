<?php

namespace App\DTO;

class CouchDTO
{
    public function __construct(
        public int $couchtype,
        public int $styletype,
        public int $fabrictype,
        public int $legtype,
        public int $seatertype,
        public ?string $discount = null,
        public string $country
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            couchtype: $data['couchtype'],
            styletype: $data['styletype'],
            fabrictype: $data['fabrictype'],
            legtype: $data['legtype'],
            seatertype: $data['seatertype'],
            discount: $data['discount'] ?? null,
            country: $data['country']
        );
    }

    public function toArray(): array
    {
        return [
            'couchtype' => $this->couchtype,
            'styletype' => $this->styletype,
            'fabrictype' => $this->fabrictype,
            'legtype'   => $this->legtype,
            'seatertype' => $this->seatertype,
            'discount'  => $this->discount,
            'country'   => $this->country,
        ];
    }
}

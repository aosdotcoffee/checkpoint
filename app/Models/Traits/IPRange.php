<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Services\IPTools;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\Builder;
use IPLib\Factory as IPLib;

trait IPRange
{
    private function getRange()
    {
        $start = IPTools::byteStringToRangeByteArray($this->range_start);
        $end = IPTools::byteStringToRangeByteArray($this->range_end);

        return IPLib::getRangeFromBoundaries(
            from: IPLib::addressFromBytes($start),
            to: IPLib::addressFromBytes($end),
        );
    }

    private function getCidr(): string
    {
        return $this->getRange()->toString();
    }

    private function setCidr(string $cidr)
    {
        $range = IPTools::getCidrBoundaries($cidr);

        return [
            'range_start' => $range[0],
            'range_end' => $range[1],
        ];
    }

    protected function cidr(): Attribute
    {
        return Attribute::make(
            get: $this->getCidr(...),
            set: $this->setCidr(...),
        );
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        unset($attributes['range_start']);
        unset($attributes['range_end']);
        $attributes['cidr'] = $this->getCidr();

        return $attributes;
    }

    #[Scope]
    protected function ofAddress(Builder|EloquentBuilder $query, string $address)
    {
        $address = IPLib::parseAddressString($address);
        if ($address === null) {
            throw new \Exception('Invalid address');
        }

        $byteString = IPTools::rangeByteArrayToByteString($address->getBytes());

        return $query
            ->where('range_start', '<=', $byteString)
            ->where('range_end', '>=', $byteString);
    }

    public function matchesAddress(string $address)
    {
        $range = $this->getRange();
        return $range->contains(IPLib::parseAddressString($address));
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use IPLib\Factory as IPLib;
use IPLib\Address;

final class IPTools
{
    public static function byteStringToRangeByteArray(string $byteString): array
    {
        $mappedIPv4Prefix = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xFF\xFF";

        if (substr($byteString, offset: 0, length: 12) === $mappedIPv4Prefix) {
            $byteString = substr($byteString, offset: 12, length: 4);
        }

        return array_map(
            array: str_split($byteString),
            callback: ord(...),
        );
    }

    public static function rangeByteArrayToByteString(array $bytes): string
    {
        /* ipv4 */
        if (count($bytes) === 4) {
            $bytes = [
                0x00, 0x00, 0x00, 0x00,
                0x00, 0x00, 0x00, 0x00,
                0x00, 0x00, 0xFF, 0xFF,
                ...$bytes,
            ];
        } elseif (count($bytes) !== 16) {
            throw new \Exception('Unexpected array size');
        }

        $byteString = '';
        foreach ($bytes as $byte) {
            $byteString .= chr($byte);
        }

        return $byteString;
    }

    public static function getCidrBoundaries(string $cidr)
    {
        $range = IPLib::parseRangeString($cidr);

        $rangeStart = $range->getAddressAtOffset(0);
        $rangeEnd = $range->getAddressAtOffset(-1);

        return [
            static::rangeByteArrayToByteString($rangeStart->getBytes()),
            static::rangeByteArrayToByteString($rangeEnd->getBytes()),
        ];
    }
}

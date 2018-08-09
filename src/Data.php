<?php

/**
 * Data.php
 */

namespace Bmatovu\Conversion;

/**
 * Class Data
 *
 * Working with primitive data types in PHP
 *
 * @author  Brian Matovu <mtvbrianking@gmail.com>
 *
 * @package Bmatovu\Conversion
 *
 * @link http://php.net/manual/en/language.types.integer.php PHP Integer Data Type
 * @link http://php.net/manual/en/function.pack.php PHP Pack()
 * @link http://php.net/manual/en/function.unpack.php PHP Unpack()
 * @link http://www.binaryhexconverter.com/binary-to-decimal-converter Bin2Dec Converter
 * @link http://www.binaryhexconverter.com/decimal-to-hex-converter Dec2Hex Converter
 * @link http://www.binaryhexconverter.com/hex-to-decimal-converter Hex2Dec Converter
 * @link http://www.exploringbinary.com/twos-complement-converter/ 2's Complement Converter
 * @link http://php.net/manual/en/function.bindec.php#97102 2's Complement Conversion Eg. #1
 * @link https://stackoverflow.com/a/16127799/2732184 2's Complement Conversion Eg. #2
 * @link https://betterexplained.com/articles/understanding-big-and-little-endian-byte-order/ Machine Byte Order (Endianness)
 * @link https://www.scadacore.com/tools/programming-calculators/online-hex-converter/ Endianness Aware Converter
 *
 */
class Data
{

    // Signedness
    const SIGNED = 1;
    const UNSIGNED = 0;

    // Endianness
    const BIG_ENDIAN = 1;
    const MID_BIG_ENDIAN = 2;
    const LITTLE_ENDIAN = 3;
    const MID_LITTLE_ENDIAN = 4;

    /**
     * Convert BYTE to INT8
     * @param string $byte BYTE
     * @param bool|true $signedness Integer signedness, default true
     * @var int Signedness => 1 (Signed)
     * @var int Signedness => 0 (Unsigned)
     * @see Data::int8ToByte() Converting INT8 to BYTE
     * @return int INT16
     */
    public static function byteToInt8($byte, $signedness = 1)
    {
        # Pack byte into raw
        $raw = pack("H*", $byte);

        # Unpack raw to integer
        $int8 = unpack("C", $raw)[1];

        # Determine 2's complement
        if ($signedness && $int8 >= 0x80) {
            if (0x80 & $int8)
                return -(($int8 ^ 0xFF) + 1);
        }

        return $int8;
    }

    /**
     * Convert INT8 to BYTE
     * @param int $int8 INT8 Range: (-127 to 255)
     * @see Data::byteToInt8() Converting BYTE to INT8
     * @return bool|string BYTE or false if integer provided is out of range
     */
    public static function int8ToByte($int8)
    {
        if ($int8 < -128 || $int8 > 255) {
            return false;
        }

        # int to integer (dec)
        $int8 = (int)$int8 & 0xFF;

        # Convert Integer (dec) to Hex
        return base_convert($int8, 10, 16);
    }

    /**
     * Convert WORD to INT16
     * @param string $word WORD (2 bytes)
     * @param int $endianness Machine Byte Order, default 1
     * @var int Endianness => 1 (Big Endian)
     * @var int Endianness => 2 (Mid-Big Endian) *Not supported
     * @var int Endianness => 3 (Little Endian)
     * @var int Endianness => 4 (Mid-Little Endian) *Not supported
     * @param bool|true $signed Integer signedness, default true
     * @var int $signedness => 1 (Signed)
     * @var int $signedness => 0 (Unsigned)
     * @see Data::int16ToWord() Converting INT16 to WORD
     * @return bool|int INT16 or false if invalid, or non supported endianness is given
     */
    public static function wordToInt16($word, $endianness = 1, $signedness = 1)
    {

        # unpack hex str to raw bytes
        $bin = pack("H*", $word);
        $raw = unpack("c*", $bin);

        $msb = $lsb = 0;
        if ($endianness == 1) {
            $msb = ($raw[1] << 8) & 0xFF00;
            $lsb = ($raw[2] << 0) & 0x00FF;
        } elseif ($endianness == 3) {
            $msb = ($raw[2] << 8) & 0xFF00;
            $lsb = ($raw[1] << 0) & 0x00FF;
        } else {
            return false;
        }

        $int16 = $msb + $lsb;
        if ($signedness && $int16 & 0x8000) {
            return -(($int16 ^ 0xFFFF) + 1);
        }

        return $int16;
    }

    /**
     * Convert INT16 to WORD (2 bytes)
     * @param int $int16 INT16 Range: (-32768 to 65535)
     * @param int $endianness Machine Byte Order, default 1
     * @var int Endianness => 1 (Big Endian)
     * @var int Endianness => 2 (Mid-Big Endian) *Not supported
     * @var int Endianness => 3 (Little Endian)
     * @var int Endianness => 4 (Mid-Little Endian) *Not supported
     * @see Data::dwordToInt32() Converting WORD to INT16
     * @return bool|string WORD or false if invalid, or non supported endianness is given
     */
    public static function int16ToWord($int16, $endianness = 1)
    {
        # Unpack INT16 to bytes (INT8)
        $msb = ($int16 >> 8) & 0xFF;
        $lsb = ($int16 >> 0) & 0xFF;

        # Convert bytes (INT8) to hex
        $msb = str_pad(base_convert($msb, 10, 16), 2, '0', STR_PAD_LEFT);
        $lsb = str_pad(base_convert($lsb, 10, 16), 2, '0', STR_PAD_LEFT);

        # Pack bytes into word according endianness
        if ($endianness == 1) {
            return $msb . $lsb;
        } elseif ($endianness == 3) {
            return $lsb . $msb;
        }

        return false;
    }

    /**
     * Convert DWORD to INT32
     * @param string $dword DWORD (4 bytes)
     * @param int $endianness Machine Byte Order, default 1
     * @var int Endianness => 1 (Big Endian)
     * @var int Endianness => 2 (Mid-Big Endian)
     * @var int Endianness => 3 (Little Endian)
     * @var int Endianness => 4 (Mid-Little Endian)
     * @param bool|true $signedness Integer signedness, default true
     * @var bool Signedness => True (Signed)
     * @var bool Signedness => False (Unsigned)
     * @see Data::int32ToDword() Converting INT32 to DWORD
     * @return int INT32
     */
    public static function dwordToInt32($dword, $endianness = 1, $signedness = 1)
    {
        print('endianness: '.$endianness);

        # unpack hex str to raw bytes
        $bin = pack("H*", $dword);
        $raw = unpack("c*", $bin);

        $msb1 = $lsb1 = $msb2 = $lsb2 = 0;

        # Pack raw bytes according to endianness
        if ($endianness == 1) {
            $msb1 = ($raw[1] << 24) & 0xFF000000;
            $lsb1 = ($raw[2] << 16) & 0x00FF0000;
            $msb2 = ($raw[3] << 8) & 0x0000FF00;
            $lsb2 = ($raw[4] << 0) & 0x000000FF;
        } elseif ($endianness == 2) {
            $msb1 = ($raw[2] << 24) & 0xFF000000;
            $lsb1 = ($raw[1] << 16) & 0x00FF0000;
            $msb2 = ($raw[4] << 8) & 0x0000FF00;
            $lsb2 = ($raw[3] << 0) & 0x000000FF;
        } elseif ($endianness == 3) {
            $msb1 = ($raw[4] << 24) & 0xFF000000;
            $lsb1 = ($raw[3] << 16) & 0x00FF0000;
            $msb2 = ($raw[2] << 8) & 0x0000FF00;
            $lsb2 = ($raw[1] << 0) & 0x000000FF;
        } elseif ($endianness == 4) {
            $msb1 = ($raw[3] << 24) & 0xFF000000;
            $lsb1 = ($raw[4] << 16) & 0x00FF0000;
            $msb2 = ($raw[1] << 8) & 0x0000FF00;
            $lsb2 = ($raw[2] << 0) & 0x000000FF;
        }

        $int32 = $msb1 + $lsb1 + $msb2 + $lsb2;
        if ($signedness && $int32 & 0x80000000) {
            return -(($int32 ^ 0xFFFFFFFF) + 1);
        }

        return $int32;
    }

    /**
     * Convert INT32 to DWORD (4 bytes)
     * @param int $int32 INT32 Range: (-2147483648 to 4294967295)
     * @param int $endianness Machine Byte Order, default 1
     * @var int Endianness => 1 (Big Endian)
     * @var int Endianness => 2 (Mid-Big Endian)
     * @var int Endianness => 3 (Little Endian)
     * @var int Endianness => 4 (Mid-Little Endian)
     * @see Data::dwordToInt32() Converting DWORD to INT32
     * @return bool|string DWORD or false if invalid endianness is given
     */
    public static function int32ToDword($int32, $endianness = 1)
    {
        # Unpack INT32 to bytes (INT8)
        $msb1 = ($int32 >> 24) & 0xFF;
        $lsb1 = ($int32 >> 16) & 0xFF;
        $msb2 = ($int32 >> 8) & 0xFF;
        $lsb2 = ($int32 >> 0) & 0xFF;

        # Convert bytes (INT8) to hex
        $msb1 = str_pad(base_convert($msb1, 10, 16), 2, '0', STR_PAD_LEFT);
        $lsb1 = str_pad(base_convert($lsb1, 10, 16), 2, '0', STR_PAD_LEFT);
        $msb2 = str_pad(base_convert($msb2, 10, 16), 2, '0', STR_PAD_LEFT);
        $lsb2 = str_pad(base_convert($lsb2, 10, 16), 2, '0', STR_PAD_LEFT);

        # Pack bytes into dword according endianness
        if ($endianness == 1) {
            return $msb1 . $lsb1 . $msb2 . $lsb2;
        } elseif ($endianness == 2) {
            return $lsb1 . $msb1 . $lsb2 . $msb2;
        } elseif ($endianness == 3) {
            return $lsb2 . $msb2 . $lsb1 . $msb1;
        } elseif ($endianness == 4) {
            return $msb2 . $lsb2 . $msb1 . $lsb1;
        }

        return false;
    }

}
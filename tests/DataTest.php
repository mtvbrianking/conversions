<?php

namespace Bmatovu\Conversion\Test;

use Bmatovu\Conversion\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    /**
     * @group uint8
     */
    public function testUnsignedByteToInt8()
    {
        $int8 = Data::byteToInt8('00', Data::UNSIGNED);
        $this->assertEquals(0, $int8);

        $int8 = Data::byteToInt8('FF', Data::UNSIGNED);
        $this->assertEquals(255, $int8);
    }

    /**
     * @group int8
     */
    public function testSignedByteToInt8()
    {
        $int8 = Data::byteToInt8('80', Data::SIGNED);
        $this->assertEquals(-128, $int8);

        $int8 = Data::byteToInt8('7F', Data::SIGNED);
        $this->assertEquals(127, $int8);
    }

    /**
     * @group byte
     */
    public function testInt8ToByte()
    {
        $byte = Data::int8ToByte(-128);
        $this->assertEquals('80', $byte);

        $byte = Data::int8ToByte(255);
        $this->assertEquals('ff', $byte);
    }

    /**
     * @group uint16
     */
    public function testBigEndianUnsignedWordToInt16()
    {
        $int16 = Data::wordToInt16('0000', Data::BIG_ENDIAN, Data::UNSIGNED);
        $this->assertEquals(0, $int16);

        $int16 = Data::wordToInt16('FFFF', Data::BIG_ENDIAN, Data::UNSIGNED);
        $this->assertEquals(65535, $int16);
    }

    /**
     * @group int16
     */
    public function testBigEndianSignedWordToInt16()
    {
        $int16 = Data::wordToInt16('8000', Data::BIG_ENDIAN, Data::SIGNED);
        $this->assertEquals(-32768, $int16);

        $int16 = Data::wordToInt16('FFFF', Data::BIG_ENDIAN, Data::SIGNED);
        $this->assertEquals(-1, $int16);
    }

    /**
     * @group uint16
     */
    public function testLittleEndianUnsignedWordToInt16()
    {
        $int16 = Data::wordToInt16('0000', Data::LITTLE_ENDIAN, Data::UNSIGNED);
        $this->assertEquals(0, $int16);

        $int16 = Data::wordToInt16('FFFF', Data::LITTLE_ENDIAN, Data::UNSIGNED);
        $this->assertEquals(65535, $int16);
    }

    /**
     * @group int16
     */
    public function testLittleEndianSignedWordToInt16()
    {
        $int16 = Data::wordToInt16('8000', Data::LITTLE_ENDIAN, Data::SIGNED);
        $this->assertEquals(128, $int16);

        $int16 = Data::wordToInt16('FFFF', Data::LITTLE_ENDIAN, Data::SIGNED);
        $this->assertEquals(-1, $int16);
    }

    /**
     * @group word
     */
    public function testInt16ToWord()
    {
        $word = Data::int16ToWord(-32768);
        $this->assertEquals('8000', $word);

        $word = Data::int16ToWord(65535);
        $this->assertEquals('ffff', $word);
    }

    /**
     * @group uint32
     */
    public function testBigEndianUnsignedDwordToInt32()
    {
        $int32 = Data::dwordToInt32('00000000', Data::BIG_ENDIAN, Data::UNSIGNED);
        $this->assertEquals(0, $int32);

//        $int32 = Data::dwordToInt32('FFFFFFFF', Data::BIG_ENDIAN, Data::UNSIGNED);
//        $this->assertEquals(4294967295, $int32);
    }

    /**
     * @group int32
     */
    public function testBigEndiansignedDwordToInt32()
    {
        $int32 = Data::dwordToInt32('00000000', Data::BIG_ENDIAN, Data::SIGNED);
        $this->assertEquals(0, $int32);

        $int32 = Data::dwordToInt32('FFFFFFFF', Data::BIG_ENDIAN, Data::SIGNED);
        $this->assertEquals(-1, $int32);
    }

    /**
     * @group dword
     */
    public function testInt32ToDword()
    {
        $dword = Data::int32ToDword(-2147483648);
        $this->assertEquals('80000000', $dword);

        $dword = Data::int32ToDword(4294967295);
        $this->assertEquals('ffffffff', $dword);
    }
}

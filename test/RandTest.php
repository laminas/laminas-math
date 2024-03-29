<?php

declare(strict_types=1);

namespace LaminasTest\Math;

use Laminas\Math\Exception\DomainException;
use Laminas\Math\Exception\InvalidArgumentException;
use Laminas\Math\Rand;
use PHPUnit\Framework\TestCase;

use function mb_strlen;
use function preg_match;
use function sprintf;
use function str_repeat;
use function strlen;

use const PHP_INT_MAX;

/**
 * @see       https://github.com/laminas/laminas-math for the canonical source repository
 */
class RandTest extends TestCase
{
    /** @var bool */
    public static $customRandomBytes = false;

    public function tearDown(): void
    {
        self::$customRandomBytes = false;
    }

    /**
     * @return array[]
     */
    public static function provideRandInt()
    {
        return [
            [2, 1, 10000, 100, 0.9, 1.1],
            [2, 1, 10000, 100, 0.8, 1.2],
        ];
    }

    public function testRandBytes(): void
    {
        for ($length = 1; $length < 4096; $length++) {
            $rand = Rand::getBytes($length);
            $this->assertNotFalse($rand);
            $this->assertEquals($length, mb_strlen($rand, '8bit'));
        }
    }

    public function testWrongRandBytesParam(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid parameter provided to getBytes(length)');
        Rand::getBytes('foo');
    }

    public function testZeroRandBytesParam(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The length must be a positive number in getBytes(length)');
        Rand::getBytes(0);
    }

    public function testNegativeRandBytesParam(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The length must be a positive number in getBytes(length)');
        Rand::getBytes(-1);
    }

    public function testRandBoolean(): void
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getBoolean();
            $this->assertIsBool($rand);
        }
    }

    /**
     * @param float|int $min
     * @param float|int $max
     * @param float|int $cycles
     * @dataProvider dataProviderForTestRandIntegerRangeTest
     */
    public function testRandIntegerRangeTest($min, $max, $cycles): void
    {
        $counter = [];
        for ($i = $min; $i <= $max; $i++) {
            $counter[$i] = 0;
        }

        for ($j = 0; $j < $cycles; $j++) {
            $value = Rand::getInteger($min, $max);
            $this->assertIsInt($value);
            $this->assertGreaterThanOrEqual($min, $value);
            $this->assertLessThanOrEqual($max, $value);
            $counter[$value]++;
        }

        foreach ($counter as $value => $count) {
            $this->assertGreaterThan(0, $count, sprintf('The bucket for value %d is empty.', $value));
        }
    }

    /**
     * @return array
     */
    public function dataProviderForTestRandIntegerRangeTest()
    {
        return [
            [0, 100, 10000],
            [-100, 100, 10000],
            [-100, 50, 10000],
            [0, 63, 10000],
            [0, 64, 10000],
            [0, 65, 10000],
        ];
    }

    /**
     * A Monte Carlo test that generates $cycles numbers from 0 to $tot
     * and test if the numbers are above or below the line y=x with a
     * frequency range of [$min, $max]
     *
     * @param int|float $num
     * @param int|float $valid
     * @param int|float $cycles
     * @param int|float $tot
     * @param int|float $min
     * @param int|float $max
     * @dataProvider provideRandInt
     */
    public function testRandInteger($num, $valid, $cycles, $tot, $min, $max): void
    {
        try {
            $test = Rand::getBytes(1);
        } catch (RuntimeException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $i     = 0;
        $count = 0;
        do {
            $up   = 0;
            $down = 0;
            for ($i = 0; $i < $cycles; $i++) {
                $x = Rand::getInteger(0, $tot);
                $y = Rand::getInteger(0, $tot);
                if ($x > $y) {
                    $up++;
                } elseif ($x < $y) {
                    $down++;
                }
            }
            $this->assertGreaterThan(0, $up);
            $this->assertGreaterThan(0, $down);
            $ratio = $up / $down;
            if ($ratio > $min && $ratio < $max) {
                $count++;
            }
            $i++;
        } while ($i < $num && $count < $valid);

        if ($count < $valid) {
            $this->fail('The random number generator failed the Monte Carlo test');
        }
    }

    public function testWrongFirstParamGetInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid parameters provided to getInteger(min, max)');
        Rand::getInteger('foo', 0);
    }

    public function testWrongSecondParamGetInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid parameters provided to getInteger(min, max)');
        Rand::getInteger(0, 'foo');
    }

    public function testIntegerRangeFail(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The min parameter must be lower than max in getInteger(min, max)');
        Rand::getInteger(100, 0);
    }

    public function testIntegerRangeOverflow(): void
    {
        $values = 0;
        $cycles = 100;
        for ($i = 0; $i < $cycles; $i++) {
            $values += Rand::getInteger(0, PHP_INT_MAX);
        }

        // It's not possible to test $values > 0 because $values may suffer a integer overflow
        $this->assertNotEquals(0, $values);
    }

    public function testRandFloat(): void
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getFloat();
            $this->assertIsFloat($rand);
            $this->assertGreaterThanOrEqual(0, $rand);
            $this->assertLessThanOrEqual(1, $rand);
        }
    }

    public function testGetString(): void
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getString($length, '0123456789abcdef');
            $this->assertEquals(strlen($rand), $length);
            $this->assertEquals(1, preg_match('#^[0-9a-f]+$#', $rand));
        }
    }

    public function testGetStringBase64(): void
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getString($length);
            $this->assertEquals(strlen($rand), $length);
            $this->assertEquals(1, preg_match('#^[0-9a-zA-Z+/]+$#', $rand));
        }
    }

    public function testGetNegativeSizeStringExpectException(): void
    {
        $this->expectException(DomainException::class);
        $rand = Rand::getString(-1);
    }

    public function testGetStringWithOneCharacter(): void
    {
        for ($length = 1; $length < 512; $length++) {
            $rand = Rand::getString($length, 'a');
            $this->assertEquals(strlen($rand), $length);
            $this->assertEquals(str_repeat('a', $length), $rand);
        }
    }
}

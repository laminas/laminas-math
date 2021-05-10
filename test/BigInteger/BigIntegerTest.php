<?php

namespace LaminasTest\Math\BigInteger;

use Laminas\Math\BigInteger\Adapter\AdapterInterface;
use Laminas\Math\BigInteger\Adapter\Bcmath;
use Laminas\Math\BigInteger\BigInteger as BigInt;
use PHPUnit\Framework\TestCase;

class BigIntegerTest extends TestCase
{
    public function testFactoryCreatesBcmathAdapter()
    {
        if (! extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing bcmath extensions');
        }

        $bigInt = BigInt::factory('Bcmath');
        $this->assertInstanceOf('Laminas\Math\BigInteger\Adapter\Bcmath', $bigInt);
    }

    public function testFactoryCreatesGmpAdapter()
    {
        if (! extension_loaded('gmp')) {
            $this->markTestSkipped('Missing gmp extensions');
        }

        $bigInt = BigInt::factory('Gmp');
        $this->assertInstanceOf('Laminas\Math\BigInteger\Adapter\Gmp', $bigInt);
    }

    public function testFactoryUsesDefaultAdapter()
    {
        if (! extension_loaded('bcmath') && ! extension_loaded('gmp')) {
            $this->markTestSkipped('Missing bcmath or gmp extensions');
        }
        $this->assertInstanceOf('Laminas\Math\BigInteger\Adapter\AdapterInterface', BigInt::factory());
    }

    public function testFactoryUnknownAdapterRaisesException()
    {
        $this->expectException('Laminas\Math\Exception\ExceptionInterface');
        BigInt::factory('unknown');
    }

    public function testSetDefaultAdapter()
    {
        if (! extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing bcmath extensions');
        }

        BigInt::setDefaultAdapter('bcmath');
        $this->assertInstanceOf(AdapterInterface::class, BigInt::getDefaultAdapter());
        $this->assertInstanceOf(Bcmath::class, BigInt::getDefaultAdapter());
    }

    /**
     * @covers Laminas\Math\BigInteger\BigInteger::__callStatic
     */
    public function testCallStatic()
    {
        if (! extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing bcmath extensions');
        }
        BigInt::setDefaultAdapter('bcmath');
        $result = BigInt::add(1, 2);
        $this->assertEquals(3, $result);
    }
}

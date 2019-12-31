<?php

/**
 * @see       https://github.com/laminas/laminas-math for the canonical source repository
 * @copyright https://github.com/laminas/laminas-math/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-math/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Math\BigInteger;

use Laminas\Math\BigInteger\BigInteger as BigInt;

/**
 * @group      Laminas_Math
 */
class BigIntegerTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryCreatesBcmathAdapter()
    {
        if (!extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing bcmath extensions');
        }

        $bigInt = BigInt::factory('Bcmath');
        $this->assertInstanceOf('Laminas\Math\BigInteger\Adapter\Bcmath', $bigInt);
    }

    public function testFactoryCreatesGmpAdapter()
    {
        if (!extension_loaded('gmp')) {
            $this->markTestSkipped('Missing gmp extensions');
        }

        $bigInt = BigInt::factory('Gmp');
        $this->assertInstanceOf('Laminas\Math\BigInteger\Adapter\Gmp', $bigInt);
    }

    public function testFactoryUsesDefaultAdapter()
    {
        if (!extension_loaded('bcmath') && !extension_loaded('gmp')) {
            $this->markTestSkipped('Missing bcmath or gmp extensions');
        }
        $this->assertInstanceOf('Laminas\Math\BigInteger\Adapter\AdapterInterface', BigInt::factory());
    }

    public function testFactoryUnknownAdapterRaisesServiceManagerException()
    {
        $this->setExpectedException('Laminas\ServiceManager\Exception\ExceptionInterface');
        BigInt::factory('unknown');
    }
}

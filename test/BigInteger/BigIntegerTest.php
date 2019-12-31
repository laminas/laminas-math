<?php

/**
 * @see       https://github.com/laminas/laminas-math for the canonical source repository
 * @copyright https://github.com/laminas/laminas-math/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-math/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Math\BigInteger;

use Laminas\Math\BigInteger\Adapter;
use Laminas\Math\BigInteger\Adapter\AdapterInterface;
use Laminas\Math\BigInteger\BigInteger as BigInt;

/**
 * @category   Laminas
 * @package    Laminas_Math_BigInteger
 * @subpackage UnitTests
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
        $this->assertTrue($bigInt instanceof Adapter\Bcmath);
    }

    public function testFactoryCreatesGmpAdapter()
    {
        if (!extension_loaded('gmp')) {
            $this->markTestSkipped('Missing gmp extensions');
        }

        $bigInt = BigInt::factory('Gmp');
        $this->assertTrue($bigInt instanceof Adapter\Gmp);
    }

    public function testFactoryUsesDefaultAdapter()
    {
        if (!extension_loaded('bcmath') && !extension_loaded('gmp')) {
            $this->markTestSkipped('Missing bcmath or gmp extensions');
        }
        $this->assertTrue(BigInt::factory() instanceof AdapterInterface);
    }

    public function testFactoryUnknownAdapterRaisesServiceManagerException()
    {
        $this->setExpectedException('Laminas\ServiceManager\Exception\ExceptionInterface');
        BigInt::factory('unknown');
    }
}
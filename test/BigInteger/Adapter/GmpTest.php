<?php

/**
 * @see       https://github.com/laminas/laminas-math for the canonical source repository
 * @copyright https://github.com/laminas/laminas-math/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-math/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Math\BigInteger\Adapter;

use Laminas\Math\BigInteger\Adapter\Gmp;

/**
 * @group      Laminas_Crypt
 */
class GmpTest extends AbstractTestCase
{
    public function setUp()
    {
        if (! extension_loaded('gmp')) {
            $this->markTestSkipped('Missing ext/gmp');
            return;
        }

        $this->adapter = new Gmp();
    }

    /**
     * Gmp adapter test uses common test methods and data providers
     * inherited from abstract @see AbstractTestCase
     */
}

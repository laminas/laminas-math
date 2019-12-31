<?php

/**
 * @see       https://github.com/laminas/laminas-math for the canonical source repository
 * @copyright https://github.com/laminas/laminas-math/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-math/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Math\BigInteger\Adapter;

use Laminas\Math\BigInteger\Adapter\Bcmath;

/**
 * @group      Laminas_Crypt
 */
class BcmathTest extends AbstractTestCase
{
    public function setUp()
    {
        if (! extension_loaded('bcmath')) {
            $this->markTestSkipped('Missing ext/bcmath');
            return;
        }

        $this->adapter = new Bcmath();
    }

    /**
     * Bcmath adapter test uses common test methods and data providers
     * inherited from abstract @see AbstractTestCase
     */
}

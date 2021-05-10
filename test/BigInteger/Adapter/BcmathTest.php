<?php

namespace LaminasTest\Math\BigInteger\Adapter;

use Laminas\Math\BigInteger\Adapter\Bcmath;

class BcmathTest extends AbstractTestCase
{
    public function setUp(): void
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

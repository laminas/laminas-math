<?php

declare(strict_types=1);

namespace LaminasTest\Math\BigInteger\Adapter;

use Laminas\Math\BigInteger\Adapter\Gmp;

use function extension_loaded;

class GmpTest extends AbstractTestCase
{
    public function setUp(): void
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

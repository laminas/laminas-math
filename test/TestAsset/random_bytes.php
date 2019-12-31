<?php

/**
 * @see       https://github.com/laminas/laminas-math for the canonical source repository
 * @copyright https://github.com/laminas/laminas-math/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-math/blob/master/LICENSE.md New BSD License
 */
namespace Laminas\Math;

use Exception;
use LaminasTest\Math\RandTest;

/**
 * Generate random bytes with $length size or throw an Exception,
 * to test a PHP platform without secure random number generator installed
 *
 * @param int $length
 * @return string
 */
function random_bytes($length)
{
    if (RandTest::$customRandomBytes) {
        throw new Exception("Random is not supported");
    }
    return \random_bytes($length);
}

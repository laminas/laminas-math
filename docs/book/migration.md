# Migration Guide

The following details the changes from the version 2 series to version 3, and
how to prepare for migration.

## New requirements for random number generation

For version 3, we changed the random number generator strategy of
`Laminas\Math\Rand` using the `random_int()` ad `random_bytes()` functions of PHP
7. For users still on PHP 5.5+, we now require the
[random_compact](https://github.com/paragonie/random_compat) library, which
provides a polyfill for these new PHP 7 functions.

## ext/mbstring required

Starting with version 3, we now require
the [mbstring](http://php.net/mbstring)
extension. We added this requirement to ensure that all
string manipulations inside laminas-math are binary-safe.

Internally, we replace all `strlen()` and `substr()` functions with the
equivalent `mb_strlen()` and `mb_substr()` functions, and require `8bit`
encoding.

## We removed the $strong optional parameter

In `Laminas\Math\Rand`, we removed the usage of the `$strong` optional parameter for
the random number generator. By default, all random numbers produced in version
3 releases will use a secure pseudo-random number generator
([CSPRNG](https://en.wikipedia.org/wiki/Cryptographically_secure_pseudorandom_number_generator)).

The following lists the functions from which the parameter was removed:

- `Rand::getBytes($length)`
- `Rand::getBoolean()`
- `Rand::getInteger($min, $max)`
- `Rand::getFloat()`
- `Rand::getString($length, $charlist = null)`

In each case, the `$strong` parameter was both optional, and the last argument
in the list. As PHP allows passing more arguments than the signature accepts,
this will not pose a backwards compatibility break; it only means that the
argument no longer has any meaning.

We recommend removing the parameter from any calls you make to the above
functions after migrating.

## We changed the error management in Rand

Several methods now throw exceptions for error situations:

- `Laminas\Math\Rand::getBytes($length)` will no longer return a boolean `false` when
  `$length <= 0`. Instead, it will now throw a `Laminas\Math\Exception\DomainException`.
- `Laminas\Math\Rand::getBytes($length)` will now throw a
  `Laminas\Math\Exception\InvalidArgumentException` if the `$length` parameter is
  not an integer.
- `Laminas\Math\Rand::getInteger($min, $max)` will now throw a
  `Laminas\Math\Exception\InvalidArgumentException` if either parameter is not an
  integer.

Additionally, in cases where you are not using PHP 7 and your PHP environment
does not provide a secure random source, we now throw a
`Laminas\Math\Exception\RuntimeException` with the following message:

```text
This PHP environment doesn't support secure random number generation.
Please consider upgrading to PHP 7.
```

This message should appear if you are using PHP versions less than 7 on Windows
machines without one of the following extensions or libraries installed:

- [Mcrypt](http://php.net/mcrypt)
- [libsodium](https://pecl.php.net/package/libsodium)
- [CAPICOM](https://en.wikipedia.org/wiki/CAPICOM)
- [OpenSSL](http://php.net/openssl)

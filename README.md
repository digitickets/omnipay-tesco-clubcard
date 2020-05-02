# omnipay-tesco-clubcard

**UK- and Ireland Tesco Clubcard voucher redemptions driver for the Omnipay PHP payment processing library**

Omnipay implementation Tesco Clubcard voucher redemption. Obviously it's not a payment gateway, but it behaves in a similar way.

[![Build Status](https://travis-ci.org/pedanticantic/omnipay-tesco-clubcard.png?branch=master)](https://travis-ci.org/pedanticantic/omnipay-tesco-clubcard)
[![Latest Stable Version](https://poser.pugx.org/pedanticantic/omnipay-tesco-clubcard/version.png)](https://packagist.org/packages/omnipay/tesco-clubcard)
[![Total Downloads](https://poser.pugx.org/pedanticantic/omnipay-tesco-clubcard/d/total.png)](https://packagist.org/packages/pedanticantic/omnipay-tesco-clubcard)

## Installation

**Important: Driver requires [PHP's Intl extension](http://php.net/manual/en/book.intl.php) to be installed.**

The Tesco Clubcard Omnipay driver is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "digitickets/omnipay-tesco-clubcard": "^2.1"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## What's Included

There are drivers for UK (Rewards) vouchers and for the Ireland (Boost) vouchers.

## What's Not Included


## Basic Usage

For both drivers ("UK" and "Ireland") in this repo, there are voucher-type requests, namely validate, redeem and unredeem, and there is a subset of the normal "Omnipay" requests, namely authorize, purchase and refund.

Use the voucher-type requests when you're treating the vouchers as vouchers; use the "Omnipay" requests when you are treating them as payments.

For general Omnipay usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug in this driver, please report it using the [GitHub issue tracker](https://github.com/pedanticantic/omnipay-tesco-clubcard/issues),
or better yet, fork the library and submit a pull request.

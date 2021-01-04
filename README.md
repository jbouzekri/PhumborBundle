PhumborBundle
=============

[![Build Status](https://travis-ci.org/jbouzekri/PhumborBundle.svg?branch=master)](https://travis-ci.org/jbouzekri/PhumborBundle)
[![Code Coverage](https://scrutinizer-ci.com/g/jbouzekri/PhumborBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jbouzekri/PhumborBundle/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jbouzekri/PhumborBundle/badges/quality-score.png?s=a0a8958b6ab291dc6f867b7df49cf55be590c23d)](https://scrutinizer-ci.com/g/jbouzekri/PhumborBundle/)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f3668751-012f-4444-9d48-7833e2a420ec/mini.png)](https://insight.sensiolabs.com/projects/f3668751-012f-4444-9d48-7833e2a420ec)

A bridge for symfony with the [phumbor client from 99designs](https://github.com/99designs/phumbor) to generate thumbor url.

Prerequisites
-------------

Of course, you must have a [thumbor server](https://github.com/thumbor/thumbor/wiki) installed and operationnal.
If not, you can follow the [official installation documentation](https://github.com/thumbor/thumbor/wiki/Installing).

Installation
------------

Add `jbouzekri/phumbor-bundle` as a dependency in `composer.json`.

``` yml
"jbouzekri/phumbor-bundle": "~1.0"
```

Enable the bundle in your AppKernel :

``` php
$bundles = array(
    ...
    new Jb\Bundle\PhumborBundle\JbPhumborBundle()
);
```

In your config.yml, configure at least the url of your thumbor server and the secret :

``` yml
jb_phumbor:
    server:
        url: http://localhost
        secret: 123456789
```

Alternatively, you can also set the environment variables `THUMBOR_URL` and `THUMBOR_SECURITY_KEY` for these two settings, for example from your `.env`
file or from inside your webserver configuration.

Quick use case
--------------

You need to resize the image of your article to fit in a square of 50x50. Define the following transformation in your config.yml :

``` yml
jb_phumbor:
    transformations:
        article_list:
            fit_in: { width: 50, height: 50 }
```

Now you can use it in twig :

``` twig
{{ thumbor(<the absolute url of your image>, 'article_list') }}
```

Documentation
-------------

* [Configuration Reference](Resources/doc/reference.md)
* [Service](Resources/doc/service.md)
* [Twig Helper](Resources/doc/twig_helper.md)

License
-------

MIT - see [LICENSE](LICENSE)

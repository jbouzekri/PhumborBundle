PhumborBundle
=============

[![Tests](https://github.com/jbouzekri/PhumborBundle/actions/workflows/tests.yml/badge.svg)](https://github.com/jbouzekri/PhumborBundle/actions/workflows/tests.yml)

A Symfony Bundle to generate Thumbor image URLs, using the [PHP client from webfactory](https://github.com/webfactory/phumbor).

Prerequisites
-------------

Of course, you must have a [Thumbor server](https://github.com/thumbor/thumbor/wiki) installed and operationnal.
If not, you can follow the [official installation documentation](https://github.com/thumbor/thumbor/wiki/Installing).

Installation
------------

Add `jbouzekri/phumbor-bundle` as a dependency in `composer.json`.

``` yml
"jbouzekri/phumbor-bundle": "^3.0"
```

Enable the bundle in your AppKernel :

``` php
$bundles = array(
    ...
    new Jb\Bundle\PhumborBundle\JbPhumborBundle()
);
```

In your config.yml, configure at least the url of your Thumbor server and the secret :

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

* [Configuration Reference](src/Resources/doc/reference.md)
* [Service](src/Resources/doc/service.md)
* [Twig Helper](src/Resources/doc/twig_helper.md)

License
-------

MIT - see [LICENSE](LICENSE)

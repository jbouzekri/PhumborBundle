PhumborBundle
=============

A bridge for symfony with the [phumbor client from 99designs](https://github.com/99designs/phumbor) to generate thumbor url.

Prerequisites
-------------

Of course, you must have a [thumbor server](https://github.com/thumbor/thumbor/wiki) installed and operationnal.
If not, you can follow the [official installation documentation](https://github.com/thumbor/thumbor/wiki/Installing).

Installation
------------

Add `jbouzekri/phumbor-bundle` as a dependency in [`composer.json`][composer].

``` yml
"jbouzekri/phumbor-bundle": "dev-master"
```

In your config.yml, configure at least the url of your thumbor server and the secret :

``` yml
jb_phumbor:
    server:
        url: http://localhost
        secret: 123456789
```

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

License
-------

MIT; see [LICENSE](LICENSE)
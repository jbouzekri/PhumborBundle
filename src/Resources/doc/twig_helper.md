Twig Helper
===========

thumbor Function
----------------

The thumbor twig function build a thumbor url with the different transformation configured in config.yml.

``` yml
jb_phumbor:
    transformations:
        width_50:
            resize: { width: 50, height: 0 }
```

``` twig
{{ thumbor('test/logo.png', 'width_50') }}
```

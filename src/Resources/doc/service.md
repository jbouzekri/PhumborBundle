Service
=======

phumbor.url.builder_factory
---------------------------

This service is an instance of \Thumbor\Url\BuilderFactory. It allows its user to access an url builder by calling the url method.

``` php
$thumborUrl = $this
    ->container
    ->get('phumbor.url.builder_factory')
    ->url('test/logo.png')
    ->resize(50, 50);
```

phumbor.url.transformer
-----------------------

This service wrapped the BuilderFactory to build a thumbor url where we applied a transformation from the config.yml file.

``` yml
jb_phumbor:
    transformations:
        width_50:
            resize: { width: 50, height: 0 }
```

``` php
$thumborUrl = $this
    ->container
    ->get('phumbor.url.transformer')
    ->transform('test/logo.png', 'width_50');
```

Is the same thing as

``` php
$thumborUrl = $this
    ->container
    ->get('phumbor.url.transformer')
    ->url('test/logo.png')
    ->resize(50, 0);
```

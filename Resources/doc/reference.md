Configuration Reference
=======================

General
-------

Overview general of all configuration.

``` yml
jb_phumbor:
    server:
        url: http://localhost:80
        secret: ""
    transformations:
        transformation_name:
            trim: true
            crop:
                top_left_x: 5
                top_left_y: 5
                bottom_right_x: 50
                bottom_right_y: 50
            fit_in:
                width: 50
                height: 50
            resize:
                width: 50
                height: 50
            halign: center
            valign: middle
            smart_crop: true
            filters:
                - { name: "brightness", arguments: 52 }
```

server.url
----------

Configure the url of your thumbor server

server.secret
-------------

The secret shared with your thumbor server

transformations.&lt;transformation_name&gt;.trim
------------------------------------------------

Trim surrounding space from the thumbnail. The top-left corner of the image is assumed to contain the background colour.
To specify otherwise, pass either 'top-left' or 'bottom-right'.

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            trim: true
```

transformations.&lt;transformation_name&gt;.crop
------------------------------------------------

Manually specify crop window

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            crop:
                top_left_x: 5
                top_left_y: 5
                bottom_right_x: 50
                bottom_right_y: 50
```

transformations.&lt;transformation_name&gt;.fit_in
--------------------------------------------------

Resize the image to fit in a box of the specified dimensions.

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            fit_in:
                width: 50
                height: 50
```

Use either fit_in or resize but not both

transformations.&lt;transformation_name&gt;.resize
--------------------------------------------------

Resize the image to the specified dimensions. Overrides any previous call to `fitIn` or `resize`.
Use a value of 0 for proportional resizing.

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            resize:
                width: 50
                height: 50
```

Use either fit_in or resize but not both

transformations.&lt;transformation_name&gt;.halign
--------------------------------------------------

Specify horizontal alignment used if width is altered due to cropping. Choose on of the following value : 'left', 'center', 'right'

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            halign: center
```

transformations.&lt;transformation_name&gt;.valign
--------------------------------------------------

Specify horizontal alignment used if width is altered due to cropping. Choose on of the following value : 'left', 'center', 'right'
Specify vertical alignment used if height is altered due to cropping. Choose on of the following value : 'top', 'middle', 'bottom'

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            valign: middle
```

transformations.&lt;transformation_name&gt;.smart_crop
------------------------------------------------------

Specify that smart cropping should be used (overrides halign/valign).

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            smart_crop: true
```

transformations.&lt;transformation_name&gt;.filters
---------------------------------------------------

Append one or many filters.
The filter must be defined with an array with key name and arguments.
arguments can be an array if the filter needs it

``` yml
jb_phumbor:
    transformations:
        transformation_name:
            filters:
                - { name: "brightness", arguments: 52 }
```
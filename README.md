api-mocker
==========
Laravel package to mock API endpoints.

Usage:
------
1) `composer require baopham/api-mocker`

2) Register Service Provider in `config/app.php`:

```php
    ...
    BaoPham\ApiMocker\ApiMockerServiceProvider::class,
    ...
```

3) `php artisan vendor:publish`

4) Update the new config file: `config/apimocker.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------------------------------
    | Mock on/off
    |--------------------------------------------------------------------------------------------------
    |
    | Determine if the endpoints below should be mocked.
    |
    */

    'should_mock' => env('API_MOCKER_ON', false),

    /*
    |--------------------------------------------------------------------------------------------------
    | Endpoints
    |--------------------------------------------------------------------------------------------------
    |
    | Array of endpoints to be mocked, keyed by the route's path.
    |
    | - fixture: path to the fixture file for the response content, could be JSON or XML.
    |
    | - middleware: an array of middlewares to be applied for this endpoint.
    |
    | - methods: GET, POST, PUT, DELETE. If not defined, it will not check against the method.
    |
    | - code: the response code should be returned. Default to 200.
    |
    | - placeholder: boolean (false by default). Set to `true` if you want to replace the
    |   placeholders in your fixture with the request parameters.
    |
    |     e.g. In your fixture: { "status": "ok", "description": "Folder {{name}} has been updated" }
    |
    |          Request: POST api/v1/folders/123?placeholder_name=BP (prefix with `placeholder_`)
    |
    |          Response will be: { "status": "ok", "description": "Folder BP has been updated" }
    |
    |   This is useful for Behat test for example, when you want to ensure you see the right status message.
    |
    |
    */
    'endpoints' => [

        'api/v1/folders/{id}' => [
            'fixture' => '/path/to/fixture.json',
            'middleware' => ['auth'],
            'methods' => ['POST'],
            'code' => 200,
            'placeholder' => true,
        ],
    ]

];
```


Requirements:
-------------
Laravel 5.1

TODOs:
------
- [ ] Tests


License:
--------
MIT

Author:
-------
Bao Pham

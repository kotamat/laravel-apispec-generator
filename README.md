# Laravel API Spec Generator

API Spec generator with Laravel test

This Package overrides json()
When you use those function, API specs are going to be generated.

You can select spec below,

- Rest
- OpenAPI Specification

## Usage

### Output each specs

Just use `ApiSpec\ApiSpecTestCase` as base class for API-based test classes.

```diff
+use ApiSpec\ApiSpecTestCase;

class SomeTestCase extends ApiSpecTestCase
{
```

or use trait `ApiSpec\ApiSpecOutput`

```diff
+use ApiSpec\ApiSpecOutput;

class SomeTestCase extends TestCase
{
    +use ApiSpecOutput;
    //...
}

```

### Aggregate output files

After Output each specs, this command aggregates all specs in one file.
(only supports OAS mode)

```bash
php artisan apispec:aggregate
```

## Configurations

This package provides config file as `apispec.php`

```php
return [
     // Whether to output spec files.
    'isExportSpec' => true,

     // Spec builder class name. You can choose ToOAS or ToHTTP.
    'builder'      => \ApiSpec\Builders\ToOAS::class,
];
```

## Output

### Rest

The output format is recognized on several IDE.

ex)
PHPStorm, IntelliJ IDEA...([2017.3 EAP](https://blog.jetbrains.com/phpstorm/2017/09/phpstorm-2017-3-early-access-program-is-open/)
https://blog.jetbrains.com/phpstorm/2017/09/editor-based-rest-client/

### OAS

The output format is OpenAPI 3.0.0

Restrictions are below

- Security Scheme type supports only JWT
- All request body contents have `required` flag
- Some parameters hard coded.
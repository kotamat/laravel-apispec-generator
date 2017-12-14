# Laravel API Spec Generator

Rest API Spec generator with Laravel test

This Package overrides getJson(), putJson(), postJson(), deleteJson().

When you use those function, RestAPI specs are going to be generated.

## Usage

Just use as base class for API-based test classes.

```diff
+use ApiSpec\ApiSpecTestCase;

class SomeTestCase extends ApiSpecTestCase
{
```

## Output

The output format is recognized on several IDE.

ex)
PHPStorm, IntelliJ IDEA...([2017.3 EAP](https://blog.jetbrains.com/phpstorm/2017/09/phpstorm-2017-3-early-access-program-is-open/)
https://blog.jetbrains.com/phpstorm/2017/09/editor-based-rest-client/


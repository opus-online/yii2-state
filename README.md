[![Build Status](https://scrutinizer-ci.com/g/opus-online/yii2-state/badges/build.png?b=master)](https://scrutinizer-ci.com/g/opus-online/yii2-state/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/opus-online/yii2-state/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/opus-online/yii2-state/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/opus-online/yii2-state/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/opus-online/yii2-state/?branch=master)

Persistent state extension for Yii2 modules and applications
=========

Provides an application component for storing persistent state information
(key-value pairs) in the database.

Installation
------------
Run migrations from your project
```
php yii migrate --interactive=0 --migrationPath=@vendor/opus-online/yii2-state/migrations
```

Add yii2-state as a system component in your common `main.php`
```php
'systemState' => [
    'class' => '\opus\state\MysqlState',
    'defaults' => [ // optional
        'shopAccessEnabled' => true,
        'siteAccessEnabled' => true,
    ]
],

```
Running tests
-------------
Run `composer install` and then in the project root directory
```
./vendor/bin/phpunit
```

TODO
----
* Unit tests
* Proper migration

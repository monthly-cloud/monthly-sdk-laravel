Monthly Cloud PHP SDK
=========================

Laravel SDK used to connect with monthly cloud.

## Installation

``` bash
composer require monthly-cloud/monthly-sdk-laravel
```

Add Monthly Cloud service provider and alias to your config/app.php config:

```php
'providers' => [
    ...
    MonthlyCloud\Laravel\MonthlyCloudServiceProvider::class,
],
```

```php
'aliases' => [
    ...
    'MonthlyCloud' => MonthlyCloud\Laravel\MonthlyCloud::class,
    'MonthlyStorage' => MonthlyCloud\Laravel\MonthlyStorage::class,
],
```

### Configuration

To create configuration file run:

``` bash
php artisan vendor:publish --provider="MonthlyCloud\Laravel\MonthlyCloudServiceProvider"
```

Update .env file MONTHLY_CLOUD_ACCESS_TOKEN variable or config/monthlycloud.php access_token with access token generated in cloud settings.

### Usage

Example usage of Monthly Cloud sdk:
```php
$property = MonthlyCloud::endpoint('properties')->find(1); // Single property
$properties = MonthlyCloud::endpoint('properties')->get(); // List of properties
```

Example usage of Monthly Storage sdk:
```php
MonthlyStorage::endpoint('routes')->get(); // get routes using default app language
MonthlyStorage::endpoint('menus')->locale('en')->get(); // get menus in pre-defined language
MonthlyStorage::endpoint('contents')->find(31); // find content
MonthlyStorage::endpoint('contents')->website(3)->id(31)->buildUrl(); // build file url for specific website and content id
```


### User log-in

Monthly Cloud is compatible with Laravel Authentication.

You can setup authentication by running:

```
php artisan make:auth
php artisan migrate
```

Setup cloud provider in auth.php:
```
    'guards' => [
        'web' => [
            ...
            'provider' => 'cloud',
        ],
    ...
    'providers' => [
        'cloud' => [
            'driver' => 'cloud',
            'model' => App\User::class,
        ],
        ...
    ],
```

Add IsMonthlyCloudUser trait to User model. By default it will store cloud "label" in $user->name and save it in database.

If you need more information in local database check `applyUserData` method in `IsMonthlyCloudUser` trait.

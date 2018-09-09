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
],
```

### Configuration

To create configuration file run:

``` bash
php artisan vendor:publish --provider="MonthlyCloud\Laravel\MonthlyCloudServiceProvider
```

Update .env file MONTHLY_CLOUD_ACCESS_TOKEN variable or config/monthlycloud.php access_token with access token generated in cloud settings.

### Usage

Documentation is still in progress. Example usage:
```php
$property = MonthlyCloud::endpoint('properties')->find(1); // Single property
$properties = MonthlyCloud::endpoint('properties')->get(); // List of properties
],
```
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

In oprder to access cloud API update .env file `MONTHLY_CLOUD_ACCESS_TOKEN` variable or `config/monthlycloud.php` access_token with access token generated in cloud settings.

To use Monthly Storage set `MONTHLY_CLOUD_WEBSITE_ID` and `MONTHLY_CLOUD_LISTING_ID`.

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

MonthlyStorage::getListingItem()->find(100); // find listing item
MonthlyStorage::findContent(1); // quick way to find content
MonthlyStorage::getRoutes('en'); // quick way to access routes
```

Example usage of Translation Service:
```php
/**
 * Get key from translations dictionary.
 *
 * @param string|null $key
 * @return string|array
 */
function __t($key = null)
{
	if (empty($key)) {
		return app(\MonthlyCloud\Laravel\Services\TranslationService::class)->getTranslations();
	}

	return app(\MonthlyCloud\Laravel\Services\TranslationService::class)->translate($key);
}
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

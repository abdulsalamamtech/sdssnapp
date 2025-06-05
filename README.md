<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Simple Terminal Commands

```sh

    php artisan make:model Department -mcrR --api
    php artisan make:resource DepartmentResource
    php artisan make:model WarehouseImage -m
    php artisan migrate:refresh --step=1

    git commit -m"Add certificate request feature"

```

## Extra Columns

```php
    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the creator
    $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the last updater
    $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete(); // User ID or name of the delete
    $table->softDeletes();
    # BLOB, TEXT, GEOMETRY or JSON column 'requirements' can't have a default value
```

## Transactions

```php
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 10, 2);
        $table->enum('status',
            [
                'pending',
                'successful',
                'cancelled',
                'suspended',
                'rejected'
            ])
            ->default('pending');
        $table->string('reference')->unique();
        $table->string('payment_method')->default('online');
        $table->string('payment_provider')->default('paystack');
        $table->json('data')->nullable(); // response data from payment server
        $table->timestamps();
        $table->softDeletes();
    });
```

## Laravel Event and Listener

-   step by step [guide](https://muwangaxyz.medium.com/laravel-events-listeners-and-observers-complete-guide-06196203b2a8)

```sh
    php artisan make:event PodcastProcessed
    php artisan make:listener SendPodcastNotification --event=PodcastProcessed
    php artisan make:mail
    php artisan event:list
```

-   within the boot method of your application's AppServiceProvider:

```php
    use App\Domain\Orders\Events\PodcastProcessed;
    use App\Domain\Orders\Listeners\SendPodcastNotification;
    use Illuminate\Support\Facades\Event;

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            PodcastProcessed::class,
            SendPodcastNotification::class,
        );
    }

```

-   on production optimize or event:cache

```sh
    php artisan event:list
    php artisan event:clear
    php artisan event:cache
```

-   from your controller

```php
    // Send an event to notify the admin about the certification request
    // event(new CertificationRequestedProceedEvent($certificationRequest));
    // dispatch the event to notify the admin about the certification request
    CertificationRequestedProceedEvent::dispatch($certificationRequest);
    OrderShipped::dispatchIf($condition, $order);
    OrderShipped::dispatchUnless($condition, $order);
```

-   you can also set it

```php
    use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
    class OrderShipped implements ShouldDispatchAfterCommit
    {}
```

## Log Viewer

-   log viewer to view log content

```sh
    composer require opcodesio/log-viewer
    php artisan log-viewer:publish
```

## Installation Warning

```sh
Generating optimized autoload files
Class ImageKitSdkCopy located in ./app/Utils/ImageKitSdkCopy.php does not comply with psr-4 autoloading standard (rule: App\ => ./app). Skipping.
Class ImageKitSdk located in ./app/Utils/ImageKitSdk.php does not comply with psr-4 autoloading standard (rule: App\ => ./app). Skipping.
Class App\Utils\Imagekit located in ./app/Utils/ImageKit.php does not comply with psr-4 autoloading standard (rule: App\ => ./app). Skipping.
> Illuminate\Foundation\ComposerScripts::postAutoloadDump
> @php artisan package:discover --ansi

```

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

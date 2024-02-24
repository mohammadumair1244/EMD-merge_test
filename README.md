# EMD 3 (v2.0.0) Laravel 9 && PHP ^8.0.2

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# EMD Available Functions

## For Check is User Premium

This function will be return (0 || 1 || 2)

-  0 for Free User

-  1 for Premium User

-  2 for Register Plan User

-   [EmdWebUserController::EmdIsUserPremium(web: 0, api:0, api_key: null)](#)

-   **[You need User Login for this](#)**

pass (web) if you want check user premium for Web

pass (api) if you want check user premium for API

-   **[No need User Login for this](#)**

(api_key) must pass

pass (web) if you want check user premium for Web

pass (api) if you want check user premium for API

## For Query Detect and Check

This function will be return (bool || array)

-   [EmdWebsiteQueryUse(tool_id : null, query_no : 1, api_key : null, error_mess : false, query_use : true)](#)

pass (tool_id) if you need to detect or check query in specific tool

pass (query_no) in interger for detecting and checking query

pass (api_key) if query detect and check from api plan

pass (error_mess) if you need result in array pass true

pass (query_use) if you want only check query not detect then pass (false) value

## For Check Available Query

This function will be return (int || array)

-   [EmdAvailableQuery(api: 0, int both_web_api: 0, separate: false, api_key: null)](#)

-   **[You need User Login for this](#)**

without pass any params you can get Web remaining query

pass (api) if you want remaining query of API

-   **[No need User Login for this](#)**

(api_key) must pass

-   **[If you want total and remaining](#)**

pass (separate) then you can get array with 2 index

1 index will be total query

2 index will be remaining query

## For Dynamic Plan Link Generation

This function will be return (array)

-   [EmdPayproDynamicPlanLink(price: 5,discount_per: 0,currency: 'USD',plan_title: null,plan_desc: null,web_api: 0,days: 7,plan_availabilities: [['tool_id' => 0, 'queries_limit' => 100, 'allow_json' => ['modes' => 3, 'words_limit' => 50]]])](#)


## For Getting Pricin Plans

This function will be return (Collection || EmdPricingPlan || null)

-   [EmdPricingPlanRepository::emd_our_pricing_plans_static(ip: '127.0.0.1', id: 1, unique_key:'--------')](#)

-   **[Required Parameters](#)**

pass (ip) in string 

-   **[Optional Parameters](#)**

pass (id) if you need specific plan with plan id

pass (unique_key) if you need specific plan with unique_key


# About Laravel

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

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Many](https://www.many.co.uk)**
-   **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[OP.GG](https://op.gg)**
-   **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

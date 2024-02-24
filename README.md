# EMD 3 (v2.0.9) Laravel 9 && PHP ^8.0.2

# EMD Available Functions

## For Check is User Premium

This function will be return (0 || 1 || 2)

-   0 for Free User

-   1 for Premium User

-   2 for Register Plan User

-   [EmdWebUserController::EmdIsUserPremium(web: false, api:false, api_key: null)](#)

-   **[You need User Login for this](#)**

pass (web) if you want check user premium for Web

pass (api) if you want check user premium for API

-   **[No need User Login for this](#)**

(api_key) must pass

pass (web) if you want check user premium for Web

pass (api) if you want check user premium for API

## For Query Detect and Check

This function will be return (bool || array)

-   [EmdWebUserController::EmdWebsiteQueryUse(tool_id : null, query_no : 1, api_key : null, error_mess : false, query_use : true, web: true, remaining_query_detect: false)](#)

pass (tool_id) if you need to detect or check query in specific tool and also use can pass array of tool_ids

pass (query_no) in integer for detecting and checking query

pass (api_key) if query detect and check from api plan

pass (error_mess) if you need result in array pass true

pass (query_use) if you want only check query not detect then pass (false) value

pass (web) if you want to query detect and check only API plans then pass (false) value

pass (remaining_query_detect) if you want to detect remaining query then pass (true) value

## For Check Available Query

This function will be return (int || array)

-   [EmdWebUserController::EmdAvailableQuery(api: false, int both_web_api: false, separate: false, api_key: null)](#)

-   **[for getting WEB Query](#)**

without pass any params you can get Web remaining query

-   **[for getting API query](#)**

pass (api) if you want remaining query of API

-   **[for getting Both Web & API query](#)**

pass (both_web_api) if you want remaining query of both WEB & API

-   **[for getting query with API_KEY](#)**

(api_key) pass

-   **[If you want total and remaining](#)**

pass (separate) then you can get array with 2 index

1 index will be total query

2 index will be remaining query

## For Dynamic Plan Link Generation

This function will be return (array)

-   [EmdUserTransactionController::EmdPayproDynamicPlanLink(price: 5,discount_per: 0,currency: 'USD',plan_title: null,plan_desc: null,web_api: 0,days: 7,plan_availabilities: [['tool_id' => 0, 'queries_limit' => 100, 'allow_json' => ['modes' => 3, 'words_limit' => 50]]])](#)

-   **[For Web Dynamic Plan Pass (web_api = 0)](#)**

-   **[For API Dynamic Plan Pass (web_api = 1)](#)**

-   **[For WEB & API Dynamic Plan Pass (web_api = 2)](#)**

-   **[For specific tool pass (tool_id) it should be tool parent_id](#)**

## For Getting Pricing Plans

This function will be return (Collection || EmdPricingPlan || null)

-   [EmdPricingPlanRepository::emd_our_pricing_plans_static(ip: "127.0.0.1", id: null, unique_key: null, is_custom: 0, all_unique_key_plans: false)](#)

-   **[Required Parameters](#)**

pass (ip) in string

-   **[Optional Parameters](#)**

pass (id) if you need specific plan with plan id

pass (unique_key) if you need specific plan with unique_key

# EMD Available Functionality

## For Custom Query in any Page

You can set the query in Controller/Dev/CustomArrayController

-   **[Pattern is mention how to set query for specific page](#)**

## Create Custom Pages

Without tool and blog set all pages from Custom Pages

## For Custom Fields

Use custom fields for tool pages

-   **[Note these key value should be integer](#)**
-   **[Like](#)**

-   [Word limit](#)

-   [no of image](#)

-   [no of allow modes](#)

-   [upload file allow](#)

-   **[For free user these value will be same from custom fields](#)**

-   **[All these value will be depend on user plan](#)**

-   **[These value will be change on plan availability](#)**

# EMD Available Middleware

## For Query Detect and Check

Use this middleware

-   [emd_web_query_use](#)

-   **[For detect query in specific tool pass tool_id it should be tool parent_id](#)**

-   **[For detect query in specific plan which have this tool then pass tool_key which are showing in pricing plan set query](#)**

-   **[For detect query without any specific tool don't pass these above params](#)**

## For No of Request Limit per mint

Use this middleware

-   [throttle:toolNew](#)

-   **[Set Default value in Key Setting of (emd_throttle_tool_limit) key](#)**

-   **[For Specific tool set in parent tool (Request Limit) value and pass (parent_id) of tool in this request where you are use middleware](#)**

-   **[it also depend on custom field but it only for premium users](#)**

# For Mobile In-App-Purchase Payment

Follow these steps

-   [set (GOOGLE_PLAY_PACKAGE_NAME) value in .env this value provided from App Developer](#)

-   [set (APPSTORE_PASSWORD) value in .env this value provided from App Developer](#)

-   [set google-credentials in (google-credentials.json) file this json file provided from App Developer](#)

-   [Mobile App Product id set in Pricing Plan (Admin) side this (pid) provided from App Developer](#)

-   [After Upload on Live server run this command (php artisan liap:url) and select 0 it will return two URls these URLs send to App Developer](#)

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

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

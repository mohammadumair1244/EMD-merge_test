@include('layout.frontend.meta')
@include('layout.frontend.emd-clarity')
{{-- ADD HEAD FROM HERE --}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{asset('web_assets/frontend/css/style.css?v=' . config('constants.product_version') . '') }}">
<link rel="shortcut icon" href="{{asset('web_assets/frontend/img/favicon.png')}}" type="image/x-icon">
<meta name="csrf-token" content="{{ csrf_token() }}" />

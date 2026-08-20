{{-- Goal: Dashboard head metadata, styles, and dark mode initialization script, Livewire: None, Alpine: None --}}
<script>
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
            '(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
</script>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>@yield('title', setting('site_title', 'API Gateway & Management')) | {{ setting('site_name', 'RazorAPI') }}</title>
<meta name="description" content="{{ setting('meta_description', 'RazorAPI - Modern API Gateway & Client Management Platform') }}" />
<meta name="keywords" content="{{ setting('meta_keywords', 'api, gateway, razorapi, management') }}" />
<meta name="author" content="{{ setting('meta_author', 'RazorAPI') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="user-id" content="{{ auth()->user()->id }}">
<meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">

@if(setting('google_analytics_id'))
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ setting('google_analytics_id') }}');
    </script>
@endif

<!-- Favicons -->
<link href="{{ setting('favicon_path') ? asset('storage/' . setting('favicon_path')) : asset('images/brand/logo.ico') }}" rel="icon" />
<link href="{{ setting('apple_touch_icon_path') ? asset('storage/' . setting('apple_touch_icon_path')) : asset('images/brand/apple-touch-icon.png') }}" rel="apple-touch-icon" />
<link href="https://fonts.googleapis.com/css2?family=Bungee&family=Poppins&family=Montserrat&display=swap"
    rel="stylesheet">

@livewireStyles

<!-- Vite Files -->
@vite('resources/css/app.css')
@vite('resources/js/app.js')

{{-- Tom select --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EmaarAPI') }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <div class="row w-100 align-items-center">
                    <div class="col-md-2">
                        <a class="navbar-brand" href="{{ route('home') }}">
                            <img src="{{ asset('images/emaar.png') }}" class="logo-img" alt="Emaar Logo">
                        </a>
                    </div>
                    <div class="col-md-8 text-right">
                        @auth
                        <a class="navbar-brand" href="{{ route('home') }}">Branches</a>
                        <a class="navbar-brand" href="{{ route('payment.methods') }}">Payment Methods</a>
                        <a class="navbar-brand" href="{{ route('orders') }}">Orders</a>
                        <a class="navbar-brand" href="{{ route('logs') }}">Logs</a>
                        @endauth
                    </div>
                    <div class="col-md-2">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto">
                                @guest
                                @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @endif
                                @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                @endguest
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    @stack('scripts')
    
    <style>
        .logo-img {
            width: 100px;
            height: 50px;
            object-fit: contain;
        }
        
        .pagination-wrapper {
            margin-bottom: 10px;
        }
        
        .pagination-wrapper a {
            display: inline-block;
        }
        
        table.table-bordered,
        table.table-bordered th,
        table.table-bordered td {
            border: 1px solid #dee2e6;
        }
        
        .btn {
            border: 2px solid #04AA6D;
            color: #04AA6D;
            padding: 4px 8px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background-color: #04AA6D;
            color: white;
        }
        
        .btn-success {
            background-color: #04AA6D;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #038a5a;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        
        .btn-info {
            background-color: #17a2b8;
            color: white;
            border-color: #17a2b8;
        }
        
        .d-flex.gap-2 {
            gap: 0.5rem;
        }
        
        .text-right {
            text-align: right;
        }
        
        .loader-container {
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.58);
            backdrop-filter: blur(7.6px);
            -webkit-backdrop-filter: blur(7.6px);
        }
        
        .loader {
            z-index: 10000;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</body>

</html>
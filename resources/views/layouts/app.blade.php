<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopEase')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand">Shop<span>Ease</span></a>
        <nav class="main-nav">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('products.index') }}">Products</a>
            @auth
                <a href="{{ route('cart.index') }}">Cart</a>
                <a href="{{ route('orders.index') }}">My Orders</a>
                @if(auth()->user()->canAccessAdminPanel())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="link-btn">Logout ({{ auth()->user()->name }})</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" class="btn btn-sm">Sign up</a>
            @endauth
        </nav>
    </div>
</header>

<main class="container page">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <p>&copy; {{ now()->year }} ShopEase — Advanced Web Project (Laravel).</p>
    </div>
</footer>
</body>
</html>

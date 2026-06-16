@extends('layouts.app')
@section('title', 'Login — ShopEase')

@section('content')
<div class="form-card" style="max-width:420px;margin:20px auto">
    <h1 style="margin-top:0;color:var(--green)">Login</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field full" style="margin-bottom:12px">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="field full" style="margin-bottom:12px">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>
        <label style="display:block;margin-bottom:12px"><input type="checkbox" name="remember" value="1"> Remember me</label>
        <button class="btn" type="submit" style="width:100%">Login</button>
    </form>
    <p class="muted" style="margin-top:14px">No account? <a href="{{ route('register') }}">Create one</a></p>
    <p class="muted" style="font-size:12px">Demo admin: admin@shopease.com / password123</p>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Sign up — ShopEase')

@section('content')
<div class="form-card" style="max-width:440px;margin:20px auto">
    <h1 style="margin-top:0;color:var(--green)">Create account</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="field full" style="margin-bottom:12px">
            <label for="name">Full name</label>
            <input id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="field full" style="margin-bottom:12px">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="field full" style="margin-bottom:12px">
            <label for="phone">Phone (optional)</label>
            <input id="phone" name="phone" value="{{ old('phone') }}">
        </div>
        <div class="field full" style="margin-bottom:12px">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div class="field full" style="margin-bottom:12px">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>
        <button class="btn" type="submit" style="width:100%">Sign up</button>
    </form>
    <p class="muted" style="margin-top:14px">Already have an account? <a href="{{ route('login') }}">Login</a></p>
</div>
@endsection

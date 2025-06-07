@extends('layouts.app')

@section('content')
    <div style="background-color: #f4f4f4; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div
            style="width: 100%; max-width: 400px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; color: #007061; margin-bottom: 20px;">Reset Password</h2>
            @if (session('status'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 5px; color: #333;">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

                    @error('email')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px; position: relative;">
                    <label for="password" style="display: block; margin-bottom: 5px; color: #333;">New Password</label>
                    <input id="password" type="password" name="password" placeholder="123456UQ$" required
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; padding-right: 40px;">
                    <button type="button" onclick="togglePassword('password')"
                        style="position: absolute; right: 10px; top: 35px; background: none; border: none; cursor: pointer; color: #007061;">
                        👁️
                    </button>
                    @error('password')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px; position: relative;">
                    <label for="password_confirmation" style="display: block; margin-bottom: 5px; color: #333;">Confirm
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="123456UQ$"
                        required
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; padding-right: 40px;">
                    <button type="button" onclick="togglePassword('password_confirmation')"
                        style="position: absolute; right: 10px; top: 35px; background: none; border: none; cursor: pointer; color: #007061;">
                        👁️
                    </button>
                </div>

                <button type="submit"
                    style="width: 100%; background-color: #007061; color: #fff; padding: 10px; border: none; border-radius: 4px; font-size: 16px;">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
@endsection
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>

@extends('layouts.app')

@section('content')
    <div style="background-color: #f4f4f4; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div
            style="width: 100%; max-width: 400px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; color: #007061; margin-bottom: 20px;">Login</h2>
            @if (session('status'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div
                    style="background-color: #d4edda; color: #a10000; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf


                <div style="margin-bottom: 20px;">
                    <label for="email_or_phone" style="display: block; margin-bottom: 5px; color: #333;">Email or Phone
                    </label>
                    <input id="email_or_phone" type="text" name="email_or_phone" value="{{ old('email_or_phone') }}"
                        required autofocus style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

                    @error('email_or_phone')
                        <span style="color: red; font-size: 14px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px; position: relative;">
                    <label for="password" style="display: block; margin-bottom: 5px; color: #333;">Password</label>
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

                <button type="submit"
                    style="width: 100%; background-color: #007061; color: #fff; padding: 10px; border: none; border-radius: 4px; font-size: 16px;">
                    Login
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

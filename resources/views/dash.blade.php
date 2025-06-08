@extends('layouts.app')

@section('content')
    <div style="background-color: #f4f4f4; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div
            style="width: 100%; max-width: 400px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; color: #007061; margin-bottom: 20px;">Hello</h2>
            @if (session('status'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                    {{ session('status') }}
                </div>
            @endif
            <h1 style="text-align: center">{{ $user->full_name }}</h1>
            <p style="text-align: center">{{ $user->email }}</p>
            <h4 style="text-align: center">
                {{ $user->city->name . ' - ' . $user->role->name . ' - ' . $user->code_phone . $user->phone }}</h4>
        </div>
    </div>
@endsection
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>

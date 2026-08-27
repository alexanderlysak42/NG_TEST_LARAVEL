<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Registration</title>
</head>
<body>
    <h1>Registration</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="post" action="{{ route('register') }}">
        @csrf

        <label>
            Username
            <input type="text" name="username" value="{{ old('username') }}" required>
        </label>

        <label>
            Phone number
            <input type="text" name="phone_number" value="{{ old('phone_number') }}" required>
        </label>

        <button type="submit">Register</button>
    </form>
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Page A</title>
</head>
<body>
    <h1>Welcome, {{ $registration->username }}</h1>
    <p>Link active until: {{ $registration->expires_at }}</p>

    <form method="post" action="{{ route('page-a.regenerate', $registration->token) }}" style="display:inline">
        @csrf
        <button type="submit">Regenerate link</button>
    </form>

    <form method="post" action="{{ route('page-a.deactivate', $registration->token) }}" style="display:inline">
        @csrf
        <button type="submit">Deactivate</button>
    </form>

    <form method="post" action="{{ route('page-a.play', $registration->token) }}" style="display:inline">
        @csrf
        <button type="submit">Im feeling lucky</button>
    </form>

    <form method="get" action="{{ route('page-a.history', $registration->token) }}" style="display:inline">
        <button type="submit">History</button>
    </form>

    @if ($lastResult !== null)
        <h2>Result</h2>
        <p>Number: {{ $lastResult['number'] }}</p>
        <p>Result: {{ ucfirst($lastResult['result']) }}</p>
        <p>Win amount: {{ number_format($lastResult['amount'], 2) }}</p>
    @endif

    @if ($history !== null)
        <h2>History (last 3)</h2>
        <ul>
            @foreach ($history as $item)
                <li>
                    {{ $item->created_at }} —
                    number {{ $item->number }},
                    {{ ucfirst($item->result) }},
                    win amount {{ number_format($item->amount, 2) }}
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlignUp</title>
</head>
<body>
    @if ($user)
        {{ $user->name }} Welcome to AlignUp Dashboard!
    @else
        Welcome to AlignUp Dashboard!
    @endif
</body>
</html>
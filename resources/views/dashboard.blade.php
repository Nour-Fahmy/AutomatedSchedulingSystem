<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlignUp</title>
</head>
    <body>
        @if (Auth::check())
            Welcome {{ Auth::user()->name }}  to AlignUp Dashboard!
            <a href="/auth/logout"><button>log out</button></a>
        @else
            Welcome to AlignUp Dashboard!
            <a href="/auth/login"><button>log in</button></a>
            <a href="/auth/signup"><button>sign up</button></a>
        @endif
    </body>
</html>
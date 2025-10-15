<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{ route("user.signup") }}" method="post">
        @csrf
        <label for="name">name</label>
        <input type="text" id="name" name="name">

        <label for="email">email</label>
        <input type="text" id="email" name="email">

        <label for="password">password</label>
        <input type="password" id="password" name="password">

        <label for="type">type</label>
        <input type="text" id="type" name="type">

        <button type="submit">Sign up</button>
    </form>
</body>
</html>
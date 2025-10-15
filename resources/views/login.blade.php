<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{ route("user.login") }}" method="post">
        @csrf
        <label for="email">Email:</label>
        <input type="text" id="email" name="email">

        <label for="Password">Password:</label>
        <input type="text" id="Password" name="password">

        <button type="submit">Save</button>
    </form>
</body>
</html>
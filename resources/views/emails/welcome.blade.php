<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Quickbook Panelone</title>
</head>
<body>
<p>Hello {{ $merchant->name }},</p>

<p>Welcome to Quickbook Panelone! Your account has been created successfully.</p>

<p>Your login credentials:</p>
<ul>
    <li>Email: {{ $merchant->email }}</li>
    <li>Password: {{ $password }}</li>
    <li>Link: <a href={{url()}}> Click Here</a></li>
</ul>

<p>It is recommended to change your password after your first login.</p>

<p>Thank you for choosing Our App!</p>
</body>
</html>

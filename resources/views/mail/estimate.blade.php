<!DOCTYPE html>
<html>
<head>
    <title>Estimate</title>
</head>
<body>
    <h1>Hello, {{ $data['customer_name'] }}</h1>
    <p>Please find the attached estimate for your request.</p>

    <p>Click the button below to respond:</p>

    <a href="{{ $data['accept_url'] }}" style="color: white; background-color: green; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Accept</a>
    <a href="{{ $data['decline_url'] }}" style="color: white; background-color: red; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Decline</a>
</body>
</html>

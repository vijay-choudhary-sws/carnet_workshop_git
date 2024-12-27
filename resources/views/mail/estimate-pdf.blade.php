<!DOCTYPE html>
<html>
<head>
    <title>Estimate</title>
</head>
<body>
    <h1>Estimate for {{ $data['customer_name'] }}</h1>
    <p>Details:</p>
    <ul>
        <li>Service: {{ $data['service'] }}</li>
        <li>Amount: ₹{{ $data['amount'] }}</li>
        <li>Date: {{ $data['date'] }}</li>
    </ul>
</body>
</html>

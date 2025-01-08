<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
    <h1>Make a Payment</h1>
    <form id="payment-form">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required>
        <button type="button" onclick="makePayment()">Pay Now</button>
    </form>

    <script>
        async function makePayment() {
            const amount = document.getElementById('amount').value;


            const response = await fetch("{!! url('create-order') !!}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    amount
                }),
            });

            const {
                order_id,
                amount: orderAmount
            } = await response.json();

            const options = {
                key: '{{ config('services.razorpay.key') }}',
                amount: orderAmount,
                currency: 'INR',
                order_id: order_id,
                handler: function(response) {
                    fetch("{!! url('store-payment') !!}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                            amount: orderAmount,
                        }),
                    }).then(() => {
                        const paymentId = response.razorpay_payment_id;
                        const customParam = "example_value";
                        const redirectUrl =
                            `{!! url('payment-success') !!}?payment_id=${paymentId}&order_id=${order_id}&amount=${orderAmount / 100}&custom_param=${customParam}`;
                        window.location.href = redirectUrl;
                    });
                },
            };

            const rzp = new Razorpay(options);
            rzp.open();
        }
    </script>
</body>

</html>

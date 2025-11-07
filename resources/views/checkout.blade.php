<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Stripe Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <h1>ادفع 50 دولار</h1>
    <button id="checkout-button">ادفع الآن</button>

    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");

        document.getElementById('checkout-button').addEventListener('click', async () => {
            const res = await fetch("{{ route('create.session') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                credentials: 'include'
            });


            const data = await res.json();
            const result = await stripe.redirectToCheckout({
                sessionId: data.id
            });

            if (result.error) {
                alert(result.error.message);
            }
        });
    </script>
</body>

</html>

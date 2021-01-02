<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Subscribe
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('subscribe.post') }}" method="post" id="payment-form" data-secret="{{ $intent->client_secret }}">
                        @csrf
                        <div class="w-1/2 form-row">
                            <label for="cardholder-name">Cardholder's name</label>
                            <div><input type="text" name="" id="cardholder-name"></div>

                            <div class="mt-4">
                                <input type="radio" name="plan" id="standart" value="price_1I5AG4K9YNhLPRz6v5t3MYSr">
                                <label for="standart">Standart - $10</label>

                                <input type="radio" name="plan" id="premium" value="price_1I5AG4K9YNhLPRz6v5t3MYSr">
                                <label for="premium">Premium - $20</label>
                            </div>
                            <label for="card-element">
                            Credit or debit card
                            </label>
                            <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                            </div>

                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>

                        <x-button class="ml-3">
                            Subscribe now
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
      <script src="https://js.stripe.com/v3/"></script>

      <script>
        // Create a Stripe client.
        var stripe = Stripe('pk_test_51I3yzgK9YNhLPRz64049NdHzDCjM68pLEC6TTgZS1XnSY8VKB4AfIKSKcfpqT5qQoUU8CBslrbLL6paBzGaRotju002DsSRoaT');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
        // Handle real-time validation errors from the card Element.
        card.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        var cardHolderName = document.getElementById('cardholder-name');
        var clientSecret = form.dataset.secret;
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );

            if (error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                // The card has been verified successfully...
                // console.log(setupIntent);
                stripeTokenHandler(setupIntent);
            }

            // stripe.createToken(card).then(function(result) {
            //     if (result.error) {
            //     // Inform the user if there was an error.
            //     var errorElement = document.getElementById('card-errors');
            //     errorElement.textContent = result.error.message;
            //     } else {
            //     // Send the token to your server.
            //     stripeTokenHandler(result.token);
            //     }
            // });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(setupIntent) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'paymentMethod');
            hiddenInput.setAttribute('value', setupIntent.payment_method);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }
      </script>
    @endpush
</x-app-layout>

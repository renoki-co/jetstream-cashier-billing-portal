<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a
                href="{{ route('billing-portal.payment-method.index') }}"
                method="post"
                as="button"
                class="focus:outline-none mr-1"
            >
                &larr;
            </a>
            Billing: Add Payment Method
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col break-words bg-white border rounded-lg shadow-md mb-4">
                <div class="w-full p-6">
                    <div class="flex flex-wrap mb-6">
                        <x-jet-label
                            for="name"
                            value="Card Holder Name">
                        </x-jet-label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full text-xs"
                            required
                            autocomplete="name"
                        >
                    </div>

                    <div class="flex flex-wrap mb-6 space-y-6 md:space-y-0">
                        <div class="w-full md:w-1/2 md:pr-1">
                            <x-jet-label
                                for="card-number"
                                value="Card Number">
                            </x-jet-label>
                            <div
                                id="card-number"
                                autocomplete="card-number">
                            </div>
                        </div>

                        <div class="w-full md:w-1/4 md:px-1">
                            <x-jet-label
                                for="card-expiry"
                                value="Card Expiry">
                            </x-jet-label>
                            <div
                                id="card-expiry"
                                autocomplete="card-expiry">
                            </div>
                        </div>

                        <div class="w-full md:w-1/4 md:pl-1">
                            <x-jet-label
                                for="card-cvc"
                                value="CVC">
                            </x-jet-label>
                            <div
                                id="card-cvc"
                                autocomplete="card-cvc">
                            </div>
                        </div>
                    </div>

                    <x-jet-button
                        id="add-payment-method-button"
                        data-secret="{{ $intent->client_secret }}"
                        :disabled="false"
                        onclick="addPaymentMethod()"
                    >
                        <div id="add-payment-method-button-loading" class="flex hidden mr-2 justify-center items-center">
                            <div class="animate-spin rounded-full h-3 w-3 border-b-2 border-white-900"></div>
                        </div>
                        Add Payment Method
                    </x-jet-button>
                </div>
            </div>

            <div class="text-center">
                The payment is
                <a
                    href="https://stripe.com"
                    target="_blank"
                >
                    <img
                        src="/images/stripe_blurple.svg"
                        class="w-32 h-auto inline ml-1"
                    />
                </a>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ $stripe_key }}')

        const card = stripe.elements()

        const number = card.create('cardNumber', {
            classes: {
                base: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                complete: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                empty: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                focus: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                invalid: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full border-red-500',
            }
        })

        number.mount('#card-number')

        const expiry = card.create('cardExpiry', {
            classes: {
                base: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                complete: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                empty: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                focus: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                invalid: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full border-red-500',
            }
        })

        expiry.mount('#card-expiry')

        const cvc = card.create('cardCvc', {
            classes: {
                base: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                complete: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                empty: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                focus: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                invalid: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full border-red-500',
            }
        })

        cvc.mount('#card-cvc');

        const btn = document.getElementById('add-payment-method-button')
        const btnLoading = document.getElementById('add-payment-method-button-loading')

        async function addPaymentMethod() {
            btn.setAttribute('disabled', 'true')
            await stripe.createPaymentMethod(
                'card', number, { billing_details: { name: document.getElementById('name').value || ' ' } }
            ).then(r => {
                btnLoading.classList.remove('hidden')
                window.axios.post('{{ route('billing-portal.payment-method.store') }}', { token: r.paymentMethod.id }).then(() => {
                    window.location.replace('{{ route('billing-portal.payment-method.index') }}');
                })
            }).catch(() => {
                btnLoading.classList.add('hidden')
                btn.removeAttribute('disabled')
            })
        }
    </script>

</x-app-layout>

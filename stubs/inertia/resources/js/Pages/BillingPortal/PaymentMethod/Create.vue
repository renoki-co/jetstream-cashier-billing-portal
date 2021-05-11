<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Billing: Add Payment Method
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col break-words bg-white border rounded-lg shadow-md mb-4">
                    <div class="w-full p-6">
                        <div class="flex flex-wrap mb-6">
                            <jet-label
                                for="name"
                                value="Card Holder Name"
                            />

                            <input
                                v-model="name"
                                type="text"
                                class="border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full text-xs"
                                required
                                autocomplete="name"
                            >
                        </div>

                        <div class="flex flex-wrap mb-6 space-y-6 md:space-y-0">
                            <div class="w-full md:w-1/2 md:pr-1">
                                <jet-label
                                    for="card-number"
                                    value="Card Number"
                                />
                                <div
                                    id="card-number"
                                    autocomplete="card-number"
                                />
                            </div>

                            <div class="w-full md:w-1/4 md:px-1">
                                <jet-label
                                    for="card-expiry"
                                    value="Card Expiry"
                                />
                                <div
                                    id="card-expiry"
                                    autocomplete="card-expiry"
                                />
                            </div>

                            <div class="w-full md:w-1/4 md:pl-1">
                                <jet-label
                                    for="card-cvc"
                                    value="CVC"
                                />
                                <div
                                    id="card-cvc"
                                    autocomplete="card-cvc"
                                />
                            </div>
                        </div>

                        <jet-button
                            :data-secret="intent.client_secret"
                            @click="addPaymentMethod"
                        >
                            Add Payment Method
                        </jet-button>
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
                        >
                    </a>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import JetButton from '@/Jetstream/Button';
import JetLabel from '@/Jetstream/Label';

export default {
    components: {
        AppLayout,
        JetButton,
        JetLabel,
    },
    props: [
        'intent',
        'stripe_key',
    ],
    data() {
        return {
            stripe: null,
            card: null,
            name: null,
            number: null,
            expiry: null,
            cvc: null,
        };
    },
    mounted() {
        this.stripe = Stripe(this.stripe_key);

        this.card = this.stripe.elements();

        this.number = this.card.create('cardNumber', {
            classes: {
                base: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                complete: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                empty: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                focus: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                invalid: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full border-red-500',
            },
        });

        this.number.mount('#card-number');

        this.expiry = this.card.create('cardExpiry', {
            classes: {
                base: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                complete: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                empty: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                focus: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                invalid: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full border-red-500',
            },
        });

        this.expiry.mount('#card-expiry');

        this.cvc = this.card.create('cardCvc', {
            classes: {
                base: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                complete: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                empty: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                focus: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full',
                invalid: 'border focus:border-gray-500 focus:outline-none px-3 py-2 rounded-lg shadow-sm w-full border-red-500',
            },
        });

        this.cvc.mount('#card-cvc');
    },
    methods: {
        /**
         * Submit the payment method form.
         */
        async addPaymentMethod() {
            const { paymentMethod, error } = await this.stripe.createPaymentMethod(
                'card', this.number, { billing_details: { name: this.name } }
            );

            if (!error) {
                this.$inertia.post(this.route('billing-portal.payment-method.store'), { token: paymentMethod.id });
            }
        },
    },
}
</script>

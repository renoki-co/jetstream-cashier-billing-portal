<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Billing: Payment Methods
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <jet-button @click.native="$inertia.visit(route('billing-portal.payment-method.create'))">
                    Add New Payment Method
                </jet-button>

                <div class="sm:-mx-6 lg:-mx-8 overflow-x-auto mt-3">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Card number
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Default
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50" />
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr
                                        v-for="method in methods"
                                        :key="method.id"
                                    >
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <span class="uppercase">
                                                {{ method.brand }}
                                            </span>

                                            <div class="inline ml-2">
                                                **** **** **** {{ method.last_four }}
                                            </div>

                                            <div class="text-gray-500 text-xs">
                                                Expires on: {{ method.month }}/{{ method.year }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-center">
                                            <svg
                                                v-if="method.default"
                                                class="h-6 w-6 text-green-400 mx-auto"
                                                fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24
                                            "
                                            >
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </td>

                                        <td class="px-6 py-4 whitespace-no-wrap text-center">
                                            <jet-secondary-button
                                                v-if="! method.default"
                                                @click.native="setAsDefault(method)"
                                            >
                                                Set as default
                                            </jet-secondary-button>

                                            <jet-danger-button @click.native="deletePaymentMethod(method)">
                                                Delete
                                            </jet-danger-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import JetButton from '@/Jetstream/Button';
import JetDangerButton from '@/Jetstream/DangerButton';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';

export default {
    components: {
        AppLayout,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
    },
    props: [
        'methods',
    ],
    methods: {
        /**
         * Set the default payment method.
         *
         * @param  Object  method
         */
        setAsDefault (method) {
            this.$inertia.post(
                this.route('billing-portal.payment-method.default', { payment_method: method.id })
            );
        },

        /**
         * Delete a payment method.
         *
         * @param  Object  method
         */
        deletePaymentMethod (method) {
            this.$inertia.delete(
                this.route('billing-portal.payment-method.destroy', { payment_method: method.id })
            )
        },
    },
}
</script>

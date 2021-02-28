<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Billing: Invoices
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="-my-2 sm:-mx-6 lg:-mx-8 overflow-x-auto">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Plan
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Issued
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50" />
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr
                                        v-for="invoice in invoices"
                                        :key="invoice.id"
                                    >
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            {{ invoice.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-center">
                                            {{ formatDate(invoice.created) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-center">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                :class="[invoice.paid ? 'bg-blue-200 text-blue-800' : 'bg-red-500 text-white']"
                                            >
                                                {{ invoice.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <a
                                                :href="invoice.url"
                                                class="text-blue-400 hover:text-blue-500 ml-3"
                                                target="_blank"
                                            >
                                                View invoice &rarr;
                                            </a>
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

export default {
    components: {
        AppLayout,
    },

    props: ['invoices'],

    data: () => ({
        dateFormatOptions: {
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: false,
        },
    }),

    methods: {

        /**
         * Format the given unix date to a human readable one.
         *
         * @param {number} unix
         */
        formatDate(unix) {
            return new Intl.DateTimeFormat('en-GB', this.dateFormatOptions).format(
                new Date(unix * 1000)
            );
        },
    },
}
</script>

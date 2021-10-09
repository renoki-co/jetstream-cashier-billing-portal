<x-app-layout>
    <x-slot name="header">
        @yield('header')
    </x-slot>
    <div class="md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row space-y-4 md:space-x-4 md:space-y-0">
                <div class="w-full md:w-1/3 lg:w-1/4 space-y-2">
                    <a
                        href="{{ route('billing-portal.subscription.index') }}"
                        class="{{ request()->routeIs('billing-portal.subscription.index') ? 'bg-gray-200' : '' }} flex space-x-2 text-gray-500 hover:bg-gray-200 p-3 rounded-lg"
                    >
                        <svg
                            class="w-6 h-6"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill="currentColor"
                                d="M12 8L15 13.2L18 10.5L17.3 14H6.7L6 10.5L9 13.2L12 8M12 4L8.5 10L3 5L5 16H19L21 5L15.5 10L12 4M19 18H5V19C5 19.6 5.4 20 6 20H18C18.6 20 19 19.6 19 19V18Z"
                            />
                        </svg>
                        <div
                            class="font-semibold {{ request()->routeIs('billing-portal.subscription.index') ? 'text-gray-700' : '' }}"
                        >
                            {{ __('Subscriptions') }}
                        </div>
                    </a>
                    <a
                        href="{{ route('billing-portal.payment-method.index') }}"
                        class="{{ request()->routeIs('billing-portal.payment-method.index') ? 'bg-gray-200' : '' }} flex space-x-2 text-gray-500 hover:bg-gray-200 p-3 rounded-lg"
                    >
                        <svg
                            class="w-6 h-6"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill="currentColor"
                                d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"
                            />
                        </svg>
                        <div
                            class="font-semibold"
                            class="{{ request()->routeIs('billing-portal.payment-method.index') ? 'text-gray-700' : '' }} font-semibold"
                        >
                            Payment Methods
                        </div>
                    </a>
                    <a
                        href="{{ route('billing-portal.invoice.index') }}"
                        class="{{ request()->routeIs('billing-portal.invoice.index') ? 'bg-gray-200' : '' }} flex space-x-2 text-gray-500 hover:bg-gray-200 p-3 rounded-lg"
                    >
                        <svg
                            class="w-6 h-6"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill="currentColor"
                                d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"
                            />
                        </svg>
                        <div
                            class="{{ request()->routeIs('billing-portal.invoice.index') ? 'text-gray-700' : '' }} font-semibold">
                            Invoices
                        </div>
                    </a>
                    <div
                        class="flex space-x-2 text-gray-500 hover:bg-gray-200 p-3 rounded-lg"
                    >
                        <a
                            href="{{ route('billing-portal.portal') }}"
                            method="post"
                            as="button"
                        >
                            <div class="font-semibold text-gray-700">
                                Stripe Billing Portal
                                <svg
                                    viewBox="0 0 24 24"
                                    class="w-4 h-4 ml-1 inline"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"
                                    />
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="flex-1">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

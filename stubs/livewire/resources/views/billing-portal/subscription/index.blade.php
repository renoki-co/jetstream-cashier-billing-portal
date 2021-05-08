<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing') }}
        </h2>

        <div class="mt-3 space-x-2">
            <a href="#">
                <x-jet-secondary-button>
                    {{ __('Invoices') }}
                </x-jet-secondary-button>
            </a>

            <a href="#">
                <x-jet-secondary-button href="#">
                    {{ __('Payment methods') }}
                </x-jet-secondary-button>
            </a>

            <a href="{{ route('billing-portal.portal') }}">
                <x-jet-secondary-button>
                    {{ __(' Stripe Billing Portal') }}
                    <svg
                        viewBox="0 0 24 24"
                        class="w-4 h-4 ml-1"
                    >
                        <path
                            fill="currentColor"
                            d="M14,3V5H17.59L7.76,14.83L9.17,16.24L19,6.41V10H21V3M19,19H5V5H12V3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V12H19V19Z"
                        />
                    </svg>
                </x-jet-secondary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center">
                @foreach($plans as $plan)
                    <div class="p-4 md:w-1/3 w-full">
                        <x-plan-slide
                            :plan="$plan"
                            :active="$currentPlan && $plan->id === $currentPlan->id"
                            :currentPlan="$currentPlan"
                            :features="$plan->getFeatures()"
                            :recurring="$recurring"
                            :cancelled="$cancelled"
                            :onGracePeriod="$onGracePeriod"
                            :endingData="$endingDate"
                            :disable="false"
                        />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

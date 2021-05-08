@props([
    'plan',
    'active',
    'currentPlan',
    'features',
    'recurring',
    'cancelled',
    'onGracePeriod',
    'endingDate' => null,
    'disable'
])

<div class="{{ $active ? 'border-blue-500' : 'border-gray-300' }} h-full p-6 rounded-lg bg-white border-2 flex flex-col relative overflow-hidden">

    @if($active)
        <span class="bg-blue-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">
            {{ __('Current') }}
        </span>
    @endif

    <h2 class="text-sm tracking-widest title-font mb-1 font-medium">
        {{ $plan->getName() }}
    </h2>

    <h1 class="text-3xl text-gray-900 pb-4 mb-4 border-b border-gray-200 leading-none">
        <span>
            {{ $plan->getPrice() > 0.00 ? `${$plan->currency} ${$plan->getPrice()}` : 'Free' }}
        </span>

        @if($plan->getPrice() > 0.00)
            <span class="text-lg font-normal text-gray-500">
                / mo
            </span>
        @endif
    </h1>

    @foreach($features as $feature)
        <p class="flex items-center mb-2 {{ $active ? 'text-blue-500' : 'text-gray-600' }}">
            <span class="{{ $active ?  'bg-blue-500' : 'bg-gray-600' }} w-4 h-4 mr-2 inline-flex items-center justify-center text-white rounded-full flex-shrink-0">
                <svg
                    fill="none"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    class="w-3 h-3"
                    viewBox="0 0 24 24"
                >
                    <path d="M20 6L9 17l-5-5" />
                </svg>
            </span>
            {{ $feature->getName() }}
        </p>
    @endforeach

    <div class="mt-3">
        @if($disable)
            <div v-if="disable">
                <jet-secondary-button>
                    Waiting...
                </jet-secondary-button>
            </div>
        @else
            <div>
                @if(!$currentPlan)
                    <x-jet-button @click.native="swapPlan(plan.id)">
                        Subscribe
                    </x-jet-button>
                @endif

                @if($currentPlan === $plan->getId())
                    <jet-secondary-button @click.native="swapPlan(plan.id">
                        Subscribe
                    </jet-secondary-button>
                @endif

                @if($currentPlan && $currentPlan->id === $plan->getId() && $recurring)
                    <jet-button @click.native="cancelSubscription">
                        Cancel
                    </jet-button>
                @endif

                @if($currentPlan && $currentPlan->id === $plan->getId() && $cancelled && $onGracePeriod)
                    <jet-button @click.native="resumeSubscription">
                        Resume
                    </jet-button>
                @endif

                @if($currentPlan && $currentPlan->id === $plan->getId() && $endingDate)
                    <p class="text-xs text-gray-500 mt-3">
                        The subscription will end on {{ $endingDate }}.
                    </p>
                @endif

            </div>
        @endif
    </div>
</div>

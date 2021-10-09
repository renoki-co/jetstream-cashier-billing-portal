<div class="space-y-6">
    <div class="grid grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($plans as $plan)
            <div class="w-full flex">
                <div class="{{ $currentPlan && $plan->getId() === $currentPlan->getId() ? 'border-indigo-500' : 'border-gray-300' }} flex flex-col w-full justify-between border-2 rounded-lg p-4 space-y-9">
                    <div class="space-y-3">
                        <div class="font-bold text-lg">
                            {{ $plan->getName() }}
                        </div>

                        <div class="font-bold">
                            <span class="text-4xl font-extrabold">
                                {{ $plan->getPrice() > 0.00 ? $plan->getCurrency().$plan->getPrice() : 'Free' }}
                                @if($plan->getPrice() > 0.00)
                                    <span class="font-normal text-base"> /month</span>
                                @endif
                            </span>
                        </div>

                        @if($plan->getDescription())
                            <div class="text-gray-500">
                                {{ $plan->getDescription() }}
                            </div>
                        @endif

                        <div class="flex flex-col space-y-3">
                            @foreach($plan->getFeatures() as $feature)
                                <p class="flex items-baseline {{ $currentPlan && $plan->getId('id') === $currentPlan->getId('id') ? 'text-indigo-500' : 'text-gray-600' }}">
                                    <span class="{{ $currentPlan && $plan->getId('id') === $currentPlan->getId('id') ? 'bg-indigo-500' : 'bg-gray-600' }} w-4 h-4 mr-2 inline-flex items-center justify-center text-white rounded-full flex-shrink-0">
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
                                    @if(method_exists($feature, 'getMeteredId'))
                                        <span v-if="feature.metered_id">
                                            {{ $feature->getName() }}
                                            <div class="text-gray-400 text-sm">
                                                {{ $plan->getCurrency() }}{{ $feature->getMeteredId() }}/{{ $feature->getMeteredUnitName() }} after
                                            </div>
                                        </span>
                                    @else
                                        <span>
                                            {{ $feature->getName() }}
                                        </span>
                                    @endif
                                </p>
                            @endforeach
                        </div>
                    </div>
                    @if(!$currentPlan)
                        <x-jet-button
                            wire:click="subscribeToPlan('{{ $plan->getId() }}')"
                            wire:loading.attr="disabled"
                            class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
                        >
                            <span class="mx-auto">Subscribe</span>
                        </x-jet-button>
                    @elseif($currentPlan->getId('id') !== $plan->getId('id'))
                        <x-jet-secondary-button
                            wire:click="swapPlan('{{ $plan->getId() }}')"
                            wire:loading.attr="disabled"
                            class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
                        >
                            <span class="mx-auto">Subscribe</span>
                        </x-jet-secondary-button>
                    @endif

                    @if($currentPlan && $currentPlan->getId('id') === $plan->getId('id') && $recurring)
                        <x-jet-secondary-button
                            wire:click="cancelSubscription"
                            wire:loading.attr="disabled"
                            class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
                        >
                            <span class="mx-auto">Cancel subscription</span>
                        </x-jet-secondary-button>
                    @endif

                    @if($currentPlan && $currentPlan->getId('id') === $plan->getId('id') && $cancelled && $onGracePeriod)
                        <x-jet-button
                            wire:click="resumeSubscription"
                            wire:loading.attr="disable"
                            class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
                        >
                            <span class="mx-auto">Resume subscription</span>
                        </x-jet-button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>


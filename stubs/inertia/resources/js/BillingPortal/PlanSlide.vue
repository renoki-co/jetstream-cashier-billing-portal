<template>
    <div
        class="h-full p-6 rounded-lg bg-white border-2 flex flex-col justify-between relative overflow-hidden"
        :class="[active ? 'border-indigo-500' : 'border-gray-300']"
    >
        <span
            v-if="active"
            class="bg-indigo-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl"
        >
            Current
        </span>

        <div>
            <h2 class="text-sm tracking-widest title-font mb-1 font-medium">
                {{ plan.name }}
            </h2>

            <h1 class="text-3xl text-gray-900 pb-4 mb-4 border-b border-gray-200 leading-none">
                <span>
                    {{ plan.monthly_price > 0.00 ? `${plan.currency}${plan.monthly_price}` : 'Free' }}
                </span>

                <span
                    v-if="plan.monthly_price > 0.00"
                    class="text-lg font-normal text-gray-500"
                >
                    / mo
                </span>

                <p
                    v-if="currentPlan && currentPlan.id === plan.id && endingDate"
                    class="text-xs text-gray-500 mt-3"
                >
                    The subscription will end on {{ endingDate }}.
                </p>
            </h1>


            <p
                v-for="(feature, index) in features"
                :key="`feature-${index}`"
                class="flex items-baseline mb-2"
                :class="[active ? 'text-indigo-500' : 'text-gray-600']"
            >
                <span
                    class="w-4 h-4 mr-2 inline-flex items-center justify-center text-white rounded-full flex-shrink-0"
                    :class="[active ? 'bg-indigo-500' : 'bg-gray-600']"
                >
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

                <span v-if="feature.metered_id">
                    {{ feature.name }}
                    <div class="text-gray-400 text-sm">{{ plan.currency }}{{ feature.metered_price }}/{{ feature.metered_unit_name }} after</div>
                </span>
                <span v-else>
                    {{ feature.name }}
                </span>
            </p>
        </div>

        <div class="mt-3">
            <template v-if="disable">
                <jet-secondary-button>
                    Waiting...
                </jet-secondary-button>
            </template>
            <template v-else>
                <jet-button
                    v-if="! currentPlan"
                    @click="subscribeToPlan(plan.id)"
                >
                    Subscribe
                </jet-button>

                <jet-secondary-button
                    v-else-if="plan.id !== currentPlan.id"
                    @click="swapPlan(plan.id)"
                >
                    Subscribe
                </jet-secondary-button>

                <jet-secondary-button
                    v-if="currentPlan && currentPlan.id === plan.id && recurring"
                    @click="cancelSubscription"
                >
                    Cancel
                </jet-secondary-button>

                <jet-button
                    v-if="currentPlan && currentPlan.id === plan.id && cancelled && onGracePeriod"
                    @click="resumeSubscription"
                >
                    Resume
                </jet-button>
            </template>
        </div>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';

export default {
    components: {
        JetButton,
        JetSecondaryButton,
    },

    props: [
        'plan',
        'active',
        'currentPlan',
        'features',
        'recurring',
        'cancelled',
        'onGracePeriod',
        'endingDate',
        'disable',
    ],

    methods: {
        subscribeToPlan(planId) {
            this.$inertia.post(this.route('billing-portal.subscription.plan-subscribe', { plan: planId }));
        },

        swapPlan(planId) {
            this.$inertia.post(this.route('billing-portal.subscription.plan-swap', { plan: planId }));
        },

        cancelSubscription() {
            this.$inertia.post(this.route('billing-portal.subscription.cancel'));
        },

        resumeSubscription() {
            this.$inertia.post(this.route('billing-portal.subscription.resume'));
        },
    },
}
</script>

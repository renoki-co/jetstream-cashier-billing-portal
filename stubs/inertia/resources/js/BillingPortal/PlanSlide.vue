<template>
    <div
        class="flex flex-col w-full justify-between border-2 rounded-lg p-4 space-y-9"
        :class="[active ? 'border-indigo-500' : 'border-gray-300']"
    >
        <div class="space-y-3">
            <div class="font-bold text-lg">
                {{ plan.name }}
            </div>

            <div class="font-bold">
                <span class="text-4xl font-extrabold">
                    {{ planPrice > 0.00 ? `${plan.currency}${planPrice}` : 'Free' }}<span
                        v-if="planPrice > 0.00"
                        class="font-normal text-base"
                    >
                        /month
                    </span>
                </span>
            </div>

            <div
                v-if="plan.description"
                class="text-gray-500"
            >
                {{ plan.description }}
            </div>

            <div class="flex flex-col space-y-3">
                <p
                    v-for="(feature, index) in features"
                    :key="`feature-${index}`"
                    class="flex items-baseline"
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
        </div>

        <template v-if="disable">
            <jet-secondary-button>
                Waiting...
            </jet-secondary-button>
        </template>
        <template v-else>
            <jet-button
                v-if="! currentPlan"
                @click="subscribeToPlan(plan.id)"
                class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
            >
                <span class="mx-auto">Subscribe</span>
            </jet-button>

            <jet-secondary-button
                v-else-if="plan.id !== currentPlan.id"
                @click="swapPlan(plan.id)"
                class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
            >
                <span class="mx-auto">Subscribe</span>
            </jet-secondary-button>

            <jet-secondary-button
                v-if="currentPlan && currentPlan.id === plan.id && recurring"
                @click="cancelSubscription"
                class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
            >
                <span class="mx-auto">Cancel subscription</span>
            </jet-secondary-button>

            <jet-button
                v-if="currentPlan && currentPlan.id === plan.id && cancelled && onGracePeriod"
                @click="resumeSubscription"
                class="bg-indigo-100 hover:bg-indigo-200 text-indigo-600 hover:text-indigo-700 font-bold border-none shadow-none text-center"
            >
                <span class="mx-auto">Resume subscription</span>
            </jet-button>
        </template>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import JetButton from '@/Jetstream/Button';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';

export default defineComponent({
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

    computed: {
        planPrice() {
            return this.plan.monthly_price;
        },
    },

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
});
</script>

<template>
    <billing-portal-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Billing Portal: Subscriptions
            </h2>
        </template>

        <div class="space-y-6">
            <div class="grid grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <div
                    v-for="plan in plans"
                    :key="`plan-${plan.id}`"
                    class="w-full flex"
                >
                    <plan-slide
                        :plan="plan"
                        :active="currentPlan && plan.id === currentPlan.id"
                        :currentPlan="currentPlan"
                        :features="plan.features"
                        :recurring="recurring"
                        :cancelled="cancelled"
                        :onGracePeriod="onGracePeriod"
                        :endingDate="endingDate"
                        :disable="false"
                    />
                </div>
            </div>
        </div>
    </billing-portal-layout>
</template>

<script>
import BillingPortalLayout from '@/Layouts/BillingPortalLayout';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';
import PlanSlide from '@/BillingPortal/PlanSlide';

export default {
    components: {
        BillingPortalLayout,
        JetSecondaryButton,
        PlanSlide,
    },

    props: [
        'currentPlan',
        'hasDefaultPaymentMethod',
        'paymentMethods',
        'plans',
        'recurring',
        'cancelled',
        'onGracePeriod',
        'endingDate',
    ],
}
</script>

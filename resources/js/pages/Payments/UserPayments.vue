<script setup lang="ts">
defineProps({ payments: Array })
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">User Payments</h1>
    <div class="grid gap-4">
      <div
        v-for="payment in payments"
        :key="payment.id"
        class="bg-white shadow rounded-2xl p-4"
      >
        <p><strong>Type:</strong> {{ payment.payment_type }}</p>
        <p><strong>Amount:</strong> ₦{{ payment.amount }}</p>
        <p><strong>Month:</strong> {{ payment.month }}</p>
      </div>
    </div>
  </div>
</template>

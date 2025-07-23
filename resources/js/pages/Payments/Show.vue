<script setup lang="ts">
defineProps({ payment: Object })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-semibold mb-4">Payment Info</h1>
    <div class="bg-white shadow rounded-2xl p-4">
      <p><strong>Type:</strong> {{ payment.payment_type }}</p>
      <p><strong>Amount:</strong> ₦{{ payment.amount }}</p>
      <p><strong>Month:</strong> {{ payment.month }}</p>
    </div>
  </div>
</template>

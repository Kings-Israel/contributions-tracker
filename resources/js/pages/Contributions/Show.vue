<script setup lang="ts">
defineProps({ contribution: Object })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-semibold mb-4">Contribution Details</h1>
    <div class="bg-white shadow rounded-2xl p-4">
      <p><strong>Amount:</strong> ₦{{ contribution.amount }}</p>
      <p><strong>Status:</strong> {{ contribution.status }}</p>
      <p><strong>Month:</strong> {{ contribution.month }}</p>
    </div>
  </div>
</template>

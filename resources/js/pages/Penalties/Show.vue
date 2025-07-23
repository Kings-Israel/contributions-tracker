<script setup lang="ts">
defineProps({ penalty: Object })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Penalty Details</h1>
    <div class="bg-white p-4 rounded-2xl shadow">
      <p><strong>Reason:</strong> {{ penalty.reason }}</p>
      <p><strong>Amount:</strong> ₦{{ penalty.amount }}</p>
      <p><strong>Status:</strong> {{ penalty.status }}</p>
    </div>
  </div>
</template>

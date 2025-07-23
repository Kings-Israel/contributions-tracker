<script setup lang="ts">
defineProps({ contributions: Array })
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">User Contributions</h1>
    <div class="grid gap-4">
      <div
        v-for="contribution in contributions"
        :key="contribution.id"
        class="bg-white shadow rounded-2xl p-4"
      >
        <p><strong>Month:</strong> {{ contribution.month }}</p>
        <p><strong>Amount:</strong> ₦{{ contribution.amount }}</p>
        <p><strong>Status:</strong> {{ contribution.status }}</p>
      </div>
    </div>
  </div>
</template>

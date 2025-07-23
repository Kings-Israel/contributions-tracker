<script setup lang="ts">
defineProps({ vote: Object })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Vote Details</h1>
    <div class="bg-white p-4 rounded-2xl shadow">
      <p><strong>User:</strong> {{ vote.user_id }}</p>
      <p><strong>Investment:</strong> {{ vote.investment_id }}</p>
      <p><strong>Vote:</strong> {{ vote.vote_value }}</p>
    </div>
  </div>
</template>

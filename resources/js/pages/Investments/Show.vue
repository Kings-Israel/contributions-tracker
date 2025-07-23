<script setup lang="ts">
defineProps({ investment: Object })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Investment Info</h1>
    <div class="bg-white p-4 rounded-2xl shadow">
      <p><strong>Title:</strong> {{ investment.title }}</p>
      <p><strong>Description:</strong> {{ investment.description }}</p>
      <p><strong>Type:</strong> {{ investment.type }}</p>
    </div>
  </div>
</template>

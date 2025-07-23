<script setup lang="ts">
defineProps({ log: Object })
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Admin Log</h1>
    <div class="bg-white p-4 rounded-2xl shadow">
      <p><strong>Action:</strong> {{ log.action_type }}</p>
      <p><strong>Details:</strong> {{ log.details }}</p>
    </div>
  </div>
</template>

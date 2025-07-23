<script setup>
defineProps({ user: Object })
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">User Profile</h1>
    <div class="bg-white shadow rounded-2xl p-4">
      <p><strong>Name:</strong> {{ user.name }}</p>
      <p><strong>Email:</strong> {{ user.email }}</p>
      <p><strong>Role:</strong> {{ user.role }}</p>
    </div>
  </div>
</template>

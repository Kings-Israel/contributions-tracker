<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  name: '',
  email: '',
  password: '',
  role: 'user'
})

const errors = ref({})

function submit() {
  router.post('/users', form.value, {
    onError: e => (errors.value = e)
  })
}
</script>

<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Create User</h1>
    <form @submit.prevent="submit" class="space-y-4 bg-white p-6 shadow rounded-2xl">
      <div>
        <label class="block text-sm font-medium">Name</label>
        <input v-model="form.name" class="input" />
        <p class="text-red-500 text-sm" v-if="errors.name">{{ errors.name }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" v-model="form.email" class="input" />
        <p class="text-red-500 text-sm" v-if="errors.email">{{ errors.email }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Password</label>
        <input type="password" v-model="form.password" class="input" />
        <p class="text-red-500 text-sm" v-if="errors.password">{{ errors.password }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Role</label>
        <select v-model="form.role" class="input">
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
        <p class="text-red-500 text-sm" v-if="errors.role">{{ errors.role }}</p>
      </div>

      <button class="bg-blue-600 text-white py-2 px-4 rounded-2xl">Create</button>
    </form>
  </div>
</template>

<style scoped>
.input {
  @apply w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring;
}
</style>

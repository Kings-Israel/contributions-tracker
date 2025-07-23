<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  user_id: '',
  reason: '',
  amount: '',
  status: 'pending'
})

const errors = ref({})

function submit() {
  router.post('/penalties', form.value, {
    onError: e => (errors.value = e)
  })
}
</script>

<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Create Penalty</h1>
    <form @submit.prevent="submit" class="space-y-4 bg-white p-6 shadow rounded-2xl">
      <div>
        <label class="block text-sm font-medium">User ID</label>
        <input v-model="form.user_id" class="input" />
        <p v-if="errors.user_id" class="text-sm text-red-600">{{ errors.user_id }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Reason</label>
        <textarea v-model="form.reason" class="input"></textarea>
        <p v-if="errors.reason" class="text-sm text-red-600">{{ errors.reason }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Amount</label>
        <input v-model="form.amount" type="number" class="input" />
        <p v-if="errors.amount" class="text-sm text-red-600">{{ errors.amount }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Status</label>
        <select v-model="form.status" class="input">
          <option value="pending">Pending</option>
          <option value="paid">Paid</option>
        </select>
      </div>

      <button class="bg-blue-600 text-white py-2 px-4 rounded-2xl">Submit</button>
    </form>
  </div>
</template>

<style scoped>
.input {
  @apply w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring;
}
</style>

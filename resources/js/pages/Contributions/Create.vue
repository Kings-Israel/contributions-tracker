<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  user_id: '',
  amount: '',
  month: '',
  status: 'paid'
})

const errors = ref({})

function submit() {
  router.post('/contributions', form.value, {
    onError: e => (errors.value = e)
  })
}
</script>

<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">New Contribution</h1>
    <form @submit.prevent="submit" class="space-y-4 bg-white p-6 shadow rounded-2xl">
      <div>
        <label class="block text-sm font-medium">User ID</label>
        <input v-model="form.user_id" class="input" />
        <p class="text-red-500 text-sm" v-if="errors.user_id">{{ errors.user_id }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Amount</label>
        <input type="number" v-model="form.amount" class="input" />
        <p class="text-red-500 text-sm" v-if="errors.amount">{{ errors.amount }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Month</label>
        <input type="date" v-model="form.month" class="input" />
        <p class="text-red-500 text-sm" v-if="errors.month">{{ errors.month }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Status</label>
        <select v-model="form.status" class="input">
          <option value="paid">Paid</option>
          <option value="pending">Pending</option>
        </select>
        <p class="text-red-500 text-sm" v-if="errors.status">{{ errors.status }}</p>
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

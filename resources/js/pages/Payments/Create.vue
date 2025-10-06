<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    user_id: '',
    reason: '',
    amount: '',
    status: 'pending',
});

const errors = ref({});

function submit() {
    router.post('/penalties', form.value, {
        onError: (e) => (errors.value = e),
    });
}
</script>

<template>
    <div class="mx-auto max-w-xl p-6">
        <h1 class="mb-4 text-2xl font-bold">Create Penalty</h1>
        <form @submit.prevent="submit" class="space-y-4 rounded-2xl bg-white p-6 shadow">
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

            <button class="rounded-2xl bg-blue-600 px-4 py-2 text-white">Submit</button>
        </form>
    </div>
</template>

<!-- <style scoped>
.input {
  @apply w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring;
}
</style> -->

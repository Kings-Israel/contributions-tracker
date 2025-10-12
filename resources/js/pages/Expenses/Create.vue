<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    expense_type: '',
    amount: '',
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
        <h1 class="mb-4 text-2xl font-bold">Create Expense</h1>
        <form @submit.prevent="submit" class="space-y-4 rounded-2xl bg-white p-6 shadow">
            <div>
                <label class="block text-sm font-medium">Expense Type/Description</label>
                <textarea v-model="form.expense_type" class="input"></textarea>
                <p v-if="errors.expense_type" class="text-sm text-red-600">{{ errors.expense_type }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Amount</label>
                <input v-model="form.amount" type="number" class="input" />
                <p v-if="errors.amount" class="text-sm text-red-600">{{ errors.amount }}</p>
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

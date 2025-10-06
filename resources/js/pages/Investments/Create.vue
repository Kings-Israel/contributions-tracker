<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    title: '',
    description: '',
    type: 'idea',
    created_by: '',
});

const errors = ref({});

function submit() {
    router.post('/investments', form.value, {
        onError: (e) => (errors.value = e),
    });
}
</script>

<template>
    <div class="mx-auto max-w-xl p-6">
        <h1 class="mb-4 text-2xl font-bold">New Investment</h1>
        <form @submit.prevent="submit" class="space-y-4 rounded-2xl bg-white p-6 shadow">
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input v-model="form.title" class="input" />
                <p v-if="errors.title" class="text-sm text-red-600">{{ errors.title }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea v-model="form.description" class="input"></textarea>
                <p v-if="errors.description" class="text-sm text-red-600">{{ errors.description }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Type</label>
                <select v-model="form.type" class="input">
                    <option value="idea">Idea</option>
                    <option value="potential">Potential</option>
                    <option value="active">Active</option>
                </select>
                <p v-if="errors.type" class="text-sm text-red-600">{{ errors.type }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Created By (User ID)</label>
                <input v-model="form.created_by" class="input" />
                <p v-if="errors.created_by" class="text-sm text-red-600">{{ errors.created_by }}</p>
            </div>

            <button class="rounded-2xl bg-green-600 px-4 py-2 text-white">Create</button>
        </form>
    </div>
</template>

<!-- <style scoped>
.input {
  @apply w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring;
}
</style> -->

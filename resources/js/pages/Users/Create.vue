<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = ref({
    name: '',
    email: '',
    password: '',
    role: 'user',
});

const errors = ref({});

function submit() {
    router.post('/users', form.value, {
        onError: (e) => (errors.value = e),
    });
}
</script>

<template>
    <div class="mx-auto max-w-xl p-6">
        <h1 class="mb-4 text-2xl font-bold">Create User</h1>
        <form @submit.prevent="submit" class="space-y-4 rounded-2xl bg-white p-6 shadow">
            <div>
                <label class="block text-sm font-medium">Name</label>
                <input v-model="form.name" class="input" />
                <p class="text-sm text-red-500" v-if="errors.name">{{ errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" v-model="form.email" class="input" />
                <p class="text-sm text-red-500" v-if="errors.email">{{ errors.email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" v-model="form.password" class="input" />
                <p class="text-sm text-red-500" v-if="errors.password">{{ errors.password }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Role</label>
                <select v-model="form.role" class="input">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <p class="text-sm text-red-500" v-if="errors.role">{{ errors.role }}</p>
            </div>

            <button class="rounded-2xl bg-blue-600 px-4 py-2 text-white">Create</button>
        </form>
    </div>
</template>

<!-- <style scoped>
.input {
  @apply w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring;
}
</style> -->

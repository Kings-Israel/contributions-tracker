<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Logs',
        href: '/logs',
    },
];
</script>

<template>
    <Head title="Logs" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h2 class="text-center">Logs Coming Soon</h2>
        </div>
    </AppLayout>
</template>

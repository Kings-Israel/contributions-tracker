<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Events Management',
        href: '/events',
    },
];
</script>

<template>
    <Head title="Events Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h2 class="text-center">Events Management Coming Soon</h2>
        </div>
    </AppLayout>
</template>

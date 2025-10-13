<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Investments',
        href: '/investments',
    },
];
</script>

<template>
    <Head title="Investments" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h2 class="text-center">Investments Feature Coming Soon</h2>
        </div>
    </AppLayout>
</template>

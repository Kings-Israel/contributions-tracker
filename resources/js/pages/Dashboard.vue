<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { DollarSign, Origami, User } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
defineProps({users: {type: Number}, total_contributions: {type: Number}, total_investments: {type: Number}})
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="flex flex-col rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <div class="mx-auto my-auto flex flex-col">
                        <component :is="User" :size="50" class="mx-auto" />
                        <span class="mx-auto text-5xl">
                            {{ users }}
                        </span>
                        <span class="mx-auto text-xl">
                            Users
                        </span>
                    </div>
                </div>
                <div class="flex flex-col rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <div class="mx-auto my-auto flex flex-col">
                        <component :is="DollarSign" :size="50" class="mx-auto" />
                        <span class="mx-auto text-5xl">
                            {{ new Intl.NumberFormat().format(total_contributions) }}
                        </span>
                        <span class="mx-auto text-xl">
                            Total Payments
                        </span>
                    </div>
                </div>
                <div class="flex flex-col rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4">
                    <div class="mx-auto my-auto flex flex-col">
                        <component :is="Origami" :size="50" class="mx-auto" />
                        <span class="mx-auto text-5xl">
                            {{ new Intl.NumberFormat().format(total_investments) }}
                        </span>
                        <span class="mx-auto text-xl">
                            Total Expenses
                        </span>
                    </div>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>

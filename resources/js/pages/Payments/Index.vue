<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import Pagination from '@/Shared/Pagination.vue';
import { User, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';

interface Payments {
    id: number;
    user_id: number;
    user: User;
    payment_type: string;
    amount: number;
    status: string;
    month: string;
    reference_id: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Payments',
        href: '/payments',
    },
];

const payments = defineProps({ payments: Object });
</script>

<template>
    <Head title="Payments" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Table>
                <TableCaption>A list of your recent payments.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Payment Type</TableHead>
                        <TableHead>Reference No.</TableHead>
                        <TableHead>User Name</TableHead>
                        <TableHead>Phone Number</TableHead>
                        <TableHead>Date</TableHead>
                        <TableHead>Amount</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody v-if="payments.payments?.data.length > 0">
                    <TableRow v-for="payment in payments.payments?.data" :key="payment.id">
                        <TableCell>{{ payment.payment_type }}</TableCell>
                        <TableCell>{{ payment.reference_id }}</TableCell>
                        <TableCell>{{ payment.name }}</TableCell>
                        <TableCell>{{ payment.phone_number }}</TableCell>
                        <TableCell>{{ moment(payment.created_at).format('Do MMM YYYY') }}</TableCell>
                        <TableCell>{{ new Intl.NumberFormat().format(payment.amount) }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <pagination :links="payments.payments?.links" />
        </div>
    </AppLayout>
</template>

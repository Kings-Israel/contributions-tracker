<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { User, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Penalty {
    id: number;
    user_id: number;
    user: User;
    amount: number;
    status: string;
    month: string;
    reason: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Payments',
        href: '/payments',
    },
];

const penalties = defineProps({ payments: Array as () => Penalty[] });
</script>

<template>
    <Head title="Payments" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Table>
                <TableCaption>A list of your penalties.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Reason</TableHead>
                        <TableHead>Month</TableHead>
                        <TableHead className="text-right text-success">Amount</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody v-if="penalties">
                    <TableRow v-for="penalty in penalties" :key="penalty?.id">
                        <TableCell>{{ penalty?.reason }}</TableCell>
                        <TableCell>{{ penalty?.month }}</TableCell>
                        <TableCell className="text-right text-success">{{ new Intl.NumberFormat().format(penalty.amount) }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <!-- <Pagination>
                <PaginationContent>
                    <PaginationItem>
                        <PaginationPrevious href="#" />
                    </PaginationItem>
                    <PaginationItem>
                        <PaginationLink href="#">1</PaginationLink>
                    </PaginationItem>
                    <PaginationItem>
                        <PaginationEllipsis />
                    </PaginationItem>
                    <PaginationItem>
                        <PaginationNext href="#" />
                    </PaginationItem>
                </PaginationContent>
            </Pagination> -->
        </div>
    </AppLayout>
</template>

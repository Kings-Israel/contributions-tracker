<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { DollarSign, Origami } from 'lucide-vue-next';
import { User } from '@/types';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

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

defineProps({ payments: Array as () => Payments[] });
</script>

<template>
<Head title="Payments" />
<AppLayout :breadcrumbs="breadcrumbs">
    <!-- <div class="p-6">
      <div class="grid gap-4" v-if="payments && payments.length > 0">
        <div
          v-for="payment in payments"
          :key="payment.id"
          class="bg-white shadow rounded-2xl p-4"
        >
          <p><strong>User:</strong> {{ payment.user?.name }}</p>
          <p><strong>Payment Type:</strong> {{ payment.payment_type }}</p>
          <p><strong>Reference Number:</strong> {{ payment.reference_id }}</p>
          <p><strong>Month:</strong> {{ payment.month }}</p>
          <p><strong>Amount:</strong> ₦{{ payment.amount }}</p>
          <p><strong>Status:</strong> {{ payment.status }}</p>
        </div>
      </div>
    </div> -->
    <Table>
  <TableCaption>A list of your recent payments.</TableCaption>
  <TableHeader>
    <TableRow>
      <TableHead className="w-[100px]">Payment Type</TableHead>
      <TableHead>Reference No.</TableHead>
      <TableHead>Method</TableHead>
      <TableHead className="text-right">Amount</TableHead>
    </TableRow>
  </TableHeader>
  <TableBody>
    <TableRow>
      <TableCell className="font-medium">INV001</TableCell>
      <TableCell>Paid</TableCell>
      <TableCell>Credit Card</TableCell>
      <TableCell className="text-right">$250.00</TableCell>
    </TableRow>
  </TableBody>
</Table>
</AppLayout>
</template>

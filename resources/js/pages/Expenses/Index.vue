<script setup lang="ts">
import Pagination from '@/Shared/Pagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import DialogClose from '@/components/ui/dialog/DialogClose.vue';
import { Form, FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Progress } from '@/components/ui/progress';
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { User, type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { CheckCheck, LoaderCircle, X } from 'lucide-vue-next';
import moment from 'moment';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import * as z from 'zod';
const closeSheetBtn = ref();

interface Expense {
    id: number;
    user_id: number;
    user: User;
    expense_type?: string;
    amount: number;
    status: string;
    month: string;
    payment_reference_id?: string;
    approved_by?: User;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Expenses',
        href: '/expenses',
    },
];

const expenses = defineProps({ expenses: Object });

const formSchema = toTypedSchema(
    z.object({
        expense_type: z.string().min(2).max(100),
        amount: z.number().min(1),
        date: z.string().min(1),
        reference_id: z.string().optional(),
        attachment: z.any().optional(),
    }),
);

const form = useForm({
    expense_type: '',
    amount: 0,
    month: '',
    payment_reference_id: '',
    attachment: null,
});

const editForm = useForm({
    expense_id: 0,
    expense_type: '',
    amount: 0,
    month: '',
    payment_reference_id: '',
    attachment: null,
});

const submitEdit = () => {
    editForm.post('/expenses/update', {
        onSuccess: () => {
            toast.success('Expense updated successfully!');
            editForm.reset('expense_type', 'amount', 'month', 'payment_reference_id');
            closeSheetBtn.value?.click();
        },
        onError: () => {
            toast.error('Failed to update expense. Please check the form for errors.');
        },
    });
};

const editExpense = (expense: Expense) => {
    editForm.expense_id = expense.id;
    editForm.expense_type = expense.expense_type || '';
    editForm.amount = expense.amount;
    editForm.month = moment(expense.month).format('yyyy-MM-DD');
    editForm.payment_reference_id = expense.payment_reference_id || '';
    editForm.attachment = null;
};

const approveForm = useForm({
    status: 'approve',
    expense_id: 0,
});

const rejectForm = useForm({
    status: 'reject',
    expense_id: 0,
});

const resolveStatusBadge = (status: string) => {
    switch (status) {
        case 'Pending':
            return 'secondary';
        case 'Approved':
            return 'default';
        case 'Rejected':
            return 'destructive';
        default:
            return 'default';
    }
};

const uploadForm = useForm({
    expenses_file: null,
});
</script>

<template>
    <Head title="Expended" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-1 lg:flex lg:justify-between">
            <div class="hidden md:block"></div>
            <div class="mt-2 flex gap-1">
                <Dialog>
                    <DialogTrigger as-child>
                        <Button variant="outline" class=""> Upload Expenses </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[500px]">
                        <DialogHeader>
                            <DialogTitle>Upload Expenses:</DialogTitle>
                        </DialogHeader>
                        <form @submit.prevent="uploadForm.post('/expenses/upload')" method="POST" class="">
                            <Input type="file" accept=".xlsx" @input="uploadForm.expenses_file = $event.target.files[0]" />
                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button variant="outline">Cancel</Button>
                                </DialogClose>
                                <Button type="button" class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                                    <a href="/expenses/template/download"> Download Template </a>
                                </Button>
                                <Button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">Upload</Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
                <Form action="/expenses/store" as="" keep-values :validation-schema="formSchema">
                    <Sheet>
                        <SheetTrigger as-child>
                            <Button variant="outline"> Add Expense </Button>
                        </SheetTrigger>
                        <SheetContent class="space-y-2">
                            <SheetHeader>
                                <SheetTitle>Add Expense</SheetTitle>
                                <SheetDescription> Enter the details of the new expense below. </SheetDescription>
                            </SheetHeader>
                            <form
                                id="dialogForm"
                                @submit.prevent="
                                    form.post('/expenses/store', {
                                        onSuccess: () => form.reset('expense_type', 'amount', 'month', 'payment_reference_id'),
                                    })
                                "
                                class="mt-4 space-y-4"
                            >
                                <FormField v-slot="{ componentField }" name="expense_type">
                                    <FormItem>
                                        <FormLabel>Expense Type/Description</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="text"
                                                v-model="form.expense_type"
                                                placeholder="Enter the type of expense or a description"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="amount">
                                    <FormItem>
                                        <FormLabel>Amount</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="number"
                                                min="1"
                                                v-model="form.amount"
                                                placeholder="Enter the amount"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="date">
                                    <FormItem>
                                        <FormLabel>Month</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="date"
                                                v-model="form.month"
                                                placeholder="Select the date of the expense"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormDescription> Please select the date of the expense. </FormDescription>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="reference_id">
                                    <FormItem>
                                        <FormLabel>Expense Payment Receipt</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="text"
                                                v-model="form.payment_reference_id"
                                                placeholder="Enter receipt of payment"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormDescription> Enter payment of receipt if available. </FormDescription>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="attachment">
                                    <FormItem>
                                        <FormLabel>Attachment</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="file"
                                                @input="form.attachment = $event.target.files[0]"
                                                placeholder="Upload Attachment"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormDescription> Upload any relevant attachment for the expense. </FormDescription>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </form>
                            <SheetFooter>
                                <SheetClose as-child ref="closeSheetBtn"> </SheetClose>
                                <div class="flex flex-col">
                                    <Button type="submit" form="dialogForm" :disabled="form.processing">
                                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                        Add Expense
                                    </Button>
                                    <Progress v-if="form.progress" :model-value="form.progress.percentage" />
                                    <div v-if="form.wasSuccessful" class="ml-4 flex items-center">
                                        <p class="text-sm text-green-600">Expense added successfully!</p>
                                    </div>
                                </div>
                            </SheetFooter>
                        </SheetContent>
                    </Sheet>
                </Form>
            </div>
        </div>
        <div class="p-6">
            <Table>
                <TableCaption>A list of expenses.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Expense Type</TableHead>
                        <TableHead>Payment Ref.</TableHead>
                        <TableHead>Date</TableHead>
                        <TableHead>Amount</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>Created By</TableHead>
                        <TableHead>Approved By</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody v-if="expenses?.expenses.data.length > 0">
                    <TableRow v-for="expense in expenses.expenses.data" :key="expense.id">
                        <TableCell>{{ expense?.expense_type }}</TableCell>
                        <TableCell>{{ expense?.payment_reference_id }}</TableCell>
                        <TableCell>{{ moment(expense?.month).format('MMM YYYY') }}</TableCell>
                        <TableCell>{{ new Intl.NumberFormat().format(expense?.amount) }}</TableCell>
                        <TableCell>
                            <Badge :variant="resolveStatusBadge(expense?.status)">
                                {{ expense?.status }}
                            </Badge>
                        </TableCell>
                        <TableCell>{{ expense?.user.name }}</TableCell>
                        <TableCell>{{ expense?.approved_by?.name }}</TableCell>
                        <TableCell>
                            <Dialog>
                                <DialogTrigger as-child>
                                    <Button variant="ghost" size="sm">View</Button>
                                </DialogTrigger>
                                <DialogContent class="sm:max-w-[500px]">
                                    <DialogHeader>
                                        <DialogTitle>Expense Details</DialogTitle>
                                    </DialogHeader>
                                    <div class="items-center gap-4">
                                        <Label for="expense_type" class="text-right"> Expense Type/Description </Label>
                                        <span>
                                            {{ expense?.expense_type }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="amount" class="text-right"> Amount </Label>
                                        <span>
                                            {{ new Intl.NumberFormat().format(expense?.amount) }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Created By </Label>
                                        <span>
                                            {{ expense?.user.name }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Month </Label>
                                        <span>
                                            {{ moment(expense?.month).format('MMM YYYY') }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Status </Label>
                                        <span>
                                            {{ expense?.status }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4" v-if="expense.payment_reference_id">
                                        <Label for="created_by" class="text-right"> Payment Reference </Label>
                                        <span>
                                            {{ expense?.payment_reference_id }}
                                        </span>
                                    </div>
                                    <div class="items-center" v-if="expense.attachment">
                                        <Label for="created_by" class="text-right"> Attachment </Label>
                                        <Button type="button" variant="outline" size="sm" class="mt-2 text-blue-600">
                                            <a :href="expense.attachment" target="_blank" rel="noopener noreferrer"> View Attachment </a>
                                        </Button>
                                    </div>
                                </DialogContent>
                            </Dialog>
                            <Form as="" keep-values :validation-schema="formSchema">
                                <Sheet>
                                    <SheetTrigger as-child>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            class="text-yellow-600"
                                            v-if="expense?.can_edit"
                                            @click="editExpense(expense)"
                                        >
                                            Edit
                                        </Button>
                                    </SheetTrigger>
                                    <SheetContent class="space-y-2">
                                        <SheetHeader>
                                            <SheetTitle>Edit Expense</SheetTitle>
                                            <SheetDescription> Update the details of the new expense below. </SheetDescription>
                                        </SheetHeader>
                                        <form id="dialogForm" @submit.prevent="submitEdit" class="mt-4 space-y-4">
                                            <FormField name="expense_type">
                                                <FormItem>
                                                    <FormLabel>Expense Type/Description</FormLabel>
                                                    <FormControl>
                                                        <Input
                                                            type="text"
                                                            v-model="editForm.expense_type"
                                                            placeholder="Enter the type of expense or a description"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="amount">
                                                <FormItem>
                                                    <FormLabel>Amount</FormLabel>
                                                    <FormControl>
                                                        <Input
                                                            type="number"
                                                            min="1"
                                                            v-model="editForm.amount"
                                                            step=".01"
                                                            placeholder="Enter the amount"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="date">
                                                <FormItem>
                                                    <FormLabel>Month</FormLabel>
                                                    <FormControl>
                                                        <Input type="date" v-model="editForm.month" placeholder="Select the date of the expense" />
                                                    </FormControl>
                                                    <FormDescription> Please select the date of the expense. </FormDescription>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="reference_id">
                                                <FormItem>
                                                    <FormLabel>Expense Payment Receipt</FormLabel>
                                                    <FormControl>
                                                        <Input
                                                            type="text"
                                                            v-model="editForm.payment_reference_id"
                                                            placeholder="Enter receipt of payment"
                                                        />
                                                    </FormControl>
                                                    <FormDescription> Enter payment of receipt if available. </FormDescription>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="attachment">
                                                <FormItem>
                                                    <FormLabel>Attachment</FormLabel>
                                                    <FormControl>
                                                        <Input
                                                            type="file"
                                                            @input="editForm.attachment = $event.target.files[0]"
                                                            placeholder="Upload Attachment"
                                                        />
                                                    </FormControl>
                                                    <FormDescription> Upload any relevant attachment for the expense. </FormDescription>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>
                                        </form>
                                        <SheetFooter>
                                            <div class="flex flex-col">
                                                <Button type="submit" form="dialogForm" :disabled="editForm.processing">
                                                    <LoaderCircle v-if="editForm.processing" class="h-4 w-4 animate-spin" />
                                                    Update Expense
                                                </Button>
                                                <Progress v-if="editForm.progress" :model-value="editForm.progress.percentage" />
                                                <div v-if="editForm.wasSuccessful" class="ml-4 flex items-center">
                                                    <p class="text-sm text-green-600">Expense updated successfully!</p>
                                                </div>
                                            </div>
                                        </SheetFooter>
                                    </SheetContent>
                                </Sheet>
                            </Form>
                            <Dialog>
                                <DialogTrigger as-child>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        class="text-green-600"
                                        v-if="expense?.can_approve"
                                        @click="approveForm.expense_id = expense.id"
                                    >
                                        <CheckCheck class="mr-1 h-4 w-4" />
                                    </Button>
                                </DialogTrigger>
                                <DialogContent class="sm:max-w-[500px]">
                                    <DialogHeader>
                                        <DialogTitle>Approve Expense with the details:</DialogTitle>
                                    </DialogHeader>
                                    <div class="items-center gap-4">
                                        <Label for="expense_type" class="text-right"> Expense Type/Description </Label>
                                        <span>
                                            {{ expense?.expense_type }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="amount" class="text-right"> Amount </Label>
                                        <span>
                                            {{ new Intl.NumberFormat().format(expense?.amount) }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Created By </Label>
                                        <span>
                                            {{ expense?.user.name }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Month/Year </Label>
                                        <span>
                                            {{ moment(expense?.month).format('MMM YYYY') }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Status </Label>
                                        <span>
                                            {{ expense?.status }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4" v-if="expense.payment_reference_id">
                                        <Label for="created_by" class="text-right"> Payment Reference </Label>
                                        <span>
                                            {{ expense?.payment_reference_id }}
                                        </span>
                                    </div>
                                    <DialogFooter>
                                        <DialogClose as-child>
                                            <Button variant="outline">Close</Button>
                                        </DialogClose>
                                        <form
                                            v-if="expense?.status == 'Pending'"
                                            @submit.prevent="approveForm.post('/expenses/status/update')"
                                            method="POST"
                                            class=""
                                        >
                                            <input type="text" class="hidden" v-model="approveForm.status" />
                                            <input type="number" class="hidden" v-model="approveForm.expense_id" />
                                            <Button type="submit" class="bg-green-600 hover:bg-green-700 focus:ring-green-500">Approve</Button>
                                        </form>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>
                            <Dialog>
                                <DialogTrigger as-child>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        class="text-red-600"
                                        v-if="expense?.can_approve"
                                        @click="rejectForm.expense_id = expense.id"
                                    >
                                        <X class="mr-1 h-4 w-4" />
                                    </Button>
                                </DialogTrigger>
                                <DialogContent class="sm:max-w-[500px]">
                                    <DialogHeader>
                                        <DialogTitle>Reject Expense with the details:</DialogTitle>
                                    </DialogHeader>
                                    <div class="items-center gap-4">
                                        <Label for="expense_type" class="text-right"> Expense Type/Description </Label>
                                        <span>
                                            {{ expense?.expense_type }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="amount" class="text-right"> Amount </Label>
                                        <span>
                                            {{ new Intl.NumberFormat().format(expense?.amount) }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Created By </Label>
                                        <span>
                                            {{ expense?.user.name }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Month </Label>
                                        <span>
                                            {{ moment(expense?.month).format('MMM YYYY') }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Status </Label>
                                        <span>
                                            {{ expense?.status }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4" v-if="expense.payment_reference_id">
                                        <Label for="created_by" class="text-right"> Payment Reference </Label>
                                        <span>
                                            {{ expense?.payment_reference_id }}
                                        </span>
                                    </div>
                                    <DialogFooter>
                                        <DialogClose as-child>
                                            <Button variant="outline">Close</Button>
                                        </DialogClose>
                                        <form
                                            v-if="expense?.status == 'Pending'"
                                            @submit.prevent="rejectForm.post('/expenses/status/update')"
                                            method="POST"
                                            class=""
                                        >
                                            <input type="text" class="hidden" v-model="rejectForm.status" />
                                            <input type="number" class="hidden" v-model="rejectForm.expense_id" />
                                            <Button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">Reject</Button>
                                        </form>
                                    </DialogFooter>
                                </DialogContent>
                            </Dialog>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <pagination :links="expenses.expenses.links" />
        </div>
    </AppLayout>
</template>

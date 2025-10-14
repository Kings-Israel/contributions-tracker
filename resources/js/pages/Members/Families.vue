<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form } from '@/components/ui/form';
import { Sheet, SheetContent, SheetDescription, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Table, TableCaption, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { ref } from 'vue';
const closeSheetBtn = ref();
</script>

<template>
    <div class="mx-1 lg:flex lg:justify-between">
        <div class="hidden md:block"></div>
        <div class="mt-1 flex gap-1">
            <Form action="/expenses/store" as="" keep-values>
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="outline"> Add Family </Button>
                    </SheetTrigger>
                    <SheetContent class="space-y-2">
                        <SheetHeader>
                            <SheetTitle>Add Family</SheetTitle>
                            <SheetDescription> Enter the new family details below. </SheetDescription>
                        </SheetHeader>
                        <!-- <form
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
                                        <Input type="number" min="1" v-model="form.amount" placeholder="Enter the amount" v-bind="componentField" />
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
                        </SheetFooter> -->
                    </SheetContent>
                </Sheet>
            </Form>
        </div>
    </div>
    <div class="py-2">
        <Table>
            <TableCaption>A list of families.</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Location</TableHead>
                    <TableHead>Member Count</TableHead>
                    <TableHead>Created At</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
        </Table>
        <!-- <pagination :links="expenses.expenses.links" /> -->
    </div>
</template>

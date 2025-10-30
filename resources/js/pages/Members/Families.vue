<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Progress } from '@/components/ui/progress';
import { Sheet, SheetClose, SheetContent, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Pagination from '@/Shared/Pagination.vue';
import { useForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { LoaderCircle } from 'lucide-vue-next';
import moment from 'moment';
import { ref } from 'vue';
import * as z from 'zod';

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(100),
    }),
);

const form = useForm({
    name: '',
});

const storeFamily = () => {
    form.post('/families/store');
};

const closeSheetBtn = ref();

const { families } = defineProps({ families: Object });
</script>

<template>
    <div class="mx-1 lg:flex lg:justify-between">
        <div class="hidden md:block"></div>
        <div class="mt-1 flex gap-1">
            <Form action="/families/store" as="" :validation-schema="formSchema">
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="outline"> Add Family </Button>
                    </SheetTrigger>
                    <SheetContent class="space-y-2">
                        <SheetHeader>
                            <SheetTitle>Add Family</SheetTitle>
                        </SheetHeader>
                        <form id="dialogForm" @submit.prevent="storeFamily" class="mt-4 space-y-4">
                            <FormField v-slot="{ componentField }" name="name">
                                <FormItem>
                                    <FormLabel>Family Name</FormLabel>
                                    <FormControl>
                                        <Input type="text" v-model="form.name" placeholder="Enter the family's name" v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                        </form>
                        <SheetFooter>
                            <SheetClose as-child ref="closeSheetBtn"> </SheetClose>
                            <div class="flex flex-col">
                                <Button type="submit" form="dialogForm" :disabled="form.processing">
                                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                                    Add Family
                                </Button>
                                <Progress v-if="form.progress" :model-value="form.progress.percentage" />
                                <div v-if="form.wasSuccessful" class="ml-4 flex items-center">
                                    <p class="text-sm text-green-600">Family added successfully!</p>
                                </div>
                            </div>
                        </SheetFooter>
                    </SheetContent>
                </Sheet>
            </Form>
        </div>
    </div>
    <div class="py-2">
        <Table>
            <TableCaption>A list of Family.</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Member Count</TableHead>
                    <TableHead>Created At</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody v-if="families?.data.length > 0">
                <TableRow v-for="family in families?.data" :key="family.id">
                    <TableCell>{{ family?.name }}</TableCell>
                    <TableCell>{{ family?.members_count }}</TableCell>
                    <TableCell>{{ moment(family.created_at).format('DD MMM YYYY') }}</TableCell>
                    <TableCell>View</TableCell>
                </TableRow>
            </TableBody>
        </Table>
        <pagination :links="families?.links" />
    </div>
</template>

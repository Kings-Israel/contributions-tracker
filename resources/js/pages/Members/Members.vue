<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Progress } from '@/components/ui/progress';
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Table, TableCaption, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';
import * as z from 'zod';

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(100),
        email: z.string().min(1),
        phone_number: z.string().min(1),
        age: z.string().optional(),
        gender: z.string().optional(),
    }),
);

const form = useForm({
    name: '',
    email: '',
    phone_number: '',
    age: '',
    gender: '',
});

const addUser = () => {
    console.log('user added');
    form.post('/expenses/store');
};

const closeSheetBtn = ref();
</script>

<template>
    <div class="mx-1 lg:flex lg:justify-between">
        <div class="hidden md:block"></div>
        <div class="mt-1 flex gap-1">
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="outline" class=""> Upload Members </Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-[500px]">
                    <DialogHeader>
                        <DialogTitle>Upload Members:</DialogTitle>
                    </DialogHeader>
                    <!-- <form @submit.prevent="uploadForm.post('/expenses/upload')" method="POST" class="">
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
                    </form> -->
                </DialogContent>
            </Dialog>
            <Form action="/members/store" as="">
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="outline"> Add Member </Button>
                    </SheetTrigger>
                    <SheetContent class="space-y-2">
                        <SheetHeader>
                            <SheetTitle>Add Member</SheetTitle>
                            <SheetDescription> Enter the details of the new member below. </SheetDescription>
                        </SheetHeader>
                        <form id="dialogForm" @submit.prevent="addUser" class="mt-4 space-y-4">
                            <FormField v-slot="{ componentField }" name="name">
                                <FormItem>
                                    <FormLabel>Member Name</FormLabel>
                                    <FormControl>
                                        <Input type="text" v-model="form.name" placeholder="Enter the member's name" v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField v-slot="{ componentField }" name="email">
                                <FormItem>
                                    <FormLabel>Email</FormLabel>
                                    <FormControl>
                                        <Input type="email" v-model="form.email" placeholder="Enter the email" v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField v-slot="{ componentField }" name="phone_number">
                                <FormItem>
                                    <FormLabel>Phone Number</FormLabel>
                                    <FormControl>
                                        <Input
                                            type="text"
                                            v-model="form.phone_number"
                                            placeholder="Enter the member's phone number"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField v-slot="{ componentField }" name="age">
                                <FormItem>
                                    <FormLabel>Age</FormLabel>
                                    <FormControl>
                                        <Input type="number" v-model="form.age" placeholder="Enter age" v-bind="componentField" />
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
                                    Add Member
                                </Button>
                                <Progress v-if="form.progress" :model-value="form.progress.percentage" />
                                <div v-if="form.wasSuccessful" class="ml-4 flex items-center">
                                    <p class="text-sm text-green-600">Member added successfully!</p>
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
            <TableCaption>A list of members.</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Email</TableHead>
                    <TableHead>Phone Number</TableHead>
                    <TableHead>Age</TableHead>
                    <TableHead>Gender</TableHead>
                    <TableHead>Created At</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
        </Table>
        <!-- <pagination :links="expenses.expenses.links" /> -->
    </div>
</template>

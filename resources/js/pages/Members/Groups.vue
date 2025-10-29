<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Progress } from '@/components/ui/progress';
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import { useForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { LoaderCircle } from 'lucide-vue-next';
import Pagination from '@/Shared/Pagination.vue';
import { ref } from 'vue';
import * as z from 'zod';
import moment from 'moment';

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(100),
        tags: z.array(z.string()).optional(),
    }),
);

const form = useForm({
    name: '',
    tags: ['Youth'],
});

const storeGroup = () => {
    form.post('/groups/store');
};

const closeSheetBtn = ref();

const { groups } = defineProps({ groups: Object })
</script>

<template>
    <div class="mx-1 lg:flex lg:justify-between">
        <div class="hidden md:block"></div>
        <div class="mt-1 flex gap-1">
            <Form action="/groups/store" as="" :validation-schema="formSchema">
                <Sheet>
                    <SheetTrigger as-child>
                        <Button variant="outline"> Add Group </Button>
                    </SheetTrigger>
                    <SheetContent class="space-y-2">
                        <SheetHeader>
                            <SheetTitle>Add Group</SheetTitle>
                            <SheetDescription> Enter the details of the new group below. </SheetDescription>
                        </SheetHeader>
                        <form id="dialogForm" @submit.prevent="storeGroup" class="mt-4 space-y-4">
                            <FormField v-slot="{ componentField }" name="name">
                                <FormItem>
                                    <FormLabel>Group Name</FormLabel>
                                    <FormControl>
                                        <Input type="text" v-model="form.name" placeholder="Enter the group's name" v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField name="email">
                                <FormItem>
                                    <FormLabel>Group Tags</FormLabel>
                                    <FormControl>
                                        <!-- <Input type="email" v-model="form.tags" placeholder="Enter the email" v-bind="componentField" /> -->
                                        <TagsInput v-model="form.tags">
                                            <TagsInputItem v-for="item in form.tags" :key="item" :value="item">
                                                <TagsInputItemText />
                                                <TagsInputItemDelete />
                                            </TagsInputItem>

                                            <TagsInputInput placeholder="Group Tags..." />
                                        </TagsInput>
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
                                    Add Group
                                </Button>
                                <Progress v-if="form.progress" :model-value="form.progress.percentage" />
                                <div v-if="form.wasSuccessful" class="ml-4 flex items-center">
                                    <p class="text-sm text-green-600">Group added successfully!</p>
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
            <TableCaption>A list of groups.</TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Tags</TableHead>
                    <TableHead>Member Count</TableHead>
                    <TableHead>Created At</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody v-if="groups?.data.length > 0">
                <TableRow v-for="group in groups?.data" :key="group.id">
                    <TableCell>{{ group?.name }}</TableCell>
                    <TableCell>
                        <div class="flex gap-1">
                            <span v-for="(tag, key) in group?.tags" :key="key">
                                <span v-if="key === group?.tags.length - 1">
                                    {{ tag }}
                                </span>
                                <span v-else>
                                    {{ tag }},
                                </span>
                            </span>
                        </div>
                    </TableCell>
                    <TableCell>{{ group?.members_count }}</TableCell>
                    <TableCell>{{ moment(group.created_at).format('DD MMM YYYY') }}</TableCell>
                    <TableCell>View</TableCell>
                </TableRow>
            </TableBody>
        </Table>
        <pagination :links="groups?.links" />
    </div>
</template>

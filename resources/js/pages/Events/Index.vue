<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Form, FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Progress } from '@/components/ui/progress';
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import Pagination from '@/Shared/Pagination.vue';
import { User, type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { LoaderCircle } from 'lucide-vue-next';
import moment from 'moment';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import * as z from 'zod';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Events Management',
        href: '/events',
    },
];

const closeSheetBtn = ref();

interface Event {
    id: number;
    created_by: number;
    user: User;
    name?: string;
    location: string;
    date: string;
    theme: string;
    description?: string;
    budget?: number;
}

const events = defineProps({ events: Object });

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(100),
        location: z.string().min(1),
        date: z.string().min(1),
        theme: z.string().optional(),
        description: z.any().optional(),
        budget: z.number().optional(),
    }),
);

const form = useForm({
    name: '',
    location: '',
    date: '',
    theme: '',
    description: '',
    budget: 0,
});

const editForm = useForm({
    event_id: 0,
    name: '',
    location: '',
    date: '',
    theme: '',
    description: '',
    budget: 0,
});

const submitEdit = () => {
    editForm.post('/events/update', {
        onSuccess: () => {
            toast.success('Event updated successfully!');
            editForm.reset('name', 'location', 'date', 'theme', 'description', 'budget');
            closeSheetBtn.value?.click();
        },
        onError: () => {
            toast.error('Failed to update event. Please check the form for errors.');
        },
    });
};

const editEvent = (event: Event) => {
    editForm.event_id = event.id;
    editForm.name = event.name;
    editForm.location = event.location;
    editForm.date = moment(event.date).format('yyyy-MM-DD');
    editForm.theme = event.theme || '';
    editForm.description = event.description || '';
    editForm.budget = event.budget || 0;
};
</script>

<template>
    <Head title="Events Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-1 lg:flex lg:justify-between">
            <div class="hidden md:block"></div>
            <div class="mt-2 flex gap-1">
                <Form action="/events/store" as="" keep-values :validation-schema="formSchema">
                    <Sheet>
                        <SheetTrigger as-child>
                            <Button variant="outline"> Add Event </Button>
                        </SheetTrigger>
                        <SheetContent class="space-y-2">
                            <SheetHeader>
                                <SheetTitle>Add Event</SheetTitle>
                                <SheetDescription> Enter the details of the new event below. </SheetDescription>
                            </SheetHeader>
                            <form
                                id="dialogForm"
                                @submit.prevent="
                                    form.post('/events/store', {
                                        onSuccess: () => form.reset('name', 'location', 'date', 'theme', 'description', 'budget'),
                                    })
                                "
                                class="mt-4 space-y-4"
                            >
                                <FormField v-slot="{ componentField }" name="name">
                                    <FormItem>
                                        <FormLabel>Name</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="text"
                                                v-model="form.name"
                                                placeholder="Enter the name of the event"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="location">
                                    <FormItem>
                                        <FormLabel>Location</FormLabel>
                                        <FormControl>
                                            <Input type="text" v-model="form.location" placeholder="Enter the location" v-bind="componentField" />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="date">
                                    <FormItem>
                                        <FormLabel>Date</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="date"
                                                v-model="form.date"
                                                placeholder="Select the date of the event"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormDescription> Please select the date of the event. </FormDescription>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="theme">
                                    <FormItem>
                                        <FormLabel>Theme</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="text"
                                                v-model="form.theme"
                                                placeholder="Enter the theme of the event"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormDescription> Enter event's theme. </FormDescription>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="description">
                                    <FormItem>
                                        <FormLabel>Description</FormLabel>
                                        <FormControl>
                                            <Textarea
                                                type="text"
                                                v-model="form.description"
                                                placeholder="Enter a description for the event"
                                                v-bind="componentField"
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>

                                <FormField v-slot="{ componentField }" name="budget">
                                    <FormItem>
                                        <FormLabel>Budget</FormLabel>
                                        <FormControl>
                                            <Input
                                                type="number"
                                                v-model="form.budget"
                                                placeholder="Enter the budget of the event"
                                                v-bind="componentField"
                                            />
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
                                        Add Event
                                    </Button>
                                    <Progress v-if="form.progress" :model-value="form.progress.percentage" />
                                    <div v-if="form.wasSuccessful" class="ml-4 flex items-center">
                                        <p class="text-sm text-green-600">Event added successfully!</p>
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
                <TableCaption>A list of Events.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Location.</TableHead>
                        <TableHead>Date</TableHead>
                        <TableHead>Theme</TableHead>
                        <TableHead>Budget</TableHead>
                        <TableHead>Created By</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody v-if="events?.events.data.length > 0">
                    <TableRow v-for="event in events.events.data" :key="event.id">
                        <TableCell>{{ event?.name }}</TableCell>
                        <TableCell>{{ event?.location }}</TableCell>
                        <TableCell>{{ moment(event?.date).format('DD MMM YYYY') }}</TableCell>
                        <TableCell>{{ event?.theme }}</TableCell>
                        <TableCell>{{ new Intl.NumberFormat().format(event?.budget) }}</TableCell>
                        <TableCell>{{ event?.user?.name }}</TableCell>
                        <TableCell>
                            <Dialog>
                                <DialogTrigger as-child>
                                    <Button variant="ghost" size="sm">View</Button>
                                </DialogTrigger>
                                <DialogContent class="sm:max-w-[500px]">
                                    <DialogHeader>
                                        <DialogTitle>Event Details</DialogTitle>
                                    </DialogHeader>
                                    <div class="items-center gap-4">
                                        <Label for="name" class="text-right"> Name </Label>
                                        <span>
                                            {{ event?.name }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="location" class="text-right"> Location </Label>
                                        <span>
                                            {{ event?.location }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="date" class="text-right"> Date </Label>
                                        <span>
                                            {{ moment(event?.date).format('DD MMM YYYY') }}
                                        </span>
                                    </div>
                                    <div class="items-center gap-4">
                                        <Label for="created_by" class="text-right"> Created By </Label>
                                        <span>
                                            {{ event?.user.name }}
                                        </span>
                                    </div>
                                </DialogContent>
                            </Dialog>
                            <Form as="" keep-values :validation-schema="formSchema">
                                <Sheet>
                                    <SheetTrigger as-child>
                                        <Button variant="ghost" size="sm" class="text-yellow-600" @click="editEvent(event)"> Edit </Button>
                                    </SheetTrigger>
                                    <SheetContent class="space-y-2">
                                        <SheetHeader>
                                            <SheetTitle>Edit Event</SheetTitle>
                                            <SheetDescription> Update the details of the event below. </SheetDescription>
                                        </SheetHeader>
                                        <form id="dialogForm" @submit.prevent="submitEdit" class="mt-4 space-y-4">
                                            <FormField name="name">
                                                <FormItem>
                                                    <FormLabel>Name</FormLabel>
                                                    <FormControl>
                                                        <Input type="text" v-model="editForm.name" placeholder="Enter the event's name" />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="location">
                                                <FormItem>
                                                    <FormLabel>Location</FormLabel>
                                                    <FormControl>
                                                        <Input type="text" v-model="editForm.location" placeholder="Enter the event's location" />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="theme">
                                                <FormItem>
                                                    <FormLabel>Theme</FormLabel>
                                                    <FormControl>
                                                        <Input type="text" v-model="editForm.theme" placeholder="Enter the event's theme" />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="description">
                                                <FormItem>
                                                    <FormLabel>Description</FormLabel>
                                                    <FormControl>
                                                        <Textarea v-model="editForm.description" placeholder="Enter the event's description" />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="date">
                                                <FormItem>
                                                    <FormLabel>Date</FormLabel>
                                                    <FormControl>
                                                        <Input type="date" v-model="editForm.date" placeholder="Select the date of the event" />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField name="amount">
                                                <FormItem>
                                                    <FormLabel>Budget</FormLabel>
                                                    <FormControl>
                                                        <Input
                                                            type="number"
                                                            min="0"
                                                            v-model="editForm.budget"
                                                            step=".01"
                                                            placeholder="Enter the event's budget"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>
                                        </form>
                                        <SheetFooter>
                                            <div class="flex flex-col">
                                                <Button type="submit" form="dialogForm" :disabled="editForm.processing">
                                                    <LoaderCircle v-if="editForm.processing" class="h-4 w-4 animate-spin" />
                                                    Update Event
                                                </Button>
                                                <Progress v-if="editForm.progress" :model-value="editForm.progress.percentage" />
                                                <div v-if="editForm.wasSuccessful" class="ml-4 flex items-center">
                                                    <p class="text-sm text-green-600">Event updated successfully!</p>
                                                </div>
                                            </div>
                                        </SheetFooter>
                                    </SheetContent>
                                </Sheet>
                            </Form>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
            <pagination :links="events.events?.links" />
        </div>
    </AppLayout>
</template>

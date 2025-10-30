<script setup lang="ts">
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Families from './Families.vue';
import Groups from './Groups.vue';
import Members from './Members.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Membership Management',
        href: '/members',
    },
];

const data = defineProps({ members: Object, groups: Object, families: Object, all_groups: Object, all_families: Object });
</script>

<template>
    <Head title="Membership Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <Tabs default-value="membership">
                <TabsList>
                    <TabsTrigger value="membership"> Membership Management </TabsTrigger>
                    <TabsTrigger value="groups"> Groups Management </TabsTrigger>
                    <TabsTrigger value="families"> Families Management </TabsTrigger>
                </TabsList>
                <TabsContent value="membership">
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2">
                        <Members :members="data?.members" :groups="data?.all_groups" :families="data?.all_families" />
                    </div>
                </TabsContent>
                <TabsContent value="groups">
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2">
                        <Groups :groups="data?.groups" />
                    </div>
                </TabsContent>
                <TabsContent value="families">
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2">
                        <Families :families="data?.families" />
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>

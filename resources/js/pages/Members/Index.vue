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

const tabs = [
    { key: 'membership_management', label: 'Membership Management', component: 'Members' },
    { key: 'groups_management', label: 'Groups Management', component: 'Groups' },
    { key: 'families_management', label: 'Families Management', component: 'Families' },
];
</script>

<template>
    <Head title="Membership Management" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <Tabs default-value="membership_management">
                <TabsList>
                    <TabsTrigger v-for="tab in tabs" :key="tab.key" :value="tab.key"> {{ tab.label }} </TabsTrigger>
                </TabsList>
                <TabsContent v-for="tab in tabs" :key="tab.key" :value="tab.key">
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2" v-if="tab.component == 'Members'">
                        <Members />
                    </div>
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2" v-if="tab.component == 'Groups'">
                        <Groups />
                    </div>
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2" v-if="tab.component == 'Families'">
                        <Families />
                    </div>
                </TabsContent>
                <!-- <TabsContent value="groups">
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2">
                        <Groups />
                    </div>
                </TabsContent>
                <TabsContent value="families">
                    <div class="border-[hsl(0 0% 63.9%)] rounded-lg border-2">
                        <Families />
                    </div>
                </TabsContent> -->
            </Tabs>
        </div>
    </AppLayout>
</template>

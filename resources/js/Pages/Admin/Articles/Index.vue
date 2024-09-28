<template>
    <AppLayout title="Article">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customer
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                     <!-- Flash Messages -->
                    <flash-messages />
                    <!-- Template -->
                    <div>
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <!-- Tabs (All, Archived, Activity Logs) -->
                            <div class="flex justify-between items-center">
                                <div class="space-x-4">
                                    <!-- Tabs -->
                                    <nav-tabs
                                        v-if="!noTabs"
                                        :tabs="tabs"
                                        :button-items="true"
                                        :active-tab="pageFilters.tab"
                                        @update:tab="(value) => (pageFilters.tab = value)"
                                        :tab-route="route('admin.article.index')"
                                    />
                                </div>
                                <div class="flex space-x-4">
                                    <button class="bg-gray-200 py-2 px-4 rounded">Import</button>
                                    <button class="bg-gray-200 py-2 px-4 rounded">Export</button>
                                    <Link :href="route('admin.article.create')" class="bg-black text-white py-2 px-4 rounded">+ Create</Link>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <template v-if="tab != 'activity_logs'">
                            <table-container>
                                <template #header>

                                </template>
                                <template #body>
                                    <DataTable
                                    :headers="headers"
                                    :no-action="true"
                                    :count="items.data.length"
                                    :is-empty="items.data.length === 0"
                                >
                                    <template v-slot:body>
                                        <template v-for="item in items.data" :key="item.id">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ item.title }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ item.content }}
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 text-center">
                                                    <template
                                                        v-if="!['activity_logs', 'archived'].includes(tab)"
                                                    >
                                                    <edit-button
                                                        :route-link="
                                                            route('admin.article.edit', item.id)
                                                        "
                                                        class="mr-2.5"
                                                    />
                                                    <delete-button
                                                        :modal-title="`Archive ${resourceName}`"
                                                        :modal-name="item.name"
                                                        :route-link="
                                                            route(
                                                                'admin.article.archive',
                                                                item.id
                                                            )
                                                        "
                                                    />
                                                    </template>

                                                    <restore-button
                                                        v-if="tab === 'archived'"
                                                        :modal-title="`Restore ${moduleName}`"
                                                        :modal-name="item.name"
                                                        :route-link="
                                                            route('admin.article.restore', item.id)
                                                        "
                                                    />
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                </DataTable>
                                </template>
                                <template #footer>
                                    <paginator class="mb-4" :items="items" />
                                </template>
                            </table-container>
                        </template>
                         <!-- Activity Logs -->
                        <activity-logs
                            v-if="pageFilters.tab === 'activity_logs'"
                            :logs="items"
                            :result-route="route('admin.article.index')"
                            tab="activity_logs"
                            :rows="10"
                        />
                    </div>
                    <!-- Template -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from "@inertiajs/vue3";
import { PencilSquareIcon, TrashIcon, ArrowPathIcon } from '@heroicons/vue/24/outline'
import {ref, computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import BaseIndexProps from '../../../Contracts/Admin/BaseIndexProps';
import NavTabs from '../../../Components/NavTabs.vue';
import ActivityLogs from '../../../Components/ActivityLogs.vue';
import TableContainer from '../../../Components/Containers/TableContainer.vue';
import DataTable from '../../../Components/DataTable.vue';
import Paginator from '../../../Components/Partials/Paginator.vue';
import FlashMessages from '../../../Components/Modals/FlashMessages.vue';
import { router } from "@inertiajs/vue3";
import { route } from '../../../../../vendor/tightenco/ziggy/src/js';
import EditButton from '../../../Components/ActionButtons/EditButton.vue';
import DeleteButton from '../../../Components/ActionButtons/DeleteButton.vue';
import RestoreButton from '../../../Components/ActionButtons/RestoreButton.vue';

const baseProps = computed(() => usePage().props);

const props = defineProps({
    ...BaseIndexProps,
    tabs: {
        type: Array,
        default: [],
    }
});

const pageFilters = ref(
    Object.assign({
        tab: baseProps.value.tab,
    })
);

const tabs = computed(() =>
    props.tabs.length
        ? props.tabs
        : [
              {
                  name: "All",
                  value: null,
                  count: baseProps.value.counts.activeCount,
              },
              {
                  name: "Archived",
                  value: "archived",
                  count: baseProps.value.counts.archivedCount,
              },
              {
                  name: "Activity Logs",
                  value: "activity_logs",
                  count: baseProps.value.counts.activityCount,
              },
          ]
);

/*--------------*
 * CONSTANTS
 *--------------*/
 const headers = [
    { text: "Title", value: "title" },
    { text: "Content", value: "content" },
    { text: "Action", value: "action" },
];


const archive = ( id ) => {
    router.delete(route('admin.article.archive', id));
}

const restore = ( id ) => {
    router.post(route('admin.article.restore', id));
}
</script>

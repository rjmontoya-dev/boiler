<template>
    <div class="mt-4">
        <table-container :count="logs.data.length">
            <template #header>

            </template>
            <template #body>
                <DataTable
                    :headers="headers"
                    :no-action="true"
                    :count="logs.data.length"
                    :is-empty="logs.data.length === 0"
                >
                    <template v-slot:body>
                        <template v-for="log in logs.data" :key="log.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ log.id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ log.subject_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ log.event }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ log.description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ log.causer_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ log.created_at }}
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                </DataTable>
            </template>
            <template #footer>
                <paginator class="mb-4" :items="logs" />
            </template>
        </table-container>
    </div>
</template>
<script lang="ts" setup>
import debounce from "lodash/debounce";
import pickBy from "lodash/pickBy";
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import DataTable from "./DataTable.vue";
import TableContainer from "./Containers/TableContainer.vue";
import Paginator from "./Partials/Paginator.vue";

const props = defineProps({
    logs: {
        type: Object,
        required: true,
    },
    resultRoute: {
        type: String,
        default: "",
    },
    tab: {
        type: String,
        default: "",
    },
    query: {
        type: String,
        default: "",
    },
    event: {
        type: String,
        default: "",
    },
    date: {
        type: String,
        default: "",
    },
    subject: {
        type: String,
        default: "",
    },
    rows: {
        type: Number,
        default: 10,
    },
    rowParam: {
        type: String,
        default: "rows",
    },
    noAction: {
        type: Boolean,
        default: true,
    },
    dateFilter: {
        type: Boolean,
        default: false,
    },
    subjectFilter: {
        type: Boolean,
        default: false,
    },
    subjects: {
        type: Array,
        default: [],
    },
    sort: {
        type: String,
        default: "id",
    },
    order: {
        type: String,
        default: "asc",
    },
});

/*--------------*
 * VARS
 *--------------*/
const searchText = ref<string>(props.query);
const filterEvent = ref<string>(props.event);
const filterDate = ref<string>(props.date);
const filterSubject = ref<string>(props.subject);
const filterSort = ref<string>(props.sort);
const filterOrder = ref<string>(props.order);

/*--------------*
 * CONSTANTS
 *--------------*/
const headers: { text: string; value: string; width?: string }[] = [
    { text: "ID", value: "id" },
    { text: "Subject", value: "subject" },
    { text: "Event", value: "event" },
    { text: "Description", value: "description", width: "40%" },
    { text: "Caused By", value: "causedBy" },
    { text: "Date Created", value: "dateCreated" },
];

const events: { id: string; value: string }[] = [
    { id: "created", value: "Created" },
    { id: "updated", value: "Updated" },
    { id: "deleted", value: "Deleted" },
    { id: "restored", value: "Restored" },
    { id: "mail", value: "Mail" },
    { id: "notification", value: "Notification" },
];

const sortOptions = [
    { id: "id", value: "ID" },
    { id: "subject", value: "Subject" },
    { id: "event", value: "Event" },
    { id: "description", value: "Description" },
    { id: "created_at", value: "Created At" },
]

/*--------------*
 * METHODS
 *--------------*/

const applySort = (value:{ sort: string, order: string }) => {
    filterSort.value = value.sort
    filterOrder.value = value.order
    getData();
};

const getData: Function = (search = null) => {
    router.get(
        props.resultRoute,
        pickBy({
            event: filterEvent.value,
            query: search ?? searchText.value,
            date: filterDate.value,
            subject: filterSubject.value,
            tab: props.tab,
            sort: filterSort.value,
            order: filterOrder.value,
        }),
        {
            preserveState: true,
        }
    );
};

/*--------------*
 * WATCHERS
 *--------------*/
watch(
    searchText,
    debounce((val) => {
        getData(val);
    }, 500)
);
</script>

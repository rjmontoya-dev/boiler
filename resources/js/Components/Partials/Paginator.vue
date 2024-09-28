<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <div v-if="items.meta.total > 10">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <selector
                    :options="options"
                    :modelValue="rows"
                    @update:modelValue="updatePagination"
                    id="pagination-id"
                    name="pagination-name"
                    class="w-[auto] mr-4"
                    placeholder="10 Rows"
                    topOptions
                />

                <p class="text-sm text-gray-500">
                    Showing
                    {{ " " }}
                    <span class="text-gray-900">{{ items.meta.from }}</span>
                    {{ " " }}
                    to
                    {{ " " }}
                    <span class="text-gray-900">{{ items.meta.to }}</span>
                    {{ " " }}
                    of
                    {{ " " }}
                    <span class="text-gray-900">{{ items.meta.total }}</span>
                    {{ " " }}
                    entries
                </p>
            </div>
            <div class="relative flex -space-x-px">
                <template v-for="(link, key) in items.meta.links" :key="link">
                    <template v-if="key === 0">
                        <Link
                            v-if="key === 0"
                            :href="link.url ?? '#'"
                            class="relative inline-flex items-center justify-center w-11 h-11 border border-gray-100 bg-white text-gray-500 hover:bg-gray-50 rounded-l-lg"
                            >
                            <span class="sr-only">Previous</span>
                            <ChevronLeftIcon class="h-4 w-4" aria-hidden="true" />
                        </Link>
                    </template>

                    <template v-else-if="key === items.meta.links.length - 1">
                        <Link
                            :href="link.url ?? '#'"
                            class="relative inline-flex items-center justify-center w-11 h-11 border border-gray-100 bg-white text-gray-500 hover:bg-gray-50 rounded-r-lg"
                        >
                            <span class="sr-only">Next</span>
                            <ChevronRightIcon class="h-4 w-4" aria-hidden="true" />
                        </Link>
                    </template>

                    <Link
                        v-else
                        :href="link.url ?? '#'"
                        aria-current="page"
                        class="relative inline-flex items-center justify-center border border-gray-100 w-11 py-2 text-sm"
                        :class="
                            link.active
                                ? 'bg-gray-100 text-primary-500'
                                : 'bg-white text-gray-500'
                        "
                    >
                        {{ link.label }}
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

import {
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps({
    items: {
        type: Object,
    },
    rows: {
        type: Number,
        default: 10,
    },
})

const rows = ref(props.rows);

const options = [
    {
        id: 5,
        value: "5 Rows",
    },
    {
        id: 25,
        value: "25 Rows",
    },
    {
        id: 50,
        value: "50 Rows",
    },
    {
        id: 100,
        value: "100 Rows",
    },
];

const updatePagination = (value) => {
    const params = route().params;
    const data = params;

    if (value) {
        data.per_page = value;
    } else {
        delete params.per_page;
    }

    if (params.page) {
        delete params.page;
    }

    router.replace(route(route().current(), params), {
        data: data,
        replace: false,
        preserveState: true,
        preserveScroll: true,
    });

    rows.value = value;
};
</script>

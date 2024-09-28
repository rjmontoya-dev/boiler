<template>
    <a
        :href="routeLink"
        class="
            mx-1
            inline-flex
            items-center
            p-1
            rounded-xl
            hover:text-gray-700
            active:text-gray-700
            focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500
        "
    >
        <PencilSquareIcon class="p-0.5 h-6 w-6 text-gray-500" aria-hidden="true" />
    </a>
</template>

<script setup lang="ts">
import { PencilSquareIcon } from "@heroicons/vue/24/outline";
defineProps({
    routeLink: {
        type: String,
    },
});
</script>

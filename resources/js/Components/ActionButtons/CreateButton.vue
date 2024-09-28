<template>
    <Link :href="routeLink">
        <action-button
        :leading-icon="PlusIcon">
            {{ text }}
        </action-button>
    </Link>
</template>

<script setup lang="ts">
import ActionButton from "./ActionButton.vue";
import { Link } from "@inertiajs/vue3";
import { PlusIcon } from "@heroicons/vue/24/solid";

defineProps({
    routeLink: {
        type: String,
    },
    text: {
        type: String,
        default: "Create",
    },
});
</script>

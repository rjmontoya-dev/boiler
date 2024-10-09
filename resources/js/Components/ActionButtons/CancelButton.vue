<template>
    <a :href="routeLink">
        <action-button
            type="button"
            fill="clear"
            :color="color"
            :outlined="outlined"
            :leading-icon="leadingIcon"
        >
            {{ text }}
        </action-button>
    </a>
</template>

<script setup lang="ts">
import ActionButton from "@/Components/ActionButtons/ActionButton.vue";

defineProps({
    routeLink: {
        type: String,
    },
    text: {
        type: String,
        default: "Cancel",
    },
    color: {
        type: String,
    },
    outlined: {
        type: Boolean,
        default: false,
    },
    leadingIcon: {
        type: String,
        default: null,
    }
});
</script>

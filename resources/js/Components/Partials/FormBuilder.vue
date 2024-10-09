<template>
    <div class="w-[552px] mx-auto border rounded-lg bg-white" :class="cstmClass">
        <section-title class="mb-6 px-6 pt-6">
            <template #title>
                <slot name="title"></slot>
            </template>
            <template #status>
                <slot name="status"></slot>
            </template>
            <template #description>
                <slot name="description"></slot>
            </template>
        </section-title>

        <div>
            <form @submit.prevent="$emit('submitted')">
                <div class="grid grid-cols-12 gap-6 px-6">
                    <slot name="form"></slot>
                </div>

                <div class="flex items-center space-x-2 mt-6 px-6 py-4 rounded-bl-lg rounded-br-lg bg-gray-100 " v-if="hasActions">
                    <slot name="actions"></slot>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import SectionTitle from '../SectionTitle.vue';

defineProps({
    hasActions: {
        type: Boolean,
        default: true,
    },
    cstmClass: {
        type: String,
        default: null,
    },
});
</script>

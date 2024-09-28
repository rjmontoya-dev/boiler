<template>
    <button
        @click="show = true"
        class="
            mx-1
            inline-flex
            items-center
            p-1
            border border-transparent
            rounded-full
            text-primary
            hover:text-orange-400
            focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-green-400
        "
    >
        <ArrowPathIcon class="p-0.5 h-6 w-6 text-gray-500" aria-hidden="true" />
    </button>

    <restore-modal
        :show="show"
        :title="modalTitle"
        :item-name="modalName"
        @confirm="confirmRestore"
        @cancel="show = false"
    />
</template>

<script setup lang="ts">
import { ArrowPathIcon } from "@heroicons/vue/24/solid";
import { router } from "@inertiajs/vue3";
import { ref } from 'vue'
import RestoreModal from "../Modals/RestoreModal.vue";

const emit = defineEmits(['click'])
const props = defineProps({
    modalTitle: { type: String, default: 'Restore Item' },
    modalName: { type: String, default: 'Item #1' },
    routeLink: { type: String, required: true },
});

const show = ref<boolean>(false)

const confirmRestore = () => {
    show.value = false
    router.patch(props.routeLink)
}
</script>

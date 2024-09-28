<template>
    <button
        @click="show = true"
        class="
            mx-1
            inline-flex
            items-center
            p-1
            border border-transparent
            rounded-xl
            text-gray-500
            hover:text-gray-700
            active:text-gray-700
            focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-400
        "
        :class="[
            fill ? 'bg-[#DB4035] !rounded !p-2' :''
        ]"
    >
        <TrashIcon :class="fill ? 'text-white' : 'text-gray-500' " class="p-0.5 h-6 w-6" aria-hidden="true" />
    </button>

    <delete-modal
        :show="show"
        :title="modalTitle"
        :item-name="modalName"
        @confirm="confirmArchive"
        @cancel="show = false"
    />
</template>
<script setup lang="ts">
import { TrashIcon } from "@heroicons/vue/24/outline";
import { router } from "@inertiajs/vue3";
import { ref } from 'vue';
import DeleteModal from "../Modals/DeleteModal.vue";

const props = defineProps({
    modalTitle: { type: String, default: 'Archive Item' },
    modalName: { type: String, default: 'Item #1' },
    routeLink: { type: String, required: true },
    fill:{ type: Boolean, default: false }
});

const show = ref<boolean>(false)

const confirmArchive = () => {
    show.value = false
    router.delete(props.routeLink)
}
</script>

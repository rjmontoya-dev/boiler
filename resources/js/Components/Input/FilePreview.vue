<template>
    <div class="flex items-center justify-between border psi-input-border-default psi-input-border-radius">
        <div
            class="flex items-center justify-start px-4 py-2.5 space-x-2 w-[calc(100%-120px)]"
        >
            <component
                :is="VideoCameraIcon"
                class="w-10 h-10 rounded-lg cursor-pointer flex-shrink-0"
                v-if="fileType.includes('.mp4')"
                @click="$emit('file:download')"
            />
            <img
                :src="source"
                class="w-10 h-10 rounded-lg cursor-pointer object-contain bg-neutral-50 flex-shrink-0"
                title="View Image"
                v-else-if="fileType.includes('image/')"
                @click="$emit('file:download')"
            />
            <component
                :is="DocumentTextIcon"
                class="w-10 h-10 rounded-lg cursor-pointer flex-shrink-0"
                v-else
                @click="$emit('file:download')"
            />
            <div class="w-[90%] max-w-[90%]">
                <p class="text-sm text-[#111827] truncate">
                    {{ fileName }}
                </p>
                <p v-if="size > 0" class="text-sm text-[#6B7280]">
                    {{ formatBytes(size) }}
                </p>
            </div>
        </div>
        <div class="flex items-center justify-end w-[88px] flex-shrink-0">
            <div class="border-l psi-input-border-default" v-if="allowDownload">
                <button
                    type="button"
                    class="py-5 px-4 psi-input-icon-text hover:text-blue-500 transition"
                    @click="$emit('file:download')"
                >
                    <ArrowDownTrayIcon class="w-5 h-5" />
                </button>
            </div>
            <div class="border-l psi-input-border-default" v-if="allowDelete">
                <button
                    type="button"
                    class="py-5 px-3 psi-input-icon-text hover:text-red-500 transition"
                    @click="$emit('file:delete')"
                >
                    <TrashIcon class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import {
    ArrowDownTrayIcon,
    TrashIcon,
    VideoCameraIcon,
    DocumentTextIcon,
} from "@heroicons/vue/24/outline";

defineProps({
    source: {
        type: String,
        required: true,
    },
    fileType: {
        type: String,
        required: true,
        default: "image/*",
    },
    fileName: {
        type: String,
        default: "",
    },
    size: {
        type: Number,
        default: 0,
    },
    allowDownload: {
        type: Boolean,
        default: true,
    },
    allowDelete: {
        type: Boolean,
        default: false,
    },
});

const formatBytes = (bytes, decimals = 2) => {
    if (bytes === 0) return "0 Bytes";

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
};
</script>

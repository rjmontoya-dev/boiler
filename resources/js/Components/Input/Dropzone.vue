<template>
    <div>
        <p
            v-if="isDefault"
            :for="id"
            class="psi-input-label"
        >
            {{ label }}
            <span v-if="required" class="psi-input-label-required">*</span>
        </p>
        <div :class="disabled ? 'cursor-not-allowed' : ''">
            <div
                class="
                    w-full
                    flex
                    justify-center
                    px-6
                    py-4
                    border-2
                    border-dashed

                    psi-input-border-radius
                    focus:outline-[var(--psi-border-color-focus)]
                "
                :class="[
                    isDragActive ? 'border-[var(--psi-border-color-dropzone-active)]' : 'psi-input-border-default',
                    error ? 'psi-input-border-error' : '',
                    disabled ? 'psi-input-disabled pointer-events-none' : '',
                ]"
                v-bind="getRootProps()"
            >
                <div class="text-center" :class="isDefault ? 'flex items-center space-x-2' : ''">
                    <svg
                        class="mx-auto h-6 w-6"
                        :class="isDragActive ? 'text-primary-500' : 'psi-input-helper-icon-text'"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 48 48"
                        aria-hidden="true"
                    >
                        <path
                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                    <div class="psi-input-text" :class="isDefault ? 'flex text-sm' : 'text-xs'">
                        <p
                            for="file-upload"
                            class="
                                relative
                                cursor-pointer
                            "
                        >
                            <span>Select {{ multiple ? "files" : "a file" }}</span>
                            <input
                                v-bind="getInputProps()"
                                :id="id"
                                :name="name"
                                type="file"
                                class="sr-only"
                            />
                            <div>
                                <span class="pl-1 !font-normal">or drag and drop</span>
                                <p v-if="!isDefault" class="psi-input-helper-text mt-2 !text-xs">{{ description }}</p>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <p
            v-if="!isDefault"
            :for="id"
            class="psi-input-label mt-3 text-center"
        >
            {{ label }}
            <span v-if="required" class="psi-input-label-required">*</span>
        </p>
        <p v-if="isDefault" class="psi-input-helper-text mt-2">{{ description }}</p>

        <div
            class="w-full mt-2 space-y-2"
            v-if="previewFile && acceptedFiles.length > 0"
        >
            <template v-for="(file, key) in acceptedFiles" :key="file">
                <FilePreview
                    :file-type="file.type"
                    :source="acceptedFileSrcs[key]"
                    :file-name="file.name"
                    :size="file.size"
                    :allowDelete="allowDelete"
                    @file:download="fileDownload(key)"
                    @file:delete="fileDelete(key)"
                />
            </template>
        </div>

        <div
            class="w-full mt-2 space-y-2"
            v-else-if="path"
        >
            <template v-if="multiple == false">
                <FilePreview
                    v-if="accept == 'image/*'"
                    :source="displaySrc"
                    :allowDelete="allowDelete"
                    :file-type="previewFileType"
                    :file-name="file"
                    @file:download="previewFileDownload(displaySrc)"
                    @file:delete="fileDelete(key)"
                />
                <FilePreview
                    v-else
                    :source="displaySrc"
                    :file-type="previewFileType"
                    :allowDelete="allowDelete"
                    :file-name="path"
                    @file:download="previewFileDownload(displaySrc)"
                    @file:delete="fileDelete(key)"
                />
            </template>
            <template v-else>
                <template v-for="file in path" :key="file">
                    <FilePreview
                        :source="`${storageUrl}${file.image}`"
                        :allowDelete="allowDelete"
                        :file-name="file.image"
                        :file-type="previewFileType"
                        @file:delete="$emit('delete:file', file.id)"
                        @file:download="previewFileDownload(`${storageUrl}${file.image}`)"
                    />
                </template>
            </template>
        </div>

        <!-- External error -->
        <p
            v-if="error"
            class="input-error-text"
        >
            {{ error }}
        </p>

        <!-- Reject error -->
        <p
            v-for="(rejectError, index) in rejectErrors"
            :key="index"
            class="input-error-text"
        >
            {{ rejectError.filename }}: {{ rejectError.errors.join(", ") }}
        </p>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useDropzone } from "vue3-dropzone";
import FilePreview from "./FilePreview.vue";

const props = defineProps({
    path: {
        type: String,
        default: "",
    },
    file: {
        type: String,
    },
    error: {
        type: String,
        default: null,
    },
    description: {
        type: String,
        default: "File type: webp | Max file size: 10MB", // Standard image desc: Max file size: 10MB | Dimension: 1366px x 600px
    },
    name: {
        type: String,
        default: null,
    },
    id: {
        type: String,
        default: null,
    },
    label: {
        type: String,
        default: null,
    },
    // Dropzone props
    multiple: {
        type: Boolean,
        default: false,
    },
    maxSize: {
        type: Number,
        default: 25,
    },
    maxFiles: {
        type: Number,
        default: 0,
    },
    accept: {
        type: String,
        default: "image/*, video/*, application/pdf",
    },
    overwritePath: {
        type: Boolean,
        default: false,
    },
    previewFile: {
        type: Boolean,
        default: true,
    },
    previewFileType: {
        type: String,
        default: 'image/*'
    },
    deleteImageRoute: {
        type: String,
        default: null
    },
    allowDelete: {
        type: Boolean,
        default: true,
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    isDefault: {
        type: Boolean,
        default: true,
    }
})

const rejectErrors = ref([]);
const acceptedFileSrcs = ref([]);
const emit = defineEmits(['update:path', 'update:file'])

// Triggers when file is dropped or selected
function onDrop(acceptFiles, rejectReasons) {
    // console.log(acceptFiles);
    // console.log(rejectReasons);
    // process errors
    rejectErrors.value = rejectReasons.length
        ? renderErrors(rejectReasons)
        : [];

    // process SRCs
    acceptedFileSrcs.value = [];
    if (acceptFiles.length) {
        acceptFiles.forEach((file) => {
            processFileSrc(file).then((base64) => {
                acceptedFileSrcs.value.push(base64);
                if (props.overwritePath) {
                    emit("update:path", base64);
                }
            });
        });
        // emit v-model change event
        let payload = props.multiple
            ? acceptedFiles.value
            : acceptedFiles.value[0];
        emit("update:file", payload);
    } else {
        emit("update:file", null);
    }
}

const processFileSrc = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = (error) => reject(error);
    });
}

const renderErrors = (rejectReasons) => {
    const errors = rejectReasons.map((reason) => {
        return {
            filename: reason.file.name,
            errors: reason.errors.map((error) => {
                switch (error.code) {
                    case "file-invalid-type":
                        return "Invalid file type";
                    case "file-too-large":
                        return `File too large (${reason.file.size / 1000 / 1000} MB)`;
                }
            }),
        };
    });
    return props.multiple ? errors : [errors[0]];
}

const { getRootProps, getInputProps, isDragActive, acceptedFiles } =
    useDropzone({
        onDrop,
        maxSize: props.maxSize * 1000 * 3000, // convert to MB
        maxFiles: props.maxFiles,
        multiple: props.multiple,
        accept: props.accept,
    });

// displayed image
const storageUrl = computed(() => usePage().props.storage_url);
const displaySrc = computed(() => {
    if (acceptedFileSrcs.value.length) {
        return acceptedFileSrcs.value[0];
    } else if (props.path) {
        return storageUrl.value + props.path;
    }
    return null;
});

const fileDownload = (key) => {
    const a = document.createElement("a");
    a.href = acceptedFileSrcs.value[key];
    a.download = acceptedFiles.value[key].name;
    a.target = "_blank";
    a.click();
    a.remove();
}

const fileDelete = (key) => {
    acceptedFiles.value.splice(key, 1);
    acceptedFileSrcs.value.splice(key, 1);

    // emit v-model change event
    let payload = props.multiple
        ? acceptedFiles.value
        : acceptedFiles.value[0];
    emit("update:file", payload);
}

const deleteOldImage = (id) => {
    router.delete(
        route(props.deleteImageRoute, id),
        { preserveState: false }
    );
}

const previewFileDownload = (item) => {
    const a = document.createElement("a");
    a.href = item;
    a.target = "_blank";
    a.click();
    a.remove();
}
</script>

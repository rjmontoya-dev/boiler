<template>
    <form-builder>
        <template v-slot:title >
             Admin Information
        </template>

        <template v-slot:description >
            Relevant data and other details
        </template>

        <template v-slot:form >
            <template v-if="isForm">
                <div class="col-span-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title  *</label>
                        <input v-model="form.title" type="text" name="title" id="title" autocomplete="tel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="col-span-full">
                    <dropzone
                        :path="form.image_uploaded?.path"
                        :file-name="form.image_uploaded?.name"
                        :file-size="form.image_uploaded?.size"
                        :file-type="form.image_uploaded?.type"
                        v-model:file="form.image"
                        label="Image"
                        id="image"
                        name="image"
                        description="Image"
                        :preview-file="true"
                        required
                    />
                </div>
                <div class="col-span-full">
                    <CKEditor v-model="form.content" />
                </div>
            </template>
        </template>
        <template v-slot:actions>
            <slot />
        </template>
    </form-builder>
</template>
<script setup>

const props = defineProps({
    form: {
        type: Object,
        default: {}
    },
    isForm: {
        type: Boolean,
        default: true
    },
})

console.log(props.form);

</script>

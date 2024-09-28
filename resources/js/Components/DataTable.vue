<template>
    <div
    class="frm-table"
    :class="[ tableClass ]"
    >
        <div class="align-middle inline-block w-full">
            <div
                class="
                    overflow-x-auto
                    border-gray-200
                "
                :class="noBorder ? '' : 'border-t border-b'"
            >
                <table
                    class="
                        min-w-full
                        divide-y
                        divide-gray-200
                    "
                >
                    <!-- Header Slot -->
                    <slot name="header">
                        <thead class="bg-gray-50" :class="cstmTheadClass">
                            <tr>
                                <th
                                    v-for="(header, index) in headers"
                                    :key="`th-${index}`"
                                    class="
                                        px-6
                                        py-3
                                        text-left text-xs
                                        text-gray-500
                                        whitespace-nowrap
                                        th-parent
                                    "
                                    :class="header.customClass"
                                    :width="header.width"
                                >
                                    {{ header.text }}
                                </th>
                                <th
                                    v-if="!noAction"
                                    class="
                                        px-6
                                        py-3
                                        text-center text-xs
                                        text-gray-500
                                        font-semibold
                                        whitespace-nowrap
                                    "
                                >
                                    {{ actionText }}
                                </th>
                            </tr>
                        </thead>
                    </slot>
                    <!-- Body Slot -->
                    <tbody
                        class="
                            bg-white
                            divide-y
                            divide-gray-200
                            text-sm
                            text-gray-500
                        "
                        :class="cstmTbodyClass"
                    >
                        <slot name="body" v-if="count > 0" />
                        <template v-else>
                            <tr>
                                <td
                                    class="whitespace-nowrap text-center"
                                    :colspan="
                                        headers.length + (!noAction ? 1 : 0)
                                    "
                                >
                                    <div class="text-sm text-gray-400 py-4">
                                        {{ emptyText }}
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script setup lang="ts">

defineProps({
    headers: {
        type: [ Array, null ],
    },

    tableClass: {
        default: null,
        type: String
    },

    /* Text for empty list */
    emptyText: {
        default: 'No data to display.',
        type: String
    },

    /* Hide action column */
    noAction: {
        default: false,
        type: Boolean
    },

     /* Action header text */
    actionText: {
        default: 'Action',
        type: String
    },

    count: {
        default: 0,
        type: Number,
    },
    noBorder: {
        type: Boolean,
        default: false
    },
    cstmTheadClass: {
        type: String
    },
    cstmTbodyClass: {
        type: String
    },
})
</script>

<template>
    <TransitionRoot
        :show="show"
        enter="transition-opacity duration-75"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity duration-150"
        leave-from="opacity-100"
        leave-to="opacity-0"
    >
        <div v-if="message()" :class="`bg-${color()}-50 p-4`">
            <div class="flex">
                <div class="flex-shrink-0">
                    <CheckCircleIcon
                        :class="`h-5 w-5 text-${color()}-400`"
                        aria-hidden="true"
                        v-if="success !== ''"
                    />
                    <XCircleIcon
                        :class="`h-5 w-5 text-${color()}-400`"
                        aria-hidden="true"
                        v-else-if="danger !== '' || error !== ''"
                    />
                    <ExclamationCircleIcon
                        :class="`h-5 w-5 text-${color()}-400`"
                        aria-hidden="true"
                        v-else-if="warning !== ''"
                    />
                    <ChatBubbleBottomCenterTextIcon
                        :class="`h-5 w-5 text-${color()}-400`"
                        aria-hidden="true"
                        v-else
                    />
                </div>
                <div class="ml-3">
                    <p
                        :class="`text-sm font-medium text-${color()}-800`"
                        v-html="message()"
                    />
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button
                            type="button"
                            :class="`
                inline-flex
                rounded-md
                p-1.5
                focus:outline-none
                focus:ring-2
                focus:ring-offset-2
                bg-${color()}-50 text-${color()}-500 hover:bg-${color()}-100 focus:ring-offset-${color()}-50 focus:ring-${color()}-600
              `"
                        >
                            <span class="sr-only">Dismiss</span>
                            <XMarkIcon
                                class="h-5 w-5"
                                aria-hidden="true"
                                @click="close()"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </TransitionRoot>
</template>

<script lang="ts" setup>
import {
    CheckCircleIcon,
    XCircleIcon,
    XMarkIcon,
    ExclamationCircleIcon,
    ChatBubbleBottomCenterTextIcon,
} from "@heroicons/vue/24/solid";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { TransitionRoot } from "@headlessui/vue";

/*--------------*
 * COMPUTED
 *--------------*/
console.log(usePage() )
const success = computed<string>(() => usePage<any>().props?.flash?.success);
const danger = computed<string>(() => usePage<any>().props?.flash?.danger);
const warning = computed<string>(() => usePage<any>().props?.flash?.warning);
const error = computed<string>(() => usePage<any>().props?.flash?.error);
const show = computed<boolean>(() =>
    [success.value, danger.value, warning.value, error.value].some(
        (val: string) => (val ? val.trim().length > 0 : false)
    )
);

/*--------------*
 * METHODS
 *--------------*/

/**
 * Get message
 *
 * @property {Function}
 * @return {string}
 */
const message: Function = (): string => {
    if (success.value) {
        return success.value;
    } else if (danger.value) {
        return danger.value;
    } else if (warning.value) {
        return warning.value;
    } else if (error.value) {
        return error.value;
    }
};

/**
 * Get color
 *
 * @property {Function}
 * @return {string}
 */
const color: Function = (): string => {
    if (success.value) {
        return "green";
    } else if (danger.value || error.value) {
        return "red";
    } else if (warning.value) {
        return "yellow";
    } else {
        return "slate";
    }
};

/**
 * Close
 *
 * @property {Function}
 * @return {void}
 */
const close: Function = (): void => {
    usePage<any>().props.flash.success = null;
    usePage<any>().props.flash.danger = null;
    usePage<any>().props.flash.warning = null;
    usePage<any>().props.flash.error = null;
};
</script>

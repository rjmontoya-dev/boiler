<template>
    <div class="">
        <div class="border-b border-gray-200 flex justify-between">
            <nav
                class="-mb-px flex space-x-2 px-12 py-2"
                aria-label="Tabs"
                :class="cstmNavClass"
            >
                <a
                    v-for="tab in tabs"
                    :key="tab.name"
                    :href="tab.route ? tab.route : tab.value"
                    @click.prevent="selectTab(tab)"
                    :class="[
                        isSelectedTab(tab.value)
                            ? 'text-primary-500'
                            : 'text-gray-400 hover:text-gray-600 hover:border-gray-200',
                        'whitespace-nowrap flex py-4 px-1 text-sm cursor-pointer relative',
                    ]"
                    :aria-current="tab.current ? 'page' : undefined"
                >
                    {{ tab.name }}
                    <component v-if="tab.icon" :is="tab.icon" class="w-5 h-5 ml-2" />

                    <span
                        v-if="tab.count"
                        :class="[
                            isSelectedTab(tab.value)
                                ? 'bg-primary-500/[0.2] text-primary-500'
                                : 'bg-gray-100 text-gray-400',
                            'hidden ml-3 py-0.5 px-2.5 rounded-xl text-xs md:inline-block',
                        ]"
                        >{{ tab.count }}</span
                    >
                    <span
                        class="bg-primary-500 w-full inline-block h-1 absolute -bottom-2 left-0"
                        v-if="isSelectedTab(tab.value)"
                    />
                </a>
            </nav>
            <div class="px-7 py-2 flex items-center" v-if="buttonItems">
                <slot name="buttons" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import pickBy from "lodash/pickBy";

/*--------------*
 * PROPS
 *--------------*/

const props = defineProps({
    tabs: {
        type: Object,
    },
    buttonItems: {
        type: Boolean,
        default: false,
    },
    activeTab: {
        type: String,
        default: null,
    },
    tabRoute: {
        type: String,
        required: true,
    },
    preserveState: {
        type: Boolean,
        default: false,
    },
    cstmNavClass: {
        type: String,
    },
});

/*--------------*
 * EMITS
 *--------------*/

const emit = defineEmits(["update:tab"]);

/*--------------*
 * METHODS
 *--------------*/

const isSelectedTab = (value) => {
    return props.activeTab === value;
};

const selectTab = (tab) => {
    if(tab?.href) {
        router.get(tab?.href);
    } else {
        router.get(
            props.tabRoute,
            pickBy({ tab: tab.value }),
            {
                preserveState: props.preserveState,
                onSuccess: () => {
                    emit("update:tab", tab.value);
                },
            }
        );
    }
};
</script>

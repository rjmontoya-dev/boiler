<template>
    <button
        :type="btnType"
        :disabled="disabled"
        class="psi-base-btn"
        :class="[
            disabled ? 'opacity-25 pointer-events-none' : '',
            rounded ? 'psi-btn-rounded' : '',
            customClass,
            // color
            color == 'secondary' ? 'psi-btn-secondary' :
            color == 'success' ? 'psi-btn-success' :
            color == 'info' ? 'psi-btn-info' :
            color == 'warning' ? 'psi-btn-warning' :
            color == 'help' ? 'psi-btn-help' :
            color == 'danger' ? 'psi-btn-danger' :
            color == 'contrast' ? 'psi-btn-contrast' :
            // fill
            fill == 'clear' ? 'psi-btn-clear' : 'psi-btn-primary',

            outlined ? 'psi-btn-outlined' : '',

            // block
            block ? 'flex w-full' : 'inline-flex',
            // size
            size == 'small' ? 'psi-btn-sm' :
            size == 'large' ? 'psi-btn-lg' : 'psi-btn-base px-4',
        ]"
    >
        <component
        v-if="leadingIcon"
        :is="leadingIcon"
        aria-hidden="true"
        class="h-5 w-5 mr-2"/>
        <slot></slot>

        <component
        v-if="trailingIcon"
        :is="trailingIcon"
        aria-hidden="true"
        class="h-5 w-5 ml-2"/>
    </button>
  </template>

<script setup lang="ts">
import { PropType } from 'vue';


export type ButtonType = 'button' | 'submit' | 'reset'

defineProps({
    btnType: {
        type: String as PropType<ButtonType>,
        default: "submit",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    outlined: {
        type: Boolean,
        default: false,
    },
    customClass: {
        type: String,
        default: "",
    },
    block: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: "default", // small | default | large
    },
    color: {
        type: String,
        default: "primary", // primary, secondary, success, info, warning, help, danger, contrast
    },
    fill: {
        type: String,
        default: "default", // default, clear, outline
    },
    leadingIcon: {
        default: null,
    },
    trailingIcon: {
        default: null,
    },

    rounded: {
        type: Boolean,
        default: false // rounded-full
    }
});
</script>

<template>
    <div class="border border-gray-100 rounded-lg">
        <div class="px-6 py-4">
            <slot name="header" />
        </div>

        <div>
            <slot name="body" />
        </div>

        <div class="px-6 py-4">
            <slot name="footer" />
        </div>
    </div>
</template>

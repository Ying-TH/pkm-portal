<template>
    <div class="flex items-center justify-between px-4 py-2 bg-blue-100 dark:bg-blue-950 border-b border-blue-200 dark:border-blue-800 shrink-0">
        <div class="flex items-center gap-2 text-sm text-blue-800 dark:text-blue-200">
            <i class="pi pi-info-circle text-blue-500" />
            <span>{{ message }}</span>
        </div>
        <button
            class="flex items-center justify-center w-6 h-6 rounded hover:bg-blue-200 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-300 transition-colors"
            @click="$emit('dismiss')"
        >
            <i class="pi pi-times text-xs" />
        </button>
    </div>
</template>

<script setup>
defineProps({
    message: { type: String, default: '' },
});
defineEmits(['dismiss']);
</script>

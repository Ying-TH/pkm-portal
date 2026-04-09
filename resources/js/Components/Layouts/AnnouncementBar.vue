<script setup>
import { ref } from "vue";

const visible = ref(true);
</script>

<template>
    <div
        v-if="visible"
        class="relative flex items-center bg-blue-100 px-4 py-2 text-sm border-b border-blue-200 flex-shrink-0"
    >
        <p class="flex-1 text-center text-blue-800">
            <span class="font-semibold">Announcement</span>&nbsp; Read.
        </p>
        <button
            @click="visible = false"
            class="absolute right-3 p-1 rounded text-blue-600 hover:text-blue-900 hover:bg-blue-200 transition-colors"
            aria-label="Dismiss announcement"
        >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                />
            </svg>
        </button>
    </div>
</template>

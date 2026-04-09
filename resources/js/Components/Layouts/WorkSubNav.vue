<template>
    <div class="flex items-center h-9 px-3 bg-surface-50 dark:bg-surface-950 border-b border-surface-200 dark:border-surface-700 shrink-0 gap-0 overflow-x-auto scrollbar-none">
        <template v-for="mod in modules" :key="mod.key">
            <button
                :class="[
                    'inline-flex items-center gap-1 h-7 px-2.5 rounded text-xs font-medium whitespace-nowrap transition-colors',
                    isActive(mod)
                        ? 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300'
                        : 'text-surface-600 dark:text-surface-400 hover:bg-surface-200 dark:hover:bg-surface-800 hover:text-surface-900 dark:hover:text-surface-100',
                ]"
                @click="navigate(mod)"
            >
                <i class="pi pi-plus text-[10px] opacity-50" />
                {{ mod.label }}
            </button>
        </template>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    modules: { type: Array, default: () => [] },
});

function isActive(mod) {
    if (!mod.route) return false;
    try { return route().current(mod.route); } catch { return false; }
}

function navigate(mod) {
    if (!mod.route) return;
    try { router.visit(route(mod.route)); } catch { /* route not registered yet */ }
}
</script>

<style scoped>
.scrollbar-none { scrollbar-width: none; }
.scrollbar-none::-webkit-scrollbar { display: none; }
</style>

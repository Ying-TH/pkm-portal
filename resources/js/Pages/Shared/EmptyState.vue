<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";

const props = defineProps({ module: { type: String, default: "Module" } });

// "mail" -> "Mail"
const moduleTitle = computed(() => {
    return props.module.charAt(0).toUpperCase() + props.module.slice(1);
});
</script>

<template>
    <AuthenticatedLayout :defaultSidebarOpen="true">
        <div
            class="flex flex-col items-center justify-center min-h-[60vh] text-center p-8"
        >
            <!-- Lycanroc — the "construction" mascot -->
            <img
                src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/745.png"
                alt="Coming Soon"
                class="w-48 h-48 object-contain mb-6 opacity-90"
            />
            <h1 class="text-3xl font-black text-gray-800 tracking-wide mb-3">
                COMING SOON...
            </h1>
            <p class="text-gray-500 text-sm mb-5 max-w-xs leading-relaxed">
                The
                <span class="font-semibold text-gray-700">{{
                    moduleTitle
                }}</span>
                module is currently under construction. Check back later for
                updates!
            </p>
            <span
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full"
            >
                <span
                    class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"
                ></span>
                Development in Progress
            </span>
        </div>
    </AuthenticatedLayout>
</template>

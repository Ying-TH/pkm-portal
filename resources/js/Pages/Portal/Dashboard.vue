<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const page = usePage();
const userName = computed(() => page.props.auth?.user?.name ?? "User");

const quickTabs = ["Recent News", "Desk", "Note", "Calendar", "Analytics"];
const activeTab = ref("Recent News");
</script>

<template>
    <AuthenticatedLayout :defaultSidebarOpen="true">
        <div class="p-6">
            <h1 class="text-2xl font-light text-gray-800 mb-1">
                Wellcome, {{ userName }}
            </h1>
            <p class="text-gray-400 mb-6 text-sm">To Work Pokemon World</p>

            <!-- Customize + tab bar -->
            <div class="flex flex-wrap items-center gap-2 mb-5">
                <button
                    class="flex items-center gap-1.5 text-xs text-gray-600 border border-gray-200 rounded px-2.5 py-1 hover:bg-gray-50"
                >
                    <!-- pencil icon -->
                    Customize
                </button>
                <div class="flex items-center gap-0.5 flex-wrap">
                    <button
                        v-for="tab in quickTabs"
                        :key="tab"
                        @click="activeTab = tab"
                        class="text-xs px-2.5 py-1 rounded transition-colors"
                        :class="
                            activeTab === tab
                                ? 'bg-blue-50 text-blue-600 font-medium'
                                : 'text-gray-500 hover:bg-gray-100'
                        "
                    >
                        {{ tab }}
                    </button>
                </div>
            </div>

            <!-- Widget grid (2 "Coming Soon" cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div
                    v-for="i in 2"
                    :key="i"
                    class="bg-white border border-gray-200 rounded-lg p-6 flex flex-col items-center justify-center min-h-[240px]"
                >
                    <img
                        src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/745.png"
                        alt="Coming Soon"
                        class="w-24 h-24 object-contain mb-4 opacity-80"
                    />
                    <p class="font-bold text-gray-700 text-sm mb-1">
                        COMING SOON...
                    </p>
                    <p
                        class="text-xs text-gray-400 text-center mb-3 max-w-[200px]"
                    >
                        The Mail module is currently under construction. Check
                        back later for updates!
                    </p>
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 bg-orange-100 text-orange-700 text-xs font-medium rounded-full"
                    >
                        <span
                            class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"
                        ></span>
                        Development in Progress
                    </span>
                    <Link
                        href="#"
                        class="mt-3 text-xs text-gray-400 hover:text-blue-600 flex items-center gap-1"
                    >
                        <!-- expand icon -->
                        Explore query
                    </Link>
                </div>
            </div>

            <!-- Scroll indicator dots -->
            <div class="flex justify-end gap-1 mt-4">
                <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

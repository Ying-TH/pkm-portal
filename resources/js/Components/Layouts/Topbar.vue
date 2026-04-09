<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import Dropdown from "@/Components/Base/Dropdown.vue";
import DropdownLink from "@/Components/Base/DropdownLink.vue";
import Sidebar from "@/Components/Layouts/Sidebar.vue";

defineProps({ sidebarOpen: Boolean });
defineEmits(["toggleSidebar"]);

const page = usePage();

// "John Doe" -> "JD"
const userInitials = computed(() => {
    const name = page.props.auth?.user?.name;
    if (!name) return "?";
    const parts = name.trim().split(/\s+/);
    return parts.length >= 2
        ? (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
        : name.substring(0, 2).toUpperCase();
});
</script>

<template>
    <header
        class="flex h-11 items-center gap-2 border-b border-gray-200 bg-white px-3 flex-shrink-0 z-10"
    >
        <!-- Hamburger toggle -->
        <button @click="$emit('toggleSidebar')"><!-- bars SVG -->></button>

        <!-- PKM Work logo (alway visible) -->
        <Link
            href="route('portal.overview')"
            class="flex items-center gap-0.5 flex-shrink-0"
        >
            <span class="font-bold text-blue-600 text-sm">PKM</span>
            <span class="text-gray-400 text-sm font-medium">Work</span>
        </Link>

        <span class="text-gray-300">|</span>
        <span class="text-gray-700 text-sm font-medium truncate max-w-[160px]"
            >Pokemon Work</span
        >

        <!-- Nav links: only shown when sidebar is CLOSED -->
        <template v-if="!sidebarOpen">
            <div class="hidden sm:flex items-center gap-0.5 ml-1">
                <Dropdown><!-- Workspace dropdown -->></Dropdown>
                <Link
                    :href="route('portal.dashboard')"
                    class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 rounded"
                    >Dashboard</Link
                >
                <Link
                    :href="route('portal.news')"
                    class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 rounded"
                    >News</Link
                >
            </div>
        </template>

        <div class="flex-1 min-w-0" />

        <!-- Search icon, Bell icon, User avatar dropdown -->
        <div class="flex items-center gap-1">
            <button class="p-1.5 rounded text-gray-500 hover:bg-gray-100">
                <!-- search SVG -->
            </button>
            <button class="p-1.5 rounded text-gray-500 hover:bg-gray-100">
                <!-- bell SVG -->
            </button>
            <Dropdown align="right" width="48">
                <template #trigger>
                    <!-- Blue circle with initials: "JD" -->
                    <button
                        class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center"
                    >
                        {{ userInitials }}
                    </button>
                </template>
                <template #content>
                    <!-- user name + email -->
                    <DropdownLink :href="route('profile.edit')"
                        >Profile</DropdownLink
                    >
                    <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                        >Log Out</DropdownLink
                    >
                </template>
            </Dropdown>
        </div>
    </header>
</template>

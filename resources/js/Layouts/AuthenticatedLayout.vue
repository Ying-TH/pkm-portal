<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import AnnouncementBar from "@/Components/Layouts/AnnouncementBar.vue";
import Topbar from "@/Components/Layouts/Topbar.vue";
import Sidebar from "@/Components/Layouts/Sidebar.vue";
import AppFooter from "@/Components/Layouts/Footer.vue";

const props = defineProps({
    defaultSidebarOpen: { type: Boolean, default: false },
});

const sidebarOpen = ref(props.defaultSidebarOpen);

// Module shortcut bar - shown in the second nav row when sidebar is hidden
const quickLinks = [
    { label: "Dashboard", href: "/portal/dashboard" },
    { label: "Talk", href: "/portal/talk" },
    { label: "File", href: "/portal/file" },
    { label: "Photos", href: "/portal/photos" },
    { label: "Activities", href: "/portal/activities" },
    { label: "Mail", href: "/portal/mail" },
    { label: "Contacts", href: "/portal/contacts" },
    { label: "Calendar", href: "/portal/calendar" },
    { label: "Analytics", href: "/portal/analytics" },
    { label: "Note", href: "/portal/note" },
    { label: "Deck", href: "/portal/deck" },
    { label: "Collective", href: "/portal/collective" },
    { label: "Form", href: "/portal/form" },
    { label: "Work", href: "/portal/work" },
    { label: "Tables", href: "/portal/tables" },
];
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-gray-50">
        <Sidebar :open="sidebarOpen" @close="sidebarOpen = false" />

        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <AnnouncementBar />

            <Topbar
                :sidebar-open="sidebarOpen"
                @toggle-sidebar="sidebarOpen = !sidebarOpen"
            />

            <!-- Second nav row: + Dashboard + Talk + File ... (only when sidebar closed) -->
            <div
                v-if="!sidebarOpen"
                class="flex items-center overflow-x-auto bg-white border-b border-gray-200 px-3 py-1 flex-shrink-0"
            >
                <Link
                    v-for="link in quickLinks"
                    :key="link.label"
                    :href="link.href"
                    class="flex-shrink-0 flex items-center gap-0.5 px-2 py-1 text-xs text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded whitespace-wrap transition-colors"
                >
                    <span class="text-gray-300">+</span>{{ link.label }}
                </Link>
            </div>

            <main class="flex-1 overflow-y-auto">
                <slot />
            </main>

            <AppFooter />
        </div>
    </div>
</template>

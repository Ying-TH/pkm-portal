<template>
    <header class="flex items-center h-12 px-4 bg-white dark:bg-[#0a0a0a] border-b border-[#ededed] dark:border-[#1f1f1f] shrink-0 z-20 relative">

        <!-- Brand -->
        <div class="flex items-center gap-2 mr-4 shrink-0">
            <div class="w-5 h-5 rounded bg-[#171717] dark:bg-white text-white dark:text-[#171717] text-[8px] font-black flex items-center justify-center">
                PK
            </div>
            <span class="text-[13px] font-semibold text-[#171717] dark:text-white tracking-tight">
                PKM Work
            </span>
            <span class="text-[#ccc] dark:text-[#444] text-[11px] ml-1">|</span>
        </div>

        <!-- Nav tabs -->
        <nav class="flex items-center gap-0.5 flex-1 overflow-x-auto scrollbar-none">
            <template v-for="tab in navTabs" :key="tab.key">
                <button
                    :ref="el => tabRefs[tab.key] = el"
                    :class="[
                        'flex items-center gap-1 h-8 px-3 rounded text-[13px] font-medium whitespace-nowrap transition-colors',
                        openTab === tab.key
                            ? 'bg-[#f2f2f2] dark:bg-[#1a1a1a] text-[#171717] dark:text-white'
                            : 'text-[#555] dark:text-[#888] hover:bg-[#f5f5f5] dark:hover:bg-[#141414] hover:text-[#171717] dark:hover:text-white',
                    ]"
                    @click="toggleTab(tab, $event)"
                >
                    {{ tab.label }}
                    <i
                        v-if="tab.columns"
                        :class="['pi pi-chevron-down text-[9px] opacity-60 transition-transform', openTab === tab.key ? 'rotate-180' : '']"
                    />
                </button>
            </template>
        </nav>

        <!-- Right actions -->
        <div class="flex items-center gap-1 shrink-0 ml-4">
            <button
                v-tooltip.bottom="'Search  Ctrl+K'"
                class="flex items-center justify-center w-8 h-8 rounded-md text-[#666] dark:text-[#888] hover:bg-[#f5f5f5] dark:hover:bg-[#1a1a1a] transition-colors"
                @click="$emit('open-search')"
            >
                <i class="pi pi-search text-[13px]" />
            </button>

            <button
                v-tooltip.bottom="isDark ? 'Light mode' : 'Dark mode'"
                class="flex items-center justify-center w-8 h-8 rounded-md text-[#666] dark:text-[#888] hover:bg-[#f5f5f5] dark:hover:bg-[#1a1a1a] transition-colors"
                @click="toggleDark"
            >
                <i :class="['pi text-[13px]', isDark ? 'pi-sun' : 'pi-moon']" />
            </button>

            <div class="w-px h-4 bg-[#ededed] dark:bg-[#2e2e2e] mx-1" />

            <!-- Back to overview -->
            <button
                v-tooltip.bottom="'Back to overview'"
                class="flex items-center justify-center w-8 h-8 rounded-md text-[#666] dark:text-[#888] hover:bg-[#f5f5f5] dark:hover:bg-[#1a1a1a] transition-colors"
                @click="setMode('overview')"
            >
                <i class="pi pi-th-large text-[13px]" />
            </button>

            <!-- User avatar -->
            <button
                class="w-7 h-7 rounded-full bg-[#171717] dark:bg-white text-white dark:text-[#171717] text-[10px] font-bold hover:opacity-75 transition-opacity ml-1"
                @click="userMenu.toggle($event)"
            >
                {{ userInitials }}
            </button>
            <Menu ref="userMenu" :model="userMenuItems" popup />
        </div>

        <!-- Mega-menu dropdown panel -->
        <Transition name="megamenu">
            <div
                v-if="openTab && activeColumns.length"
                class="absolute top-full left-0 right-0 bg-white dark:bg-[#0f0f0f] border-b border-[#ededed] dark:border-[#1f1f1f] shadow-lg z-50 px-6 py-5"
                @mouseleave="openTab = null"
            >
                <div class="flex gap-12 max-w-5xl">
                    <div
                        v-for="col in activeColumns"
                        :key="col.heading"
                        class="flex flex-col gap-1 min-w-[150px]"
                    >
                        <p class="text-[11px] font-semibold text-[#999] dark:text-[#555] uppercase tracking-wider mb-2">
                            {{ col.heading }}
                        </p>
                        <button
                            v-for="link in col.links"
                            :key="link.key"
                            class="flex items-center gap-2 py-1 text-[13px] text-[#333] dark:text-[#ccc] hover:text-[#171717] dark:hover:text-white transition-colors text-left"
                            @click="handleMegaLink(link)"
                        >
                            <i v-if="link.icon" :class="['pi', link.icon, 'text-[12px] text-[#999] dark:text-[#555]']" />
                            {{ link.label }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </header>

    <!-- Backdrop to close mega-menu -->
    <Transition name="fade">
        <div
            v-if="openTab"
            class="fixed inset-0 z-10"
            @click="openTab = null"
        />
    </Transition>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Menu from 'primevue/menu';
import { useLayoutMode } from '@/composables/useLayoutMode.js';

const { isDark, setMode, toggleDark } = useLayoutMode();

const page = usePage();
const userMenu = ref(null);
const openTab = ref(null);
const tabRefs = ref({});

const userName = computed(() => page.props.auth?.user?.name ?? 'User');
const userInitials = computed(() =>
    userName.value.split(' ').map((n) => n[0]).join('').substring(0, 2).toUpperCase()
);

// Nav tabs with mega-menu column definitions
const navTabs = [
    {
        key: 'knowledge',
        label: 'Knowledge',
        columns: [
            {
                heading: 'Content',
                links: [
                    { key: 'notes',     label: 'Notes',      icon: 'pi-file-edit',  route: 'portal.module', params: { module: 'notes' } },
                    { key: 'tags',      label: 'Tags',        icon: 'pi-tags',       route: 'portal.module', params: { module: 'tags' } },
                    { key: 'links',     label: 'Saved Links', icon: 'pi-link',       route: 'portal.module', params: { module: 'links' } },
                    { key: 'bookmarks', label: 'Bookmarks',   icon: 'pi-bookmark',   route: 'portal.module', params: { module: 'bookmarks' } },
                ],
            },
            {
                heading: 'Explore',
                links: [
                    { key: 'search',    label: 'Search all',  icon: 'pi-search' },
                    { key: 'recent',    label: 'Recent',       icon: 'pi-history' },
                    { key: 'favorites', label: 'Favorites',    icon: 'pi-star' },
                ],
            },
        ],
    },
    {
        key: 'workspace',
        label: 'Workspace',
        columns: [
            {
                heading: 'Productivity',
                links: [
                    { key: 'tasks',      label: 'Tasks',      icon: 'pi-check-square', route: 'portal.module', params: { module: 'tasks' } },
                    { key: 'calendar',   label: 'Calendar',   icon: 'pi-calendar',     route: 'portal.module', params: { module: 'calendar' } },
                    { key: 'mail',       label: 'Mail',        icon: 'pi-envelope',     route: 'portal.module', params: { module: 'mail' } },
                    { key: 'contacts',   label: 'Contacts',    icon: 'pi-users',        route: 'portal.module', params: { module: 'contacts' } },
                ],
            },
            {
                heading: 'Collaboration',
                links: [
                    { key: 'talk',       label: 'Talk',        icon: 'pi-comments',     route: 'portal.module', params: { module: 'talk' } },
                    { key: 'collective', label: 'Collective',  icon: 'pi-sitemap',      route: 'portal.module', params: { module: 'collective' } },
                    { key: 'deck',       label: 'Deck',        icon: 'pi-table',        route: 'portal.module', params: { module: 'deck' } },
                    { key: 'forms',      label: 'Forms',       icon: 'pi-list-check',   route: 'portal.module', params: { module: 'forms' } },
                ],
            },
            {
                heading: 'Data',
                links: [
                    { key: 'files',    label: 'Files',     icon: 'pi-folder',   route: 'portal.module', params: { module: 'files' } },
                    { key: 'photos',   label: 'Photos',    icon: 'pi-images',   route: 'portal.module', params: { module: 'photos' } },
                    { key: 'storage',  label: 'Storage',   icon: 'pi-database', route: 'portal.module', params: { module: 'storage' } },
                    { key: 'tables',   label: 'Tables',    icon: 'pi-table',    route: 'portal.module', params: { module: 'tables' } },
                ],
            },
        ],
    },
    {
        key: 'dashboard',
        label: 'Dashboard',
        action: () => { setMode('dashboard'); router.visit(route('portal.overview')); },
    },
    {
        key: 'news',
        label: 'News',
        action: () => router.visit(route('portal.news')),
    },
];

const activeColumns = computed(() => {
    const tab = navTabs.find((t) => t.key === openTab.value);
    return tab?.columns ?? [];
});

// User dropdown
const userMenuItems = computed(() => [{
    label: userName.value,
    items: [
        { label: 'Profile',   icon: 'pi pi-user',      command: () => router.visit(route('profile.edit')) },
        { label: 'Overview',  icon: 'pi pi-home',      command: () => { setMode('overview'); router.visit(route('overview')); } },
        { label: 'Dashboard', icon: 'pi pi-chart-bar', command: () => setMode('dashboard') },
        { separator: true },
        { label: 'Sign out',  icon: 'pi pi-sign-out',  command: () => router.post(route('logout')) },
    ],
}]);

function toggleTab(tab, event) {
    if (tab.action) {
        openTab.value = null;
        tab.action();
        return;
    }
    openTab.value = openTab.value === tab.key ? null : tab.key;
}

function handleMegaLink(link) {
    openTab.value = null;
    if (!link.route) return;
    try { router.visit(route(link.route, link.params ?? {})); } catch { /* not yet registered */ }
}

defineEmits(['open-search']);
</script>

<style scoped>
.scrollbar-none { scrollbar-width: none; }
.scrollbar-none::-webkit-scrollbar { display: none; }

.megamenu-enter-active,
.megamenu-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.megamenu-enter-from,
.megamenu-leave-to   { opacity: 0; transform: translateY(-6px); }

.fade-enter-active,
.fade-leave-active { transition: opacity 0.1s; }
.fade-enter-from,
.fade-leave-to     { opacity: 0; }
</style>

<template>
    <aside class="flex flex-col w-[240px] h-screen bg-white dark:bg-[#0a0a0a] border-r border-[#ededed] dark:border-[#1f1f1f] shrink-0 overflow-hidden">

        <!-- Workspace header -->
        <div class="flex items-center h-12 border-b border-[#ededed] dark:border-[#1f1f1f] px-2 gap-1 shrink-0">
            <!-- Back to overview -->
            <button
                v-tooltip.right="'Back to overview'"
                class="flex items-center justify-center w-7 h-7 rounded text-[#888] dark:text-[#555] hover:bg-[#f5f5f5] dark:hover:bg-[#1a1a1a] hover:text-[#171717] dark:hover:text-white transition-colors shrink-0"
                @click="setMode('overview')"
            >
                <i class="pi pi-chevron-left text-[11px]" />
            </button>

            <div
                class="flex items-center gap-2 flex-1 min-w-0 cursor-pointer hover:bg-[#f5f5f5] dark:hover:bg-[#1a1a1a] rounded px-1.5 py-1 transition-colors"
                @click="workspaceMenu.toggle($event)"
            >
            <div class="flex items-center justify-center w-5 h-5 rounded bg-black dark:bg-white text-white dark:text-black text-[10px] font-bold shrink-0">
                {{ workspaceInitials }}
            </div>
            <span class="flex-1 text-[13px] font-medium text-[#171717] dark:text-[#ededed] truncate">
                {{ workspaceName }}
            </span>
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded border border-[#ededed] dark:border-[#2e2e2e] text-[#666] dark:text-[#888] bg-white dark:bg-[#111] shrink-0">
                Hobby
            </span>
            <i class="pi pi-chevron-down text-[10px] text-[#888] dark:text-[#555] shrink-0" />
            </div><!-- /clickable workspace row -->
        </div><!-- /workspace header -->
        <Menu ref="workspaceMenu" :model="workspaceMenuItems" popup />

        <!-- Search -->
        <div class="px-3 py-2">
            <div
                class="flex items-center gap-2 px-2.5 h-8 rounded-md bg-[#fafafa] dark:bg-[#111] border border-[#ededed] dark:border-[#2e2e2e] cursor-text hover:border-[#ccc] dark:hover:border-[#444] transition-colors"
                @click="$emit('open-search')"
            >
                <i class="pi pi-search text-[12px] text-[#999] dark:text-[#555]" />
                <span class="flex-1 text-[13px] text-[#999] dark:text-[#555] select-none">Find...</span>
                <kbd class="text-[10px] px-1.5 py-0.5 rounded bg-[#f0f0f0] dark:bg-[#1f1f1f] text-[#888] dark:text-[#555] border border-[#e0e0e0] dark:border-[#2a2a2a] font-mono leading-none">
                    F
                </kbd>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 pb-3 space-y-0.5">
            <template v-for="(group, gi) in navGroups" :key="gi">
                <div v-if="gi > 0" class="my-1.5 border-t border-[#ededed] dark:border-[#1f1f1f]" />

                <template v-for="item in group" :key="item.key">
                    <!-- Expandable item -->
                    <template v-if="item.children">
                        <button
                            :class="navItemClass(item, true)"
                            @click="toggleExpand(item.key)"
                        >
                            <i :class="['pi', item.icon, 'text-[14px] shrink-0']" />
                            <span class="flex-1 text-left truncate">{{ item.label }}</span>
                            <i :class="['pi text-[10px] text-[#999] dark:text-[#555] transition-transform', expanded.includes(item.key) ? 'pi-chevron-down' : 'pi-chevron-right']" />
                        </button>
                        <!-- Children -->
                        <div v-show="expanded.includes(item.key)" class="ml-4 mt-0.5 space-y-0.5">
                            <button
                                v-for="child in item.children"
                                :key="child.key"
                                :class="navItemClass(child)"
                                @click="navigate(child)"
                            >
                                <i :class="['pi', child.icon, 'text-[13px] shrink-0']" />
                                <span class="flex-1 text-left truncate">{{ child.label }}</span>
                            </button>
                        </div>
                    </template>

                    <!-- Regular item -->
                    <button
                        v-else
                        :class="navItemClass(item)"
                        @click="navigate(item)"
                    >
                        <i :class="['pi', item.icon, 'text-[14px] shrink-0']" />
                        <span class="flex-1 text-left truncate">{{ item.label }}</span>
                    </button>
                </template>
            </template>
        </nav>
    </aside>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Menu from 'primevue/menu';
import { useLayoutMode } from '@/composables/useLayoutMode.js';

const { setMode } = useLayoutMode();

const props = defineProps({
    workspaceName: { type: String, default: 'PKM Work' },
    workspaces:    { type: Array,  default: () => [] },
});

const emit = defineEmits(['open-search', 'change-workspace']);

const workspaceMenu = ref(null);
const expanded = ref([]);

const workspaceInitials = computed(() =>
    props.workspaceName.split(' ').map((w) => w[0]).join('').substring(0, 2).toUpperCase()
);

// Workspace switcher menu
const workspaceMenuItems = computed(() =>
    props.workspaces.map((ws) => ({
        label:   ws.label,
        command: () => emit('change-workspace', ws),
    }))
);

// Nav groups (mirrors Vercel's grouping)
const navGroups = [
    [
        { key: 'overview',     label: 'Overview',      icon: 'pi-home',         route: 'overview' },
        { key: 'notes',        label: 'Notes',         icon: 'pi-file-edit',    route: 'portal.module', params: { module: 'notes' } },
        { key: 'tasks',        label: 'Tasks',         icon: 'pi-check-square', route: 'portal.module', params: { module: 'tasks' } },
        { key: 'analytics',    label: 'Analytics',     icon: 'pi-chart-bar',    route: 'portal.module', params: { module: 'analytics' } },
        { key: 'activities',   label: 'Activities',    icon: 'pi-bolt',         route: 'portal.module', params: { module: 'activities' } },
        {
            key: 'calendar', label: 'Calendar', icon: 'pi-calendar',
            children: [
                { key: 'calendar-week',  label: 'Week view',  icon: 'pi-table' },
                { key: 'calendar-month', label: 'Month view', icon: 'pi-calendar' },
            ],
        },
        { key: 'mail',         label: 'Mail',          icon: 'pi-envelope',     route: 'portal.module', params: { module: 'mail' } },
        { key: 'files',        label: 'Files',         icon: 'pi-folder',       route: 'portal.module', params: { module: 'files' } },
    ],
    [
        { key: 'contacts',     label: 'Contacts',      icon: 'pi-users',        route: 'portal.module', params: { module: 'contacts' } },
        { key: 'integrations', label: 'Integrations',  icon: 'pi-plug',         route: 'portal.module', params: { module: 'integrations' } },
        { key: 'storage',      label: 'Storage',       icon: 'pi-database',     route: 'portal.module', params: { module: 'storage' } },
        { key: 'tags',         label: 'Tags',          icon: 'pi-tags',         route: 'portal.module', params: { module: 'tags' } },
        {
            key: 'deck', label: 'Deck', icon: 'pi-table',
            children: [
                { key: 'deck-boards', label: 'Boards', icon: 'pi-th-large' },
                { key: 'deck-cards',  label: 'Cards',  icon: 'pi-id-card' },
            ],
        },
        {
            key: 'collective', label: 'Collective', icon: 'pi-sitemap',
            children: [
                { key: 'collective-wiki',    label: 'Wiki',    icon: 'pi-book' },
                { key: 'collective-members', label: 'Members', icon: 'pi-users' },
            ],
        },
        { key: 'forms',        label: 'Forms',         icon: 'pi-list-check',   route: 'portal.module', params: { module: 'forms' } },
    ],
    [
        { key: 'usage',        label: 'Usage',         icon: 'pi-chart-line',   route: 'portal.module', params: { module: 'usage' } },
        { key: 'support',      label: 'Support',       icon: 'pi-question-circle' },
        {
            key: 'settings', label: 'Settings', icon: 'pi-cog',
            children: [
                { key: 'settings-profile',  label: 'Profile',       icon: 'pi-user',   route: 'profile.edit' },
                { key: 'settings-billing',  label: 'Billing',       icon: 'pi-credit-card' },
                { key: 'settings-security', label: 'Security',      icon: 'pi-shield' },
            ],
        },
    ],
];

function isActive(item) {
    if (!item.route) return false;
    try { return route().current(item.route); } catch { return false; }
}

function navItemClass(item) {
    const active = isActive(item);
    return [
        'flex items-center gap-2.5 w-full px-2.5 py-[6px] rounded-md text-[13px] font-medium transition-colors',
        active
            ? 'bg-[#f2f2f2] dark:bg-[#1a1a1a] text-[#171717] dark:text-[#ededed]'
            : 'text-[#444] dark:text-[#888] hover:bg-[#f5f5f5] dark:hover:bg-[#141414] hover:text-[#171717] dark:hover:text-[#ededed]',
    ];
}

function toggleExpand(key) {
    const idx = expanded.value.indexOf(key);
    if (idx === -1) expanded.value.push(key);
    else expanded.value.splice(idx, 1);
}

function navigate(item) {
    if (!item.route) return;
    try {
        router.visit(route(item.route, item.params ?? {}));
    } catch { /* route not yet registered */ }
}
</script>

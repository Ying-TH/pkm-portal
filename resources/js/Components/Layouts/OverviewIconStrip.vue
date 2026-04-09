<template>
    <aside class="flex flex-col w-12 h-screen bg-white dark:bg-[#0a0a0a] border-r border-[#ededed] dark:border-[#1f1f1f] shrink-0 z-20">

        <!-- Brand mark -->
        <div class="flex items-center justify-center h-12 border-b border-[#ededed] dark:border-[#1f1f1f] shrink-0">
            <div class="w-6 h-6 rounded bg-[#171717] dark:bg-white text-white dark:text-[#171717] text-[9px] font-black flex items-center justify-center tracking-tight select-none">
                PK
            </div>
        </div>

        <!-- Nav icons -->
        <nav class="flex flex-col items-center gap-0.5 py-2 flex-1 overflow-y-auto">
            <template v-for="item in navItems" :key="item.key">
                <div
                    v-if="item.divider"
                    class="w-6 my-1 border-t border-[#ededed] dark:border-[#1f1f1f]"
                />
                <button
                    v-else
                    v-tooltip.right="{ value: item.label, showDelay: 300 }"
                    :class="[
                        'flex items-center justify-center w-9 h-9 rounded-md transition-colors relative group',
                        item.active
                            ? 'bg-[#f2f2f2] dark:bg-[#1a1a1a] text-[#171717] dark:text-white'
                            : 'text-[#777] dark:text-[#555] hover:bg-[#f5f5f5] dark:hover:bg-[#141414] hover:text-[#171717] dark:hover:text-white',
                    ]"
                    @click="handleClick(item)"
                >
                    <i :class="['pi', item.icon, 'text-[15px]']" />
                    <!-- Active dot indicator -->
                    <span
                        v-if="item.active"
                        class="absolute right-1 top-1/2 -translate-y-1/2 w-1 h-1 rounded-full bg-[#171717] dark:bg-white"
                    />
                </button>
            </template>
        </nav>

        <!-- User avatar -->
        <div class="flex items-center justify-center py-3 border-t border-[#ededed] dark:border-[#1f1f1f] shrink-0">
            <button
                v-tooltip.right="userName"
                class="w-7 h-7 rounded-full bg-[#171717] dark:bg-white text-white dark:text-[#171717] text-[10px] font-bold hover:opacity-75 transition-opacity"
                @click="userMenu.toggle($event)"
            >
                {{ userInitials }}
            </button>
            <Menu ref="userMenu" :model="userMenuItems" popup />
        </div>
    </aside>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Menu from 'primevue/menu';
import { useLayoutMode } from '@/composables/useLayoutMode.js';

const { mode, setMode } = useLayoutMode();

const page = usePage();
const userMenu = ref(null);

const userName = computed(() => page.props.auth?.user?.name ?? 'User');
const userInitials = computed(() =>
    userName.value.split(' ').map((n) => n[0]).join('').substring(0, 2).toUpperCase()
);

const navItems = computed(() => [
    {
        key:    'overview',
        label:  'Overview',
        icon:   'pi-home',
        active: mode.value === 'overview',
        action: () => { setMode('overview'); router.visit(route('overview')); },
    },
    {
        key:    'workspace',
        label:  'Workspace',
        icon:   'pi-th-large',
        active: mode.value === 'workspace',
        action: () => setMode('workspace'),
    },
    {
        key:    'dashboard',
        label:  'Dashboard',
        icon:   'pi-chart-bar',
        active: mode.value === 'dashboard',
        action: () => setMode('dashboard'),
    },
    { key: 'd1', divider: true },
    {
        key:    'notes',
        label:  'Notes',
        icon:   'pi-file-edit',
        active: false,
        action: () => router.visit(route('portal.module', { module: 'notes' })),
    },
    {
        key:    'tasks',
        label:  'Tasks',
        icon:   'pi-check-square',
        active: false,
        action: () => router.visit(route('portal.module', { module: 'tasks' })),
    },
    {
        key:    'calendar',
        label:  'Calendar',
        icon:   'pi-calendar',
        active: false,
        action: () => router.visit(route('portal.module', { module: 'calendar' })),
    },
    {
        key:    'mail',
        label:  'Mail',
        icon:   'pi-envelope',
        active: false,
        action: () => router.visit(route('portal.module', { module: 'mail' })),
    },
    { key: 'd2', divider: true },
    {
        key:    'settings',
        label:  'Settings',
        icon:   'pi-cog',
        active: false,
        action: () => router.visit(route('profile.edit')),
    },
]);

const userMenuItems = computed(() => [{
    label: userName.value,
    items: [
        { label: 'Profile',     icon: 'pi pi-user',      command: () => router.visit(route('profile.edit')) },
        { label: 'Dashboard',   icon: 'pi pi-chart-bar', command: () => setMode('dashboard') },
        { label: 'Workspace',   icon: 'pi pi-th-large',  command: () => setMode('workspace') },
        { separator: true },
        { label: 'Sign out',    icon: 'pi pi-sign-out',  command: () => router.post(route('logout')) },
    ],
}]);

function handleClick(item) {
    if (item.action) item.action();
}
</script>

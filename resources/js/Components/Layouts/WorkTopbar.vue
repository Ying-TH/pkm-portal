<template>
    <header class="flex items-center h-12 px-3 bg-surface-0 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700 shrink-0 gap-1">

        <!-- Back / nav toggle -->
        <Button
            severity="secondary" text rounded
            class="!w-8 !h-8 !p-0 shrink-0 text-surface-500 dark:text-surface-400"
            @click="$emit('nav-back')"
        >
            <i class="pi pi-chevron-left text-sm" />
        </Button>

        <!-- Brand -->
        <div class="flex items-center gap-1.5 shrink-0 mr-2">
            <span class="font-bold text-primary-600 dark:text-primary-400 text-base tracking-tight">PKM</span>
            <span class="text-surface-700 dark:text-surface-300 text-base font-medium">Work</span>
        </div>

        <!-- Primary nav tabs -->
        <nav class="flex items-center flex-1 min-w-0 gap-0.5 overflow-x-auto scrollbar-none">
            <template v-for="tab in navTabs" :key="tab.key">
                <!-- Tab with dropdown (Workspace) -->
                <template v-if="tab.hasDropdown">
                    <Button
                        severity="secondary" text
                        :class="[
                            '!h-8 !px-3 !py-0 text-sm font-medium whitespace-nowrap rounded',
                            activeTab === tab.key
                                ? 'bg-surface-100 dark:bg-surface-800 text-surface-900 dark:text-surface-0'
                                : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800',
                        ]"
                        @click="workspaceMenu.toggle($event)"
                    >
                        {{ tab.label }}
                        <i class="pi pi-chevron-down text-xs ml-1 opacity-60" />
                    </Button>
                    <Menu ref="workspaceMenu" :model="workspaceMenuItems" popup />
                </template>

                <!-- Regular tab -->
                <Button
                    v-else
                    severity="secondary" text
                    :class="[
                        '!h-8 !px-3 !py-0 text-sm font-medium whitespace-nowrap rounded',
                        activeTab === tab.key
                            ? 'bg-surface-100 dark:bg-surface-800 text-surface-900 dark:text-surface-0'
                            : 'text-surface-600 dark:text-surface-400 hover:bg-surface-100 dark:hover:bg-surface-800',
                    ]"
                    @click="$emit('change-tab', tab.key)"
                >
                    {{ tab.label }}
                </Button>
            </template>
        </nav>

        <!-- Right actions -->
        <div class="flex items-center gap-1 shrink-0 ml-auto pl-2">
            <!-- Search -->
            <Button
                v-tooltip.bottom="'Search  Ctrl+K'"
                severity="secondary" text rounded
                class="!w-8 !h-8 !p-0 text-surface-500 dark:text-surface-400"
                @click="$emit('open-search')"
            >
                <i class="pi pi-search text-sm" />
            </Button>

            <!-- Notifications -->
            <div class="relative">
                <Button
                    v-tooltip.bottom="'Notifications'"
                    severity="secondary" text rounded
                    class="!w-8 !h-8 !p-0 text-surface-500 dark:text-surface-400"
                >
                    <i class="pi pi-bell text-sm" />
                </Button>
                <span
                    class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-500 border-2 border-white dark:border-surface-900"
                />
            </div>

            <!-- Dark mode -->
            <Button
                v-tooltip.bottom="isDark ? 'Light mode' : 'Dark mode'"
                severity="secondary" text rounded
                class="!w-8 !h-8 !p-0 text-surface-500 dark:text-surface-400"
                @click="toggleDark"
            >
                <i :class="['pi text-sm', isDark ? 'pi-sun' : 'pi-moon']" />
            </Button>

            <!-- User avatar -->
            <Avatar
                :label="userInitials"
                shape="circle"
                class="!w-8 !h-8 cursor-pointer text-sm font-semibold bg-primary-500 text-white hover:ring-2 hover:ring-primary-400 transition-all shrink-0"
                @click="userMenu.toggle($event)"
            />
            <Menu ref="userMenu" :model="userMenuItems" popup />
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Menu from 'primevue/menu';

const props = defineProps({
    navTabs:          { type: Array,  default: () => [] },
    activeTab:        { type: String, default: '' },
    workspaces:       { type: Array,  default: () => [] },
    workspace:        { type: Object, default: null },
});

const emit = defineEmits(['change-tab', 'change-workspace', 'open-search', 'nav-back']);

const page = usePage();
const userMenu      = ref(null);
const workspaceMenu = ref(null);

const userName = computed(() => page.props.auth?.user?.name ?? 'User');
const userInitials = computed(() =>
    userName.value.split(' ').map((n) => n[0]).join('').substring(0, 2).toUpperCase()
);

// Dark mode
const isDark = ref(document.documentElement.classList.contains('dark'));
function toggleDark() {
    isDark.value = !isDark.value;
    document.documentElement.classList.toggle('dark', isDark.value);
    localStorage.setItem('pkm-dark-mode', isDark.value ? '1' : '0');
}

// Workspace dropdown
const workspaceMenuItems = computed(() =>
    props.workspaces.map((ws) => ({
        label:   ws.label,
        icon:    ws.key === props.workspace?.key ? 'pi pi-check' : undefined,
        command: () => emit('change-workspace', ws),
    }))
);

// User menu
const userMenuItems = computed(() => [
    {
        label: userName.value,
        items: [
            { label: 'Profile',  icon: 'pi pi-user',     command: () => router.visit(route('profile.edit')) },
            { label: 'Settings', icon: 'pi pi-cog',      command: () => router.visit(route('profile.edit')) },
            { separator: true },
            { label: 'Sign out', icon: 'pi pi-sign-out', command: () => router.post(route('logout')) },
        ],
    },
]);
</script>

<style scoped>
.scrollbar-none { scrollbar-width: none; }
.scrollbar-none::-webkit-scrollbar { display: none; }
</style>

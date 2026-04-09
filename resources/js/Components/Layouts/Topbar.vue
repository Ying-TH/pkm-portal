<template>
    <header class="flex items-center justify-between h-16 px-4 bg-surface-0 dark:bg-surface-900 border-b border-surface-200 dark:border-surface-700 shrink-0">
        <!-- Left: collapse toggle + breadcrumb -->
        <div class="flex items-center gap-3">
            <Button
                v-tooltip.bottom="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                severity="secondary"
                text
                rounded
                class="!w-9 !h-9 !p-0 justify-center text-surface-600 dark:text-surface-400"
                @click="$emit('toggle-sidebar')"
            >
                <i :class="['pi', collapsed ? 'pi-indent' : 'pi-outdent', 'text-base']" />
            </Button>

            <Breadcrumb
                v-if="breadcrumbs.length"
                :model="breadcrumbs"
                class="!bg-transparent !p-0 !border-0"
            >
                <template #item="{ item }">
                    <span
                        :class="[
                            'text-sm',
                            item.last
                                ? 'text-surface-900 dark:text-surface-0 font-medium'
                                : 'text-surface-500 dark:text-surface-400 hover:text-primary-500 cursor-pointer',
                        ]"
                        @click="!item.last && item.url && router.visit(item.url)"
                    >
                        {{ item.label }}
                    </span>
                </template>
                <template #separator>
                    <i class="pi pi-chevron-right text-xs text-surface-400 dark:text-surface-500 mx-1" />
                </template>
            </Breadcrumb>
        </div>

        <!-- Right: actions -->
        <div class="flex items-center gap-2">
            <!-- Global search -->
            <Button
                v-tooltip.bottom="'Search (Ctrl+K)'"
                severity="secondary"
                text
                rounded
                class="!w-9 !h-9 !p-0 justify-center text-surface-600 dark:text-surface-400"
                @click="$emit('open-search')"
            >
                <i class="pi pi-search text-base" />
            </Button>

            <!-- Dark mode toggle -->
            <Button
                v-tooltip.bottom="isDark ? 'Light mode' : 'Dark mode'"
                severity="secondary"
                text
                rounded
                class="!w-9 !h-9 !p-0 justify-center text-surface-600 dark:text-surface-400"
                @click="toggleDark"
            >
                <i :class="['pi', isDark ? 'pi-sun' : 'pi-moon', 'text-base']" />
            </Button>

            <!-- Notifications -->
            <div class="relative">
                <Button
                    v-tooltip.bottom="'Notifications'"
                    severity="secondary"
                    text
                    rounded
                    class="!w-9 !h-9 !p-0 justify-center text-surface-600 dark:text-surface-400"
                    @click="$emit('open-notifications')"
                >
                    <i class="pi pi-bell text-base" />
                </Button>
                <Badge
                    v-if="notificationCount > 0"
                    :value="notificationCount > 9 ? '9+' : notificationCount"
                    severity="danger"
                    class="!absolute !-top-1 !-right-1 !min-w-4 !h-4 !text-xs !leading-none"
                />
            </div>

            <Divider layout="vertical" class="!h-6 !mx-1" />

            <!-- User avatar menu -->
            <div class="relative">
                <Avatar
                    :label="userInitials"
                    shape="circle"
                    class="cursor-pointer bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300 font-semibold hover:ring-2 hover:ring-primary-400 transition-all"
                    size="normal"
                    @click="toggleUserMenu"
                />
                <Menu ref="userMenu" :model="userMenuItems" popup />
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Badge from 'primevue/badge';
import Breadcrumb from 'primevue/breadcrumb';
import Divider from 'primevue/divider';
import Menu from 'primevue/menu';

const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
    breadcrumbs: {
        type: Array,
        default: () => [],
    },
    notificationCount: {
        type: Number,
        default: 0,
    },
});

defineEmits(['toggle-sidebar', 'open-search', 'open-notifications']);

const page = usePage();
const userMenu = ref(null);

const userName = computed(() => page.props.auth?.user?.name ?? 'User');
const userInitials = computed(() =>
    userName.value
        .split(' ')
        .map((n) => n[0])
        .join('')
        .substring(0, 2)
        .toUpperCase(),
);

// Dark mode
const isDark = ref(document.documentElement.classList.contains('dark'));

function toggleDark() {
    isDark.value = !isDark.value;
    document.documentElement.classList.toggle('dark', isDark.value);
    localStorage.setItem('pkm-dark-mode', isDark.value ? '1' : '0');
}

// User dropdown menu
const userMenuItems = computed(() => [
    {
        label: userName.value,
        items: [
            {
                label: 'Profile',
                icon: 'pi pi-user',
                command: () => router.visit(route('profile.edit')),
            },
            {
                label: 'Settings',
                icon: 'pi pi-cog',
                command: () => router.visit(route('profile.edit')),
            },
            { separator: true },
            {
                label: 'Sign out',
                icon: 'pi pi-sign-out',
                command: () => router.post(route('logout')),
            },
        ],
    },
]);

function toggleUserMenu(event) {
    userMenu.value.toggle(event);
}
</script>

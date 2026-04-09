<template>
    <aside
        :class="[
            'flex flex-col h-screen bg-surface-0 dark:bg-surface-900 border-r border-surface-200 dark:border-surface-700 transition-all duration-300 overflow-hidden',
            collapsed ? 'w-16' : 'w-64',
        ]"
    >
        <!-- Brand -->
        <div class="flex items-center gap-3 px-4 h-16 border-b border-surface-200 dark:border-surface-700 shrink-0">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-500 shrink-0">
                <i class="pi pi-book text-white text-sm" />
            </div>
            <Transition name="fade">
                <span v-if="!collapsed" class="font-semibold text-surface-900 dark:text-surface-0 truncate">
                    PKM Work
                </span>
            </Transition>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-3">
            <template v-if="collapsed">
                <!-- Collapsed: icon-only buttons -->
                <div class="flex flex-col items-center gap-1 px-2">
                    <template v-for="item in navItems" :key="item.key">
                        <Button
                            v-tooltip.right="item.label"
                            :severity="isActive(item) ? 'primary' : 'secondary'"
                            :text="!isActive(item)"
                            :class="['w-10 h-10 !p-0 justify-center', isActive(item) ? '' : 'text-surface-600 dark:text-surface-400']"
                            @click="navigate(item)"
                        >
                            <i :class="['pi', item.icon, 'text-base']" />
                        </Button>

                        <Divider v-if="item.dividerAfter" class="!my-1" />
                    </template>
                </div>
            </template>

            <template v-else>
                <!-- Expanded: labelled menu -->
                <div class="flex flex-col gap-0.5 px-3">
                    <template v-for="item in navItems" :key="item.key">
                        <template v-if="item.children">
                            <!-- Group with children -->
                            <div class="mt-2 mb-1 px-2">
                                <span class="text-xs font-semibold uppercase tracking-wider text-surface-500 dark:text-surface-400">
                                    {{ item.label }}
                                </span>
                            </div>
                            <Button
                                v-for="child in item.children"
                                :key="child.key"
                                :severity="isActive(child) ? 'primary' : 'secondary'"
                                :text="!isActive(child)"
                                :class="[
                                    'w-full justify-start gap-2 !px-3 !py-2 text-sm',
                                    isActive(child) ? '' : 'text-surface-700 dark:text-surface-300',
                                ]"
                                @click="navigate(child)"
                            >
                                <i :class="['pi', child.icon]" />
                                <span>{{ child.label }}</span>
                                <Badge v-if="child.badge" :value="child.badge" class="ml-auto" severity="primary" />
                            </Button>
                        </template>

                        <template v-else>
                            <Button
                                :severity="isActive(item) ? 'primary' : 'secondary'"
                                :text="!isActive(item)"
                                :class="[
                                    'w-full justify-start gap-2 !px-3 !py-2 text-sm',
                                    isActive(item) ? '' : 'text-surface-700 dark:text-surface-300',
                                ]"
                                @click="navigate(item)"
                            >
                                <i :class="['pi', item.icon]" />
                                <span>{{ item.label }}</span>
                                <Badge v-if="item.badge" :value="item.badge" class="ml-auto" severity="primary" />
                            </Button>
                        </template>

                        <Divider v-if="item.dividerAfter" class="!my-1" />
                    </template>
                </div>
            </template>
        </nav>

        <!-- User Profile -->
        <div class="shrink-0 border-t border-surface-200 dark:border-surface-700 p-3">
            <div
                :class="['flex items-center gap-3 rounded-lg p-2 cursor-pointer hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors', collapsed ? 'justify-center' : '']"
                @click="$emit('profile-click')"
            >
                <Avatar
                    :label="userInitials"
                    shape="circle"
                    class="shrink-0 bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300 font-semibold"
                    size="normal"
                />
                <Transition name="fade">
                    <div v-if="!collapsed" class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-surface-900 dark:text-surface-0 truncate">{{ userName }}</p>
                        <p class="text-xs text-surface-500 dark:text-surface-400 truncate">{{ userEmail }}</p>
                    </div>
                </Transition>
            </div>
        </div>
    </aside>
</template>

<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Badge from 'primevue/badge';
import Divider from 'primevue/divider';

const props = defineProps({
    collapsed: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['profile-click']);

const page = usePage();

const userName  = computed(() => page.props.auth?.user?.name  ?? 'User');
const userEmail = computed(() => page.props.auth?.user?.email ?? '');
const userInitials = computed(() =>
    userName.value
        .split(' ')
        .map((n) => n[0])
        .join('')
        .substring(0, 2)
        .toUpperCase(),
);

const navItems = [
    {
        key: 'dashboard',
        label: 'Dashboard',
        icon: 'pi-home',
        route: 'portal.overview',
    },
    {
        key: 'knowledge',
        label: 'Knowledge',
        icon: 'pi-th-large',
        children: [
            { key: 'notes',    label: 'Notes',    icon: 'pi-file-edit',  route: 'notes.index' },
            { key: 'tags',     label: 'Tags',     icon: 'pi-tags',       route: 'tags.index' },
            { key: 'links',    label: 'Links',    icon: 'pi-link',       route: 'links.index' },
        ],
    },
    {
        key: 'tasks',
        label: 'Tasks',
        icon: 'pi-th-large',
        dividerAfter: true,
        children: [
            { key: 'todos',    label: 'To-Do',    icon: 'pi-check-square', route: 'todos.index' },
            { key: 'projects', label: 'Projects', icon: 'pi-briefcase',    route: 'projects.index' },
        ],
    },
    {
        key: 'settings',
        label: 'Settings',
        icon: 'pi-cog',
        route: 'profile.edit',
    },
];

function isActive(item) {
    if (!item.route) return false;
    try {
        return route().current(item.route);
    } catch {
        return false;
    }
}

function navigate(item) {
    if (!item.route) return;
    try {
        router.visit(route(item.route));
    } catch {
        // route not yet registered — silently ignore in dev
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

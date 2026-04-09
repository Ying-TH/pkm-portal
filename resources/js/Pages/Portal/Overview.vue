<template>
    <div class="min-h-full">
        <!-- Page header -->
        <div class="px-6 pt-6 pb-4 border-b border-[#ededed] dark:border-[#1f1f1f]">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-[#171717] dark:text-[#ededed]">
                        Good {{ greeting }}, {{ firstName }} 👋
                    </h1>
                    <p class="mt-0.5 text-[13px] text-[#666] dark:text-[#888]">
                        {{ todayLabel }} · Here's what's happening in your workspace.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Button
                        label="New Note"
                        icon="pi pi-plus"
                        size="small"
                        @click="newNoteVisible = true"
                    />
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">

            <!-- ── Stat cards ───────────────────────────────────── -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    v-for="stat in stats"
                    :key="stat.key"
                    class="flex items-center gap-4 p-4 bg-white dark:bg-surface-800 rounded-xl border border-surface-100 dark:border-surface-700 hover:border-primary-200 dark:hover:border-primary-800 transition-colors cursor-pointer group"
                    @click="stat.route && navigate(stat.route)"
                >
                    <div :class="['flex items-center justify-center w-10 h-10 rounded-lg shrink-0', stat.bg]">
                        <i :class="['pi', stat.icon, 'text-base', stat.color]" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-surface-900 dark:text-surface-0 leading-none">{{ stat.value }}</p>
                        <p class="text-xs text-surface-500 dark:text-surface-400 mt-0.5 truncate">{{ stat.label }}</p>
                    </div>
                </div>
            </div>

            <!-- ── Main grid ────────────────────────────────────── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Recent notes (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Recent Notes -->
                    <section class="bg-white dark:bg-surface-800 rounded-xl border border-surface-100 dark:border-surface-700">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-surface-100 dark:border-surface-700">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-file-edit text-primary-500 text-sm" />
                                <span class="font-semibold text-surface-900 dark:text-surface-0 text-sm">Recent Notes</span>
                            </div>
                            <Button label="View all" severity="secondary" text size="small" />
                        </div>
                        <ul class="divide-y divide-surface-50 dark:divide-surface-700">
                            <li
                                v-for="note in recentNotes"
                                :key="note.id"
                                class="flex items-start gap-3 px-5 py-3.5 hover:bg-surface-50 dark:hover:bg-surface-700/50 cursor-pointer group transition-colors"
                            >
                                <div class="w-8 h-8 rounded-lg bg-surface-100 dark:bg-surface-700 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="pi pi-file text-surface-400 text-xs" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-surface-900 dark:text-surface-0 truncate group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ note.title }}
                                    </p>
                                    <p class="text-xs text-surface-400 dark:text-surface-500 mt-0.5 line-clamp-1">{{ note.excerpt }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-1.5 shrink-0">
                                    <Tag :value="note.tag" :severity="note.tagSeverity" class="text-xs" />
                                    <span class="text-xs text-surface-400">{{ note.time }}</span>
                                </div>
                            </li>
                        </ul>
                    </section>

                    <!-- Tasks -->
                    <section class="bg-white dark:bg-surface-800 rounded-xl border border-surface-100 dark:border-surface-700">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-surface-100 dark:border-surface-700">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-check-square text-emerald-500 text-sm" />
                                <span class="font-semibold text-surface-900 dark:text-surface-0 text-sm">Today's Tasks</span>
                            </div>
                            <span class="text-xs text-surface-400">{{ completedTasks }}/{{ tasks.length }} done</span>
                        </div>
                        <ul class="divide-y divide-surface-50 dark:divide-surface-700">
                            <li
                                v-for="task in tasks"
                                :key="task.id"
                                class="flex items-center gap-3 px-5 py-3 hover:bg-surface-50 dark:hover:bg-surface-700/50 transition-colors cursor-pointer"
                                @click="task.done = !task.done"
                            >
                                <Checkbox v-model="task.done" :binary="true" />
                                <span :class="['flex-1 text-sm', task.done ? 'line-through text-surface-400' : 'text-surface-800 dark:text-surface-200']">
                                    {{ task.label }}
                                </span>
                                <Tag
                                    v-if="task.priority"
                                    :value="task.priority"
                                    :severity="task.priority === 'High' ? 'danger' : task.priority === 'Medium' ? 'warn' : 'secondary'"
                                    class="text-xs"
                                />
                            </li>
                        </ul>
                        <div class="px-5 py-3 border-t border-surface-50 dark:border-surface-700">
                            <Button
                                label="Add task"
                                icon="pi pi-plus"
                                severity="secondary"
                                text
                                size="small"
                                class="text-xs"
                            />
                        </div>
                    </section>
                </div>

                <!-- Right column (1/3 width) -->
                <div class="space-y-6">

                    <!-- Quick actions -->
                    <section class="bg-white dark:bg-surface-800 rounded-xl border border-surface-100 dark:border-surface-700 p-5">
                        <p class="text-sm font-semibold text-surface-900 dark:text-surface-0 mb-3">Quick Actions</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                v-for="action in quickActions"
                                :key="action.key"
                                class="flex flex-col items-center gap-1.5 p-3 rounded-lg bg-surface-50 dark:bg-surface-700 hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 transition-colors text-surface-600 dark:text-surface-400"
                            >
                                <i :class="['pi', action.icon, 'text-base']" />
                                <span class="text-xs font-medium">{{ action.label }}</span>
                            </button>
                        </div>
                    </section>

                    <!-- Activity feed -->
                    <section class="bg-white dark:bg-surface-800 rounded-xl border border-surface-100 dark:border-surface-700">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-surface-100 dark:border-surface-700">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-history text-orange-500 text-sm" />
                                <span class="font-semibold text-surface-900 dark:text-surface-0 text-sm">Activity</span>
                            </div>
                        </div>
                        <ul class="p-4 space-y-3">
                            <li
                                v-for="event in activity"
                                :key="event.id"
                                class="flex items-start gap-3"
                            >
                                <div :class="['w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5', event.bg]">
                                    <i :class="['pi', event.icon, 'text-xs', event.color]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-surface-700 dark:text-surface-300 leading-snug">
                                        <span class="font-medium">{{ event.actor }}</span>
                                        {{ event.action }}
                                        <span class="font-medium text-surface-900 dark:text-surface-100">{{ event.target }}</span>
                                    </p>
                                    <p class="text-xs text-surface-400 mt-0.5">{{ event.time }}</p>
                                </div>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import WorkLayout from '@/Layouts/WorkLayout.vue';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Checkbox from 'primevue/checkbox';
defineOptions({ layout: WorkLayout });

const page = usePage();

// ── Greeting ─────────────────────────────────────────────────────
const firstName = computed(() => {
    const name = page.props.auth?.user?.name ?? 'there';
    return name.split(' ')[0];
});

const greeting = computed(() => {
    const h = new Date().getHours();
    if (h < 12) return 'morning';
    if (h < 17) return 'afternoon';
    return 'evening';
});

const todayLabel = computed(() =>
    new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' })
);

// ── Stats ─────────────────────────────────────────────────────────
const stats = ref([
    { key: 'notes',    label: 'Notes',       value: 142, icon: 'pi-file-edit',    bg: 'bg-violet-100 dark:bg-violet-900/30', color: 'text-violet-600 dark:text-violet-400' },
    { key: 'tasks',    label: 'Open Tasks',  value: 8,   icon: 'pi-check-square', bg: 'bg-emerald-100 dark:bg-emerald-900/30', color: 'text-emerald-600 dark:text-emerald-400' },
    { key: 'links',    label: 'Saved Links', value: 57,  icon: 'pi-link',         bg: 'bg-sky-100 dark:bg-sky-900/30',     color: 'text-sky-600 dark:text-sky-400' },
    { key: 'tags',     label: 'Tags',        value: 23,  icon: 'pi-tags',         bg: 'bg-amber-100 dark:bg-amber-900/30', color: 'text-amber-600 dark:text-amber-400' },
]);

// ── Recent Notes ──────────────────────────────────────────────────
const recentNotes = ref([
    { id: 1, title: 'Pokemon Go strategy notes',   excerpt: 'Best routes for catching rare Pokemon in urban areas…', tag: 'Game',     tagSeverity: 'secondary', time: '2m ago' },
    { id: 2, title: 'Weekly retrospective — Apr',  excerpt: 'What went well: shipped the dashboard. What to improve…', tag: 'Work',   tagSeverity: 'primary',   time: '1h ago' },
    { id: 3, title: 'Book notes: Deep Work',        excerpt: 'Rule #1: Work Deeply. Ritualize your environment…',   tag: 'Reading', tagSeverity: 'warn',      time: '3h ago' },
    { id: 4, title: 'API design patterns',          excerpt: 'REST vs GraphQL — when to choose each approach…',     tag: 'Dev',     tagSeverity: 'info',      time: 'Yesterday' },
]);

// ── Tasks ─────────────────────────────────────────────────────────
const tasks = ref([
    { id: 1, label: 'Review PKM portal design',   done: true,  priority: 'High' },
    { id: 2, label: 'Build Overview page',         done: false, priority: 'High' },
    { id: 3, label: 'Set up note editor',          done: false, priority: 'Medium' },
    { id: 4, label: 'Write onboarding docs',       done: false, priority: 'Low' },
    { id: 5, label: 'Configure PrimeVue themes',   done: true,  priority: null },
]);

const completedTasks = computed(() => tasks.value.filter((t) => t.done).length);

// ── Quick actions ─────────────────────────────────────────────────
const quickActions = [
    { key: 'new-note',    label: 'New Note',     icon: 'pi-file-edit' },
    { key: 'new-task',    label: 'New Task',     icon: 'pi-check-square' },
    { key: 'save-link',   label: 'Save Link',    icon: 'pi-link' },
    { key: 'new-contact', label: 'New Contact',  icon: 'pi-user-plus' },
];

// ── Activity feed ─────────────────────────────────────────────────
const activity = ref([
    { id: 1, actor: 'You',   action: 'created note',  target: 'Pokemon Go strategy',  icon: 'pi-file-plus',  bg: 'bg-violet-100 dark:bg-violet-900/30', color: 'text-violet-600', time: '2 minutes ago' },
    { id: 2, actor: 'You',   action: 'completed task', target: 'Review portal design', icon: 'pi-check',      bg: 'bg-emerald-100 dark:bg-emerald-900/30', color: 'text-emerald-600', time: '1 hour ago' },
    { id: 3, actor: 'You',   action: 'saved link to', target: 'PrimeVue docs',         icon: 'pi-link',       bg: 'bg-sky-100 dark:bg-sky-900/30',     color: 'text-sky-600',    time: '3 hours ago' },
    { id: 4, actor: 'System', action: 'sent announcement', target: 'Read.',             icon: 'pi-info-circle', bg: 'bg-blue-100 dark:bg-blue-900/30',   color: 'text-blue-600',   time: 'Today, 9:00 AM' },
]);

// ── Modals ────────────────────────────────────────────────────────
const newNoteVisible = ref(false);

function navigate(routeName) {
    try { router.visit(route(routeName)); } catch { /* not registered */ }
}
</script>

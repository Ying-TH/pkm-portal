<template>
    <div class="flex flex-col h-screen overflow-hidden bg-white dark:bg-[#0a0a0a]">

        <!-- ══ WORKSPACE mode: horizontal mega-menu topbar ══════════ -->
        <WorkspaceMegaMenu
            v-if="mode === 'workspace'"
            @open-search="searchVisible = true"
        />

        <!-- ══ OVERVIEW + DASHBOARD mode: sidebar area ══════════════ -->
        <div class="flex flex-1 min-h-0 overflow-hidden">

            <!-- Icon strip — overview mode only -->
            <OverviewIconStrip
                v-if="mode === 'overview'"
                @open-search="searchVisible = true"
            />

            <!-- Full sidebar — dashboard mode only -->
            <WorkSidebar
                v-if="mode === 'dashboard'"
                :workspace-name="currentWorkspace.label"
                :workspaces="workspaces"
                @change-workspace="currentWorkspace = $event"
            />

            <!-- Content column (all modes) -->
            <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

                <!-- Announcement banner -->
                <AnnouncementBanner
                    v-if="announcementVisible"
                    :message="announcement"
                    @dismiss="announcementVisible = false"
                />

                <!-- Page content -->
                <main class="flex-1 overflow-y-auto">
                    <slot />
                </main>

                <!-- Footer -->
                <WorkFooter />
            </div>
        </div>

        <!-- ── Global: Toast ──────────────────────────────────────── -->
        <Toast position="bottom-right" />

        <!-- ── Global: Search dialog ─────────────────────────────── -->
        <Dialog
            v-model:visible="searchVisible"
            modal
            :style="{ width: '560px' }"
            :show-header="false"
            pt:root:class="!p-0 overflow-hidden"
        >
            <div class="flex items-center gap-3 px-4 py-3 border-b border-[#ededed] dark:border-[#2e2e2e]">
                <i class="pi pi-search text-[#999]" />
                <InputText
                    v-model="searchQuery"
                    placeholder="Search notes, tasks, links…"
                    class="flex-1 !border-0 !shadow-none !bg-transparent !p-0 text-[14px] focus:!ring-0"
                    autofocus
                    @keydown.esc="searchVisible = false"
                />
                <kbd class="px-2 py-0.5 text-[11px] bg-[#f5f5f5] dark:bg-[#1a1a1a] text-[#888] rounded border border-[#e0e0e0] dark:border-[#2a2a2a] font-mono">
                    Esc
                </kbd>
            </div>
            <div class="p-6 text-[13px] text-[#999] text-center">
                Start typing to search across your workspace…
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useLayoutMode } from '@/composables/useLayoutMode.js';

import OverviewIconStrip  from '@/Components/Layouts/OverviewIconStrip.vue';
import WorkspaceMegaMenu  from '@/Components/Layouts/WorkspaceMegaMenu.vue';
import WorkSidebar        from '@/Components/Layouts/WorkSidebar.vue';
import AnnouncementBanner from '@/Components/Layouts/AnnouncementBanner.vue';
import WorkFooter         from '@/Components/Layouts/WorkFooter.vue';
import Toast     from 'primevue/toast';
import Dialog    from 'primevue/dialog';
import InputText from 'primevue/inputtext';

const { mode } = useLayoutMode();

// ── Workspaces ────────────────────────────────────────────────────
const workspaces = [
    { key: 'pkm-work', label: 'PKM Work' },
    { key: 'personal', label: 'Personal' },
    { key: 'team',     label: 'Team' },
];
const currentWorkspace = ref(workspaces[0]);

// ── Announcement ──────────────────────────────────────────────────
const announcementVisible = ref(true);
const announcement = ref('Announcement   Read.');

// ── Search ────────────────────────────────────────────────────────
const searchVisible = ref(false);
const searchQuery   = ref('');

function handleGlobalKey(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        searchVisible.value = true;
    }
    if (e.key === 'f' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        searchVisible.value = true;
    }
}

onMounted(() => {
    if (localStorage.getItem('pkm-dark-mode') === '1') {
        document.documentElement.classList.add('dark');
    }
    window.addEventListener('keydown', handleGlobalKey);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleGlobalKey);
});
</script>

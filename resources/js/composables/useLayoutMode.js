import { ref } from 'vue';

// Module-level singleton — shared across all components without provide/inject
const mode = ref(localStorage.getItem('pkm-layout-mode') ?? 'overview');
const isDark = ref(document.documentElement.classList.contains('dark'));

function setMode(value) {
    mode.value = value;
    localStorage.setItem('pkm-layout-mode', value);
}

function toggleDark() {
    isDark.value = !isDark.value;
    document.documentElement.classList.toggle('dark', isDark.value);
    localStorage.setItem('pkm-dark-mode', isDark.value ? '1' : '0');
}

export function useLayoutMode() {
    return { mode, isDark, setMode, toggleDark };
}

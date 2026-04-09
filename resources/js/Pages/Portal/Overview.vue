<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const page = usePage();
const userName = computed(() => page.props.auth?.user?.name ?? "User");
</script>

<template>
    <AuthenticatedLayout :defaultSidebarOpen="false">
        <div class="p-6 max-w-4xl mx-auto">
            <!-- Welcome header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-light text-gray-800 mb-1">
                    Welcome, {{ userName }}!
                </h1>
                <p class="text-lg text-gray-400">To Work Pokemon World</p>
            </div>

            <!-- Ad Carousel -->
            <div>
                <!-- Indicators -->
            </div>

            <!-- Recent News -->

            <!-- Dashboard Widget placeholder -->
        </div>
    </AuthenticatedLayout>
</template>

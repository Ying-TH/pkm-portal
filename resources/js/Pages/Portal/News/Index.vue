<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import {
    newsArticles,
    newsCategories,
    typeColors,
} from "@/Services/mockData.js";

const selectedCategory = ref("All");
const sortOrder = ref("Newest First");

const filtered = computed(() =>
    selectedCategory.value === "All"
        ? newsArticles
        : newsArticles.filter((a) => a.category === selectedCategory.value),
);

// Sort: both orderings use the same data, just reversed (mock data is already newest-first)
const sorted = computed(() =>
    sortOrder.value === "Newest First"
        ? filtered.value
        : [...filtered.value].reverse(),
);
</script>

<template>
    <AuthenticatedLayout :defaultSidebarOpen="false">
        <div class="p-6">
            <!-- Ad banner -->
            <div
                class="flex gap-4 bg-white border border-gray-200 rounded-lg p-4 mb-6"
            >
                <div class="flex-1">
                    <p class="text-xs text-red-500 font-semibold mb-1">
                        EDUCBA
                    </p>
                    <p class="font-bold text-gray-800 text-sm mb-1">
                        Microsoft Azure: Cloud Solutions Mastery
                    </p>
                    <p class="text-xs text-gray-400 mb-3">
                        Learn to design, deploy, and manage Azure-based AI and
                        cloud applications.
                    </p>
                    <button
                        class="bg-blue-600 text-white text-xs px-4 py-1.5 rounded font-medium"
                    >
                        Enroll for free
                    </button>
                    <p class="text-xs text-gray-400 mt-1">
                        1,318 already enrolled
                    </p>
                </div>
                <div
                    class="w-32 h-24 bg-yellow-50 border border-dashed border-yellow-200 rounded flex items-center justify-center text-xs text-yellow-400 text-center p-2 flex-shrink-0"
                >
                    Picture Pokemon New Update
                </div>
            </div>

            <!-- Category tabs + sort -->
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <div class="flex items-center gap-1 flex-wrap">
                    <button
                        v-for="cat in newsCategories"
                        :key="cat"
                        @click="selectedCategory = cat"
                        class="px-3 py-1 text-xs rounded-full transition-colors"
                        :class="
                            selectedCategory === cat
                                ? 'bg-gray-800 text-white'
                                : 'text-gray-600 hover:bg-gray-100'
                        "
                    >
                        {{ cat }}
                    </button>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span>{{ sorted.length }} Articles</span>
                    <select
                        v-model="sortOrder"
                        class="text-xs border border-gray-200 rounded px-2 py-1 text-gray-600"
                    >
                        <option>Newest First</option>
                        <option>Oldest First</option>
                    </select>
                </div>
            </div>

            <!-- 3-column grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    v-for="article in sorted"
                    :key="article.id"
                    :href="route('portal.news.show', { id: article.id })"
                    class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow group cursor-pointer"
                >
                    <div class="relative h-36 bg-gray-100 overflow-hidden">
                        <img
                            :src="article.image"
                            :alt="article.title"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />
                        <span
                            class="absolute top-2 left-2 text-[10px] font-bold px-1.5 py-0.5 rounded"
                            :class="[
                                typeColors[article.type]?.bg,
                                typeColors[article.type]?.text,
                            ]"
                        >
                            {{ article.type }}
                        </span>
                        <span
                            v-if="article.isNew"
                            class="absolute top-2 right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded"
                            >NEW</span
                        >
                    </div>
                    <div class="p-3">
                        <p class="text-[10px] text-gray-400 mb-1">
                            {{ article.date }} · {{ article.readTime }}
                        </p>
                        <p
                            class="text-sm font-medium text-gray-800 line-clamp-2 group-hover:text-blue-600"
                        >
                            {{ article.title }}
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

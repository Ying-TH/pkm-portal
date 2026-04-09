<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { newsArticles, typeColors } from "@/Services/mockData.js";

const props = defineProps({ articleId: Number });

// Find article by id passed from the Laravel controller
const article = computed(() =>
    newsArticles.find((a) => a.id === props.articleId),
);

// Related articles via the relatedIds array
const related = computed(() =>
    (article.value?.relatedIds ?? [])
        .map((id) => newsArticles.find((a) => a.id === id))
        .filter(Boolean)
        .slice(0, 2),
);
</script>

<template>
    <AuthenticatedLayout :defaultSidebarOpen="false">
        <div class="max-w-2xl mx-auto p-6">
            <!-- Back -->
            <Link
                :href="route('portal.news')"
                class="flex items-center gap-1 text-xs text-gray-400 hover:text-blue-600 mb-4"
            >
                ← Back to home
            </Link>

            <template v-if="article">
                <!-- Hero image -->
                <div
                    class="bg-gray-50 rounded-lg overflow-hidden flex items-center justify-center h-48 mb-6 border border-gray-100"
                >
                    <img
                        :src="article.image"
                        :alt="article.title"
                        class="h-full w-full object-contain"
                    />
                </div>

                <!-- Meta -->
                <div class="flex items-center justify-between mb-2">
                    <span
                        class="text-[10px] font-bold px-2 py-0.5 rounded"
                        :class="[
                            typeColors[article.type]?.bg,
                            typeColors[article.type]?.text,
                        ]"
                    >
                        {{ article.type }}
                    </span>
                    <span class="text-xs text-gray-400"
                        >{{ article.date }} · {{ article.readTime }}</span
                    >
                </div>

                <h1 class="text-xl font-bold text-gray-900 mb-3">
                    {{ article.title }}
                </h1>

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">
                            {{ article.author }}
                        </p>
                        <p class="text-xs text-gray-400">{{ article.date }}</p>
                    </div>
                    <button
                        class="flex items-center gap-1 text-xs text-gray-500 hover:text-blue-600"
                    >
                        <!-- heart icon -->
                        {{ article.likes }}
                    </button>
                </div>

                <div
                    class="text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-4 mb-8"
                >
                    <p class="mb-2">
                        <span class="font-semibold text-gray-800"
                            >Dex Data</span
                        >
                    </p>
                    <p>{{ article.content }}</p>
                    <p class="mt-3 text-xs text-gray-400">Pokémon Biology</p>
                </div>

                <!-- Related Articles -->
                <div v-if="related.length">
                    <p
                        class="text-xs font-semibold uppercase text-gray-400 tracking-wider mb-3"
                    >
                        Related Articles
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <Link
                            v-for="rel in related"
                            :key="rel.id"
                            :href="route('portal.news.show', { id: rel.id })"
                            class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow group"
                        >
                            <div
                                class="relative h-28 bg-gray-100 overflow-hidden"
                            >
                                <img
                                    :src="rel.image"
                                    :alt="rel.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                />
                                <span
                                    class="absolute top-1 left-1 text-[9px] font-bold px-1 py-0.5 rounded"
                                    :class="[
                                        typeColors[rel.type]?.bg,
                                        typeColors[rel.type]?.text,
                                    ]"
                                >
                                    {{ rel.type }}
                                </span>
                                <span
                                    v-if="rel.isNew"
                                    class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-bold px-1 py-0.5 rounded"
                                    >NEW</span
                                >
                            </div>
                            <div class="p-2">
                                <p class="text-[9px] text-gray-400">
                                    {{ rel.date }} · {{ rel.readTime }}
                                </p>
                                <p
                                    class="text-xs font-medium text-gray-700 line-clamp-2 group-hover:text-blue-600"
                                >
                                    {{ rel.title }}
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>
            </template>

            <div v-else class="text-center py-16 text-gray-400">
                Article not found.
            </div>
        </div>
    </AuthenticatedLayout>
</template>

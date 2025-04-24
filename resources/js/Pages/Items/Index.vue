<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArrowLeftIcon, ArrowRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

defineProps({ items: Object });

const searchQuery = ref('');

watch(searchQuery, (newSearchQuery) => {
    window.location.href = `/items?search=${newSearchQuery}`;
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header + Create -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-3xl font-bold tracking-tight text-gray-800">Lost & Found Items</h1>
                <Link
                    href="/items/create"
                    class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl shadow hover:bg-indigo-700 transition"
                >
                    + Create Item
                </Link>
            </div>

            <!-- Search -->
            <div class="mb-6">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search items..."
                    class="w-full sm:w-1/2 px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <!-- Items Grid -->
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="item in items.data"
                    :key="item.id"
                    class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition duration-300"
                >
                    <h2 class="text-xl font-semibold text-gray-800 mb-1">{{ item.name }}</h2>
                    <p class="text-gray-600 mb-1"><strong>Location:</strong> {{ item.location }}</p>
                    <p class="text-gray-600 mb-1"><strong>Description:</strong> {{ item.description }}</p>
                    <span
                        class="inline-block mt-2 text-sm font-medium px-3 py-1 rounded-full"
                        :class="{
                            'bg-green-100 text-green-800': item.status === 'Found',
                            'bg-yellow-100 text-yellow-800': item.status === 'Lost'
                        }"
                    >
                        {{ item.status }}
                    </span>

                    <!-- Edit/Delete Buttons -->
                    <div v-if="item.can_edit" class="mt-4 flex gap-2">
                        <Link
                            :href="`/items/${item.id}/edit`"
                            class="px-4 py-2 text-sm font-semibold bg-blue-500 text-white rounded-xl shadow hover:bg-blue-600"
                        >
                            Edit
                        </Link>
                        <Link
                            :href="`/items/${item.id}`"
                            method="delete"
                            as="button"
                            class="px-4 py-2 text-sm font-semibold bg-red-500 text-white rounded-xl shadow hover:bg-red-600"
                            preserve-scroll
                        >
                            Delete
                        </Link>
                    </div>
                </div>
            </div>



            <!-- Pagination + Current Page Display -->
            <div class="mt-10 flex flex-col items-center gap-2">
                <div class="text-sm text-gray-500">
                    Page {{ items.current_page }} of {{ items.last_page }}
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <Link
                        href="/items?page=1"
                        class="inline-flex items-center gap-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg shadow transition"
                    >
                        <ChevronDoubleLeftIcon class="w-4 h-4" />
                        First
                    </Link>

                    <Link
                        :href="items.prev_page_url"
                        class="inline-flex items-center gap-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg shadow transition"
                    >
                        <ArrowLeftIcon class="w-4 h-4" />
                        Prev
                    </Link>

                    <Link
                        :href="items.next_page_url"
                        class="inline-flex items-center gap-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg shadow transition"
                    >
                        Next
                        <ArrowRightIcon class="w-4 h-4" />
                    </Link>

                    <Link
                        :href="`/items?page=${items.last_page}`"
                        class="inline-flex items-center gap-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg shadow transition"
                    >
                        Last
                        <ChevronDoubleRightIcon class="w-4 h-4" />
                    </Link>
                </div>
            </div>


        </div>
    </AuthenticatedLayout>
</template>

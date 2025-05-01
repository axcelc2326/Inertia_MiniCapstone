<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ArrowLeftIcon, ArrowRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon, MagnifyingGlassIcon, PlusIcon } from '@heroicons/vue/24/solid';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({ items: Object, search: String, status: String });

const searchQuery = ref(props.search || '');
const statusFilter = ref(props.status || 'all');

watch([searchQuery, statusFilter], debounce(([newSearch, newStatus]) => {
    router.get('/items', {
        search: newSearch,
        status: newStatus,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 sm:mb-0">
                        <div class="bg-[#FF2D20] p-3 rounded-xl shadow-md mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-800">Lost & Found Items</h1>
                    </div>
                    <Link
                        href="/items/create"
                        class="group flex items-center px-6 py-3 bg-[#FF2D20] hover:bg-[#e12216] text-white font-medium rounded-xl shadow-lg transition-all duration-300 transform hover:scale-105"
                    >
                        <PlusIcon class="w-5 h-5 mr-2" />
                        <span>Report Item</span>
                        <span class="ml-1 group-hover:ml-2 transition-all duration-300">→</span>
                    </Link>
                </div>

                <!-- Search + Filter -->
                <div class="mt-6 flex flex-col sm:flex-row gap-4">
                    <div class="relative w-full sm:w-1/2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search by name, location or description..."
                            class="w-full pl-10 pr-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:border-transparent transition-all"
                        />
                    </div>

                    <select
                        v-model="statusFilter"
                        class="w-full sm:w-1/4 px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:border-transparent transition-all appearance-none bg-white"
                    >
                        <option value="all">All Items</option>
                        <option value="found">Found Items</option>
                        <option value="lost">Lost Items</option>
                    </select>
                </div>
            </div>
            <!-- Items Grid -->
            <div v-if="props.items.data.length > 0" class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="item in props.items.data"
                    :key="item.id"
                    class="bg-white rounded-2xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:transform hover:scale-105"
                >
                    <!-- Status Badge - Positioned absolutely -->
                    <div class="relative">
                        <span
                            class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-sm font-medium shadow-md"
                            :class="{
                                'bg-green-500 text-white': item.status === 'Found',
                                'bg-yellow-500 text-white': item.status === 'Lost'
                            }"
                        >
                            {{ item.status }}
                        </span>

                        <div v-if="item.image" class="relative h-48 w-full">
                            <img
                                :src="`/storage/${item.image}`"
                                alt="Item Image"
                                class="w-full h-48 object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        </div>
                        <div v-else class="h-32 bg-gray-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-2">{{ item.name }}</h2>

                        <div class="flex items-center text-gray-600 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#FF2D20]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ item.location }}</span>
                        </div>

                        <div class="text-gray-600 mb-4">
                            <div class="font-medium text-[#FF2D20] mb-1">Description:</div>
                            <p class="text-sm line-clamp-2">{{ item.description }}</p>
                        </div>

                        <div class="border-t border-gray-100 pt-4 mt-2">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Reported by: {{ item.user?.name ?? 'Unknown' }}
                            </div>
                        </div>

                        <!-- Edit/Delete Buttons -->
                        <div v-if="item.can_edit" class="mt-4 flex gap-2">
                            <Link
                                :href="`/items/${item.id}/edit`"
                                class="flex-1 flex justify-center items-center px-4 py-2 text-sm font-semibold bg-blue-500 text-white rounded-xl shadow hover:bg-blue-600 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </Link>
                            <Link
                                :href="`/items/${item.id}`"
                                method="delete"
                                as="button"
                                class="flex-1 flex justify-center items-center px-4 py-2 text-sm font-semibold bg-[#FF2D20] text-white rounded-xl shadow hover:bg-[#e12216] transition-colors"
                                preserve-scroll
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Items Found - Enhanced -->
            <div v-else class="bg-white rounded-2xl shadow-md p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-1">No items found</h3>
                <p class="text-gray-500 mb-4">Try adjusting your search or filter criteria</p>
                <Link
                    href="/items/create"
                    class="inline-flex items-center px-5 py-2 bg-[#FF2D20] hover:bg-[#e12216] text-white font-medium rounded-xl shadow-md transition-colors"
                >
                    <PlusIcon class="w-5 h-5 mr-2" />
                    Report a new item
                </Link>
            </div>

            <!-- Pagination - Enhanced -->
            <div v-if="props.items.data.length > 0" class="mt-10">
                <div class="bg-white rounded-2xl shadow p-4 flex flex-col sm:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 sm:mb-0">
                        <span class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ props.items.from }}</span> to
                            <span class="font-medium">{{ props.items.to }}</span> of
                            <span class="font-medium">{{ props.items.total }}</span> results
                        </span>
                    </div>
                    <div class="flex flex-wrap justify-center gap-2">
                        <Link
                            :href="`/items?page=1&search=${searchQuery}&status=${statusFilter}`"
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition"
                            :class="{ 'opacity-50 cursor-not-allowed': props.items.current_page === 1 }"
                        >
                            <ChevronDoubleLeftIcon class="w-4 h-4" />
                        </Link>

                        <Link
                            v-if="props.items.prev_page_url"
                            :href="`${props.items.prev_page_url}&search=${searchQuery}&status=${statusFilter}`"
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition"
                        >
                            <ArrowLeftIcon class="w-4 h-4" />
                        </Link>

                        <!-- Page numbers -->
                        <div class="inline-flex items-center">
                            <template v-for="i in Math.min(props.items.last_page, 5)" :key="i">
                                <Link
                                    :href="`/items?page=${i}&search=${searchQuery}&status=${statusFilter}`"
                                    class="inline-flex items-center px-4 py-2 text-sm rounded-lg transition"
                                    :class="props.items.current_page === i ?
                                        'bg-[#FF2D20] text-white' :
                                        'bg-gray-100 hover:bg-gray-200 text-gray-800'"
                                >
                                    {{ i }}
                                </Link>
                            </template>
                        </div>

                        <Link
                            v-if="props.items.next_page_url"
                            :href="`${props.items.next_page_url}&search=${searchQuery}&status=${statusFilter}`"
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition"
                        >
                            <ArrowRightIcon class="w-4 h-4" />
                        </Link>

                        <Link
                            :href="`/items?page=${props.items.last_page}&search=${searchQuery}&status=${statusFilter}`"
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition"
                            :class="{ 'opacity-50 cursor-not-allowed': props.items.current_page === props.items.last_page }"
                        >
                            <ChevronDoubleRightIcon class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

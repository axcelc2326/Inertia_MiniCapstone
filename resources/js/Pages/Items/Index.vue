<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Link } from '@inertiajs/vue3';
    defineProps({ items: Object });
</script>

<template>
    <AuthenticatedLayout>
        <div>
            <h1 class="text-2xl font-bold mb-4">Items</h1>

            <!-- Create Item Button -->
            <div class="mb-4">
                <Link
                    href="/items/create"
                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700"
                >
                    Create Item
                </Link>
            </div>

            <!-- Item Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="item in items.data" :key="item.id" class="p-4 bg-white rounded shadow">
                    <h2 class="text-lg font-semibold">{{ item.name }}</h2>
                    <p>{{ item.location }}</p>
                    <p>{{ item.description }}</p>
                    <p class="text-sm text-gray-500">{{ item.status }}</p>
                </div>
            </div>

            <!-- Pagination Links -->
            <div class="mt-4 flex justify-center">
                <Link
                    v-if="items.prev_page_url"
                    :href="items.prev_page_url"
                    class="px-4 py-2 bg-gray-200 rounded mr-2"
                >
                    Prev
                </Link>
                <Link
                    v-if="items.next_page_url"
                    :href="items.next_page_url"
                    class="px-4 py-2 bg-gray-200 rounded"
                >
                    Next
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

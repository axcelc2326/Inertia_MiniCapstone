<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object,
    role: String,  // Add role to props
    lostCount: Number,
    foundCount: Number,
    returnedCount: Number,
});

const userRole = props.role || 'user';  // Use role from the props

const welcomeMessage = userRole === 'admin'
    ? {
        title: "Welcome, Administrator!",
        description: "You have full access to manage users, items, and system settings. Monitor platform activity and ensure smooth operations."
    }
    : {
        title: "Welcome to Lost & Found!",
        description: "Your central hub for managing lost and found items. Report items you've lost or found to help reconnect people with their belongings."
    };

// Separate stats based on role
const stats = userRole === 'admin'
    ? [
        { label: 'Total Lost Items', value: props.lostCount, color: 'text-red-600' },
        { label: 'Total Found Items', value: props.foundCount, color: 'text-green-600' },
    ]
    : [
        { label: 'My Lost Items', value: props.lostCount, color: 'text-red-600' },
        { label: 'My Found Items', value: props.foundCount, color: 'text-green-600' },
    ];

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-3xl font-bold tracking-tight text-gray-800">Dashboard</h1>
            </div>
                <!-- Welcome Section -->
                <div class="bg-white border border-gray-200 shadow-sm sm:rounded-xl overflow-hidden mb-6 relative">
                    <div class="p-8">
                        <div class="absolute top-0 right-0 w-64 h-64 transform translate-x-16 -translate-y-16 bg-gradient-to-br from-[#FF2D20] to-red-400 opacity-10 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-800 relative mb-2">{{ welcomeMessage.title }}</h2>
                        <p class="text-gray-600 text-lg relative max-w-3xl">{{ welcomeMessage.description }}</p>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div v-for="(stat, index) in stats" :key="index" class="bg-white border border-gray-200 shadow-sm rounded-xl p-6">
                        <div :class="['text-3xl font-bold', stat.color]">{{ stat.value }}</div>
                        <div class="text-gray-600 mt-1">{{ stat.label }}</div>
                    </div>
                </div>

                <!-- Admin's Control Panel (Visible only to admins) -->
                <div v-if="userRole === 'admin'" class="bg-white border border-gray-200 shadow-sm sm:rounded-xl overflow-hidden mb-6 relative">
                    <div class="p-8 flex flex-col md:flex-row justify-between md:items-center">
                        <div class="absolute bottom-0 left-0 w-64 h-64 transform -translate-x-32 translate-y-32 bg-gradient-to-tr from-[#FF2D20] to-red-400 opacity-5 rounded-full"></div>

                        <div class="mb-6 md:mb-0 md:pr-6 relative">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Manage Items</h3>
                            <p class="text-gray-600 max-w-xl">As an admin, you can view, edit, and manage all lost and found items in the system. You have full control over the platform.</p>
                        </div>
                        <Link :href="route('items.index')" class="bg-[#FF2D20] hover:bg-[#e12216] text-white px-6 py-3 rounded-full font-medium transition duration-300 flex items-center">
                            Go to Items
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L12.414 11H5a1 1 0 110-2h7.414l-2.707-2.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- User's Control Panel (Visible only to users) -->
                <div v-if="userRole === 'user'" class="bg-white border border-gray-200 shadow-sm sm:rounded-xl overflow-hidden mb-6 relative">
                    <div class="p-8 flex flex-col md:flex-row justify-between md:items-center">
                        <div class="absolute bottom-0 left-0 w-64 h-64 transform -translate-x-32 translate-y-32 bg-gradient-to-tr from-[#FF2D20] to-red-400 opacity-5 rounded-full"></div>

                        <div class="mb-6 md:mb-0 md:pr-6 relative">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">My Items</h3>
                            <p class="text-gray-600 max-w-xl">View and manage your own lost and found items. Report items you lost or found to help reconnect people with their belongings.</p>
                        </div>
                        <Link :href="route('items.index')" class="bg-[#FF2D20] hover:bg-[#e12216] text-white px-6 py-3 rounded-full font-medium transition duration-300 flex items-center">
                            Go to My Items
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L12.414 11H5a1 1 0 110-2h7.414l-2.707-2.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

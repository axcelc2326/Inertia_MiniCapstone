<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3'; // Import usePage hook
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

// Access page props using the usePage hook
const { props } = usePage();
const userRole = computed(() => props.auth.user.role); // Make sure to use computed to keep it reactive

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-white text-gray-900 transition-colors duration-300">
        <nav class="bg-white shadow-md sticky top-0 z-50 border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-6 sm:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Left Side - Logo and Nav -->
                    <div class="flex items-center gap-6">
                        <!-- Logo -->
                        <Link :href="route('dashboard')" class="flex items-center gap-2">
                            <svg class="h-8 w-auto text-black" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- SVG content placeholder -->
                            </svg>
                            <span class="text-xl font-bold tracking-wide text-[#FF2D20]">Lost&Found</span>
                        </Link>

                        <!-- Desktop Nav Links -->
                        <div class="hidden md:flex items-center gap-6">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                                class="text-gray-700 hover:text-[#FF2D20] transition font-medium">
                                Dashboard
                            </NavLink>
                            <NavLink :href="route('items.index')" :active="route().current('items.index')"
                                class="text-gray-700 hover:text-[#FF2D20] transition font-medium">
                                Items
                            </NavLink>
                        </div>
                    </div>

                    <!-- Right Side - User Dropdown -->
                    <div class="hidden md:flex items-center gap-4">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    type="button"
                                    class="flex items-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium hover:bg-gray-50 transition text-gray-700 hover:text-[#FF2D20]">
                                    <span>{{ props.auth.user.name }}</span>
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.044l3.71-3.814a.75.75 0 011.08 1.04l-4.24 4.36a.75.75 0 01-1.08 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <div class="py-1 bg-white rounded-md shadow-lg border border-gray-200">
                                    <DropdownLink :href="route('profile.edit')" class="hover:bg-gray-50 hover:text-[#FF2D20]">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button"
                                        class="hover:bg-gray-50 hover:text-[#FF2D20]">
                                        Log Out
                                    </DropdownLink>
                                </div>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="md:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-[#FF2D20] focus:outline-none focus:ring-2 focus:ring-[#FF2D20] transition-colors">
                            <svg v-if="!showingNavigationDropdown" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-show="showingNavigationDropdown" class="md:hidden bg-white shadow-inner border-t border-gray-200">
                <div class="px-4 pt-4 pb-2 space-y-2">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')"
                        class="hover:text-[#FF2D20] hover:bg-gray-50">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('items.index')" :active="route().current('items.index')"
                        class="hover:text-[#FF2D20] hover:bg-gray-50">
                        Items
                    </ResponsiveNavLink>
                </div>

                <div class="border-t border-gray-200 pt-4 pb-2 px-4">
                    <div class="text-base font-semibold">{{ props.auth.user.name }}</div>
                    <div class="text-sm text-gray-500 mb-3">{{ props.auth.user.email }}</div>
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"
                            class="hover:text-[#FF2D20] hover:bg-gray-50">
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button"
                            class="hover:text-[#FF2D20] hover:bg-gray-50">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header v-if="$slots.header" class="bg-[#f9fafb] shadow-sm border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-6 py-6 sm:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-6 sm:px-8 py-8">
            <slot />
        </main>
    </div>
</template>

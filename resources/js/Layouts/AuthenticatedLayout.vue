<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gradient-to-tr from-gray-50 to-white text-gray-800">
        <nav class="bg-white shadow-md sticky top-0 z-50">
            <div class="mx-auto max-w-7xl px-6 sm:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Left Side - Logo and Nav -->
                    <div class="flex items-center gap-6">
                        <!-- Logo -->
                        <Link :href="route('dashboard')" class="flex items-center space-x-2">
                            <ApplicationLogo class="h-9 w-auto" />
                            <span class="text-lg font-semibold text-gray-700">Lost & Found</span>
                        </Link>

                        <!-- Desktop Nav Links -->
                        <div class="hidden md:flex items-center gap-6">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>
                            <NavLink :href="route('items.index')" :active="route().current('items.index')">
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
                                    class="flex items-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium hover:bg-gray-100 transition"
                                >
                                    <span>{{ $page.props.auth.user.name }}</span>
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.044l3.71-3.814a.75.75 0 011.08 1.04l-4.24 4.36a.75.75 0 01-1.08 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="md:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="rounded-md p-2 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300">
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
            <div v-show="showingNavigationDropdown" class="md:hidden bg-white shadow-inner">
                <div class="px-4 pt-4 pb-2 space-y-2">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('items.index')" :active="route().current('items.index')">
                        Items
                    </ResponsiveNavLink>
                </div>

                <div class="border-t border-gray-200 pt-4 pb-2 px-4">
                    <div class="text-base font-semibold">{{ $page.props.auth.user.name }}</div>
                    <div class="text-sm text-gray-500 mb-3">{{ $page.props.auth.user.email }}</div>
                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header v-if="$slots.header" class="bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-6 py-4 sm:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-6 sm:px-8 py-6">
            <slot />
        </main>
    </div>
</template>

<template>
    <div class="min-h-screen bg-white text-gray-900 transition-colors duration-300">
        <!-- Navigation -->
        <header class="container mx-auto px-4 py-6 flex items-center justify-between border-b border-gray-200">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <svg
                    class="h-10 w-auto text-black"
                    viewBox="0 0 62 65"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <!-- Your SVG content here -->
                </svg>
                <span class="text-xl font-bold tracking-wide text-[#FF2D20]">Lost&Found</span>
            </div>

            <!-- Navigation Links -->
            <nav v-if="canLogin" class="flex gap-4 text-sm font-medium">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="text-gray-700 hover:text-[#FF2D20] transition"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="text-gray-700 hover:text-[#FF2D20] transition"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="text-gray-700 hover:text-[#FF2D20] transition"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="text-center py-24 bg-[#f9fafb]">
            <div class="container mx-auto px-4">
                <h1 class="text-5xl font-extrabold leading-tight mb-6">Lost Something? Found Something?</h1>
                <p class="text-lg text-gray-600 mb-10 max-w-2xl mx-auto">
                    Join our platform to report lost and found items. Help return items to their rightful owners today.
                </p>
                <div class="flex justify-center gap-4">
                    <Link
                        :href="route('login')"
                        class="bg-[#FF2D20] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#e12216] transition duration-300"
                    >
                        Get Started
                    </Link>
                    <Link
                        :href="route('register')"
                        class="border border-[#FF2D20] text-[#FF2D20] px-6 py-3 rounded-full font-semibold hover:bg-[#FF2D20] hover:text-white transition duration-300"
                    >
                        Join Now
                    </Link>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="py-24 bg-white">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">How It Works</h2>
                <div class="grid gap-8 md:grid-cols-3">
                    <div class="bg-[#f9fafb] p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                        <h3 class="text-xl font-semibold mb-2">1. Report a Lost Item</h3>
                        <p class="text-gray-600">Describe what you lost so others can help find it.</p>
                    </div>
                    <div class="bg-[#f9fafb] p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                        <h3 class="text-xl font-semibold mb-2">2. Report a Found Item</h3>
                        <p class="text-gray-600">Found something? List it so the owner can claim it.</p>
                    </div>
                    <div class="bg-[#f9fafb] p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                        <h3 class="text-xl font-semibold mb-2">3. Connect & Return</h3>
                        <p class="text-gray-600">Communicate safely and return the item to its owner.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean
})
</script>

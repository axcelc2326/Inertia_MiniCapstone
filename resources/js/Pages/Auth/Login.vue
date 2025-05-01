<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
            <p class="mt-2 text-gray-600">Log in to your <span class="text-[#FF2D20]">Lost&Found</span> account</p>
        </div>

        <div v-if="status" class="mb-4 rounded-lg bg-green-50 p-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Email" class="text-gray-700 font-medium" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring focus:ring-[#FF2D20] focus:ring-opacity-20"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <InputLabel for="password" value="Password" class="text-gray-700 font-medium" />
                </div>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring focus:ring-[#FF2D20] focus:ring-opacity-20"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div>
                <PrimaryButton
                    class="w-full justify-center py-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>

            <div class="text-center text-sm text-gray-600 mt-6">
                Don't have an account?
                <Link
                    :href="route('register')"
                    class="text-[#FF2D20] hover:underline font-medium"
                >
                    Register now
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

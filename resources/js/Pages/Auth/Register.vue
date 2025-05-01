<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
            <p class="mt-2 text-gray-600">Join the <span class="text-[#FF2D20]">Lost&Found</span> community</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="name" value="Name" class="text-gray-700 font-medium" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring focus:ring-[#FF2D20] focus:ring-opacity-20"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-gray-700 font-medium" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring focus:ring-[#FF2D20] focus:ring-opacity-20"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-gray-700 font-medium" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring focus:ring-[#FF2D20] focus:ring-opacity-20"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                    class="text-gray-700 font-medium"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring focus:ring-[#FF2D20] focus:ring-opacity-20"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div>
                <PrimaryButton
                    class="w-full justify-center rounded-full bg-[#FF2D20] py-3 font-semibold text-white hover:bg-[#e12216] focus:bg-[#e12216] focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:ring-offset-2 transition-colors duration-200"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Create Account
                </PrimaryButton>
            </div>

            <div class="text-center text-sm text-gray-600">
                Already have an account?
                <Link
                    :href="route('login')"
                    class="text-[#FF2D20] hover:underline font-medium"
                >
                    Log in
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

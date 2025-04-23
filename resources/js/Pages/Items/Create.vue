<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const form = useForm({
  name: '',
  description: '',
  location: '',
  status: 'lost',
});

const errors = ref({
  name: null,
  description: null,
  location: null,
  status: null,
});

const submitForm = () => {
  form.post(route('items.store'), {
    onError: (err) => {
      errors.value = err.errors;
    },
    onSuccess: () => {
      // Optionally redirect after successful creation
      window.location.href = route('items.index');
    },
  });
};
</script>

<template>
    <AuthenticatedLayout>
    <div>
      <h2 class="text-2xl font-bold mb-4">Create New Item</h2>
  
      <form @submit.prevent="submitForm">
        <!-- Name Field -->
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Item Name</label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
          />
          <span v-if="errors.name" class="text-red-500 text-xs">{{ errors.name }}</span>
        </div>
  
        <!-- Description Field -->
        <div class="mb-4">
          <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            v-model="form.description"
            id="description"
            rows="4"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
          ></textarea>
          <span v-if="errors.description" class="text-red-500 text-xs">{{ errors.description }}</span>
        </div>
  
        <!-- Location Field -->
        <div class="mb-4">
          <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
          <input
            v-model="form.location"
            type="text"
            id="location"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
          />
          <span v-if="errors.location" class="text-red-500 text-xs">{{ errors.location }}</span>
        </div>
  
        <!-- Status Field -->
        <div class="mb-4">
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select
            v-model="form.status"
            id="status"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
          >
            <option value="lost">Lost</option>
            <option value="found">Found</option>
          </select>
          <span v-if="errors.status" class="text-red-500 text-xs">{{ errors.status }}</span>
        </div>
  
        <!-- Submit Button -->
        <div class="flex justify-end">
          <button
            type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Submit
          </button>
        </div>
      </form>
    </div>
</AuthenticatedLayout>

  </template>
  
  <style scoped>
  /* Optional styling for form elements */
  </style>
  
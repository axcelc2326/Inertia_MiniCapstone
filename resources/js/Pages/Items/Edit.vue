<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  item: Object,
});

const form = useForm({
  name: props.item.name,
  description: props.item.description,
  location: props.item.location,
  status: props.item.status,
});

const submit = () => {
  form.put(route('items.update', props.item.id));
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold mb-6">Edit Item</h1>

      <form @submit.prevent="submit">
        <div class="grid gap-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              id="description"
              rows="4"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
          </div>

          <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
            <input
              v-model="form.location"
              type="text"
              id="location"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select
              v-model="form.status"
              id="status"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
              <option value="lost">Lost</option>
              <option value="found">Found</option>
            </select>
          </div>

          <button
            type="submit"
            class="mt-4 inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl shadow hover:bg-indigo-700 transition"
          >
            Update Item
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  description: '',
  location: '',
  status: 'lost',  // Default value
  image: null,  // To hold the uploaded image file
});

// Preview image functionality
const imagePreview = ref(null);

// Handle image input change
const handleImageChange = (event) => {
  const file = event.target.files[0];
  form.image = file;  // Store the image file in form data

  // Create preview URL
  if (file) {
    imagePreview.value = URL.createObjectURL(file);
  } else {
    imagePreview.value = null;
  }
};

const submit = () => {
  const formData = new FormData();
  formData.append('name', form.name);
  formData.append('description', form.description);
  formData.append('location', form.location);
  formData.append('status', form.status);

  // If an image is selected, append it to the formData
  if (form.image) {
    formData.append('image', form.image);
  }

  form.post(route('items.store'), {
    data: formData,
    onError: (err) => {
      console.log(err);
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
        <h1 class="text-3xl font-bold mb-6 text-gray-800 flex items-center">
          <span class="inline-block w-2 h-8 bg-red-600 mr-3 rounded"></span>
          Create New Item
        </h1>

        <form @submit.prevent="submit" class="space-y-6">
          <div class="grid md:grid-cols-2 gap-6">
            <!-- Left column -->
            <div class="space-y-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  id="name"
                  class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors"
                  placeholder="Enter item name"
                  required
                />
              </div>

              <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input
                  v-model="form.location"
                  type="text"
                  id="location"
                  class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors"
                  placeholder="Where was the item lost/found?"
                  required
                />
              </div>

              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <div class="relative">
                  <select
                    v-model="form.status"
                    id="status"
                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 pr-10 appearance-none bg-white transition-colors"
                    required
                  >
                    <option value="lost">Lost</option>
                    <option value="found">Found</option>
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right column -->
            <div class="space-y-6">
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                  v-model="form.description"
                  id="description"
                  rows="5"
                  class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors"
                  placeholder="Provide details about the item..."
                  required
                ></textarea>
              </div>

              <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Item Image</label>
                <div class="mt-1 flex items-center space-x-4">
                  <label class="flex flex-col items-center px-4 py-2 bg-white text-red-600 rounded-lg border border-red-300 cursor-pointer hover:bg-red-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="mt-1 text-sm">Upload Image</span>
                    <input
                      type="file"
                      id="image"
                      name="image"
                      @change="handleImageChange"
                      class="hidden"
                      accept="image/*"
                    />
                  </label>

                  <!-- Image preview -->
                  <div v-if="imagePreview" class="relative h-20 w-20 rounded-md overflow-hidden border border-gray-200">
                    <img :src="imagePreview" class="h-full w-full object-cover" />
                    <button
                      type="button"
                      class="absolute top-0 right-0 bg-red-600 text-white p-1 rounded-bl-md"
                      @click="imagePreview = null; form.image = null;"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="pt-4 flex justify-end">
            <button
              type="button"
              class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-200 transition-colors mr-3"
              onclick="window.history.back()"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-lg shadow-sm hover:bg-red-700 focus:ring-4 focus:ring-red-300 transition-colors flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Create Item
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

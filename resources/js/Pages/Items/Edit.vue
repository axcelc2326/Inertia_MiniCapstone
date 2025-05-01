<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';

const props = defineProps({
  item: Object,
});

const form = useForm({
  name: props.item.name,
  description: props.item.description,
  location: props.item.location,
  status: props.item.status,
  image: null,
});

const imagePreview = ref(props.item.image ? `/storage/${props.item.image}` : null);
const isDragging = ref(false);
const formSubmitted = ref(false);

function handleImageChange(event) {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function handleDrop(event) {
  event.preventDefault();
  isDragging.value = false;

  if (event.dataTransfer.files.length) {
    const file = event.dataTransfer.files[0];
    if (file.type.includes('image')) {
      form.image = file;
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreview.value = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  }
}

function handleDragOver(event) {
  event.preventDefault();
  isDragging.value = true;
}

function handleDragLeave() {
  isDragging.value = false;
}

function removeImage() {
  form.image = null;
  imagePreview.value = null;
}

function submit() {
  formSubmitted.value = true;

  const formData = new FormData();
  formData.append('name', form.name);
  formData.append('description', form.description);
  formData.append('location', form.location);
  formData.append('status', form.status);

  if (form.image) {
    formData.append('image', form.image); // must match your backend field name
  }

  Inertia.post(route('items.update', props.item.id), formData, {
    method: 'post',
    headers: {
      'X-HTTP-Method-Override': 'PUT', // tells Laravel to treat it as a PUT
    },
    onSuccess: () => {
      window.location.href = route('items.index');
    },
    onError: (errors) => {
      formSubmitted.value = false;
      console.log('Update failed with errors:', errors);
    },
  });
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Header with back button -->
      <div class="flex items-center mb-8">
        <a
          :href="route('items.index')"
          class="mr-4 p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Item</h1>
      </div>

      <!-- Main Form Card -->
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <form @submit.prevent="submit">
          <div class="grid gap-8 md:grid-cols-2">
            <!-- Left Column - Image and Image Upload -->
            <div class="space-y-6">
              <!-- Item Image Preview Section -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Item Image</label>

                <div
                  @dragover="handleDragOver"
                  @dragleave="handleDragLeave"
                  @drop="handleDrop"
                  class="relative border-2 border-dashed rounded-xl p-4 text-center cursor-pointer transition-all"
                  :class="isDragging ? 'border-[#FF2D20] bg-red-50' : 'border-gray-300 hover:border-[#FF2D20]'"
                >
                  <!-- Image preview if exists -->
                  <div v-if="imagePreview" class="relative mb-4">
                    <img
                      :src="imagePreview"
                      alt="Item preview"
                      class="h-64 w-full object-cover rounded-lg"
                    />
                    <button
                      @click.prevent="removeImage"
                      type="button"
                      class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-md hover:bg-red-50 transition-colors"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#FF2D20]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>

                  <!-- No image placeholder -->
                  <div v-else class="py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Drag and drop an image file here, or click to select</p>
                  </div>

                  <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    @change="handleImageChange"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                  />
                </div>
                <p class="mt-2 text-sm text-gray-500">Upload a clear image of the item (optional)</p>
              </div>

              <!-- Status Selector with Icons -->
              <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="grid grid-cols-2 gap-4">
                  <label
                    :class="[
                      'flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all',
                      form.status === 'lost'
                        ? 'border-yellow-500 bg-yellow-50 ring-2 ring-yellow-200'
                        : 'border-gray-200 hover:border-yellow-500'
                    ]"
                  >
                    <input
                      type="radio"
                      name="status"
                      value="lost"
                      v-model="form.status"
                      class="sr-only"
                    />
                    <div class="mr-3 p-2 bg-yellow-100 rounded-lg">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </div>
                    <div>
                      <div class="font-medium">Lost</div>
                      <div class="text-xs text-gray-500">Item is missing</div>
                    </div>
                  </label>

                  <label
                    :class="[
                      'flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all',
                      form.status === 'found'
                        ? 'border-green-500 bg-green-50 ring-2 ring-green-200'
                        : 'border-gray-200 hover:border-green-500'
                    ]"
                  >
                    <input
                      type="radio"
                      name="status"
                      value="found"
                      v-model="form.status"
                      class="sr-only"
                    />
                    <div class="mr-3 p-2 bg-green-100 rounded-lg">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </div>
                    <div>
                      <div class="font-medium">Found</div>
                      <div class="text-xs text-gray-500">Item was discovered</div>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Right Column - Text Fields -->
            <div class="space-y-6">
              <!-- Item Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    name="name"
                    placeholder="e.g. Blue Backpack"
                    class="pl-10 block w-full border border-gray-300 rounded-xl py-3 px-4 shadow-sm focus:ring-2 focus:ring-[#FF2D20] focus:border-transparent"
                    :class="{ 'border-red-300 focus:ring-red-500': form.errors.name }"
                    required
                  />
                </div>
                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
              </div>

              <!-- Location -->
              <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <input
                    v-model="form.location"
                    type="text"
                    id="location"
                    name="location"
                    placeholder="e.g. Library, 2nd floor"
                    class="pl-10 block w-full border border-gray-300 rounded-xl py-3 px-4 shadow-sm focus:ring-2 focus:ring-[#FF2D20] focus:border-transparent"
                    :class="{ 'border-red-300 focus:ring-red-500': form.errors.location }"
                    required
                  />
                </div>
                <p v-if="form.errors.location" class="mt-2 text-sm text-red-600">{{ form.errors.location }}</p>
              </div>

              <!-- Description -->
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <div class="relative">
                  <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                  </div>
                  <textarea
                    v-model="form.description"
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Provide details about the item including color, brand, distinguishing marks, etc."
                    class="pl-10 block w-full border border-gray-300 rounded-xl py-3 px-4 shadow-sm focus:ring-2 focus:ring-[#FF2D20] focus:border-transparent"
                    :class="{ 'border-red-300 focus:ring-red-500': form.errors.description }"
                    required
                  ></textarea>
                </div>
                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</p>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="mt-10 flex justify-end space-x-4">
            <a
              :href="route('items.index')"
              class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl shadow-sm hover:bg-gray-50 transition"
            >
              Cancel
            </a>
            <button
              type="submit"
              class="inline-flex items-center px-6 py-2.5 bg-[#FF2D20] hover:bg-[#e12216] text-white font-medium rounded-xl shadow-md transition relative overflow-hidden"
              :disabled="formSubmitted"
            >
              <span v-if="formSubmitted" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
              </span>
              <span v-else class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Update Item
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

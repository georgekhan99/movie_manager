<script setup lang="ts">
import { ref } from "vue";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";

// Mock Data
const placementName = "Near Toilet";
const durations = ref([
  { period: "01/01/2025 - 15/01/2025", status: "Invitation ว่างอยู่" },
  { period: "15/01/2025 - 29/01/2025", status: "Shreek 5" },
  { period: "29/01/2025 - 12/02/2025", status: "Shreek 5" },
  { period: "12/02/2025 - 26/02/2025", status: "Shreek 5" },
  { period: "26/02/2025 - 12/03/2025", status: "Invitation" },
  { period: "12/03/2025 - 26/03/2025", status: "Invitation" },
  { period: "26/03/2025 - 08/04/2025", status: "Pending / Invitation" },
  { period: "08/04/2025 - 23/04/2025", status: "Pending / Invitation" },
  { period: "23/04/2025 - 07/05/2025", status: "Pending / Invitation" },
]);

// Mock Action Function
const handleAction = (duration: string) => {
  alert(`Action clicked for: ${duration}`);
};
</script>

<template>
  <DefaultLayout>
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Placement: {{ placementName }}
      </h2>
      <nav>
        <ol class="flex items-center gap-2">
          <li><a class="font-medium" href="#">Cinemas /</a></li>
          <li class="font-medium text-primary">Placement Details</li>
        </ol>
      </nav>
    </div>

    <!-- Table -->
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="px-4 py-6 md:px-6 xl:px-7.5 flex justify-between">
        <h4 class="text-xl font-bold text-black dark:text-white">Placement Status</h4>
      </div>

      <!-- Table Header -->
      <div class="grid grid-cols-3 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-4 md:px-6 2xl:px-7.5">
        <div class="col-span-1 flex items-center">
          <p class="font-medium">Duration</p>
        </div>
        <div class="col-span-1 flex items-center">
          <p class="font-medium">Status</p>
        </div>
        <div class="col-span-1 flex items-center">
          <p class="font-medium">Action</p>
        </div>
      </div>

      <!-- Table Rows -->
      <template v-for="(duration, index) in durations" :key="index">
        <div
          class="grid grid-cols-3 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-4 md:px-6 2xl:px-7.5"
        >
          <!-- Duration -->
          <div class="col-span-1 flex items-center">
            <p class="text-sm font-medium text-black dark:text-white">
              {{ duration.period }}
            </p>
          </div>

          <!-- Status -->
          <div class="col-span-1 flex items-center">
            <p class="text-sm font-medium text-blue-500">
              {{ duration.status }}
            </p>
          </div>

          <!-- Action -->
          <div class="col-span-1 flex items-center">
            <button
              @click="handleAction(duration.period)"
              class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-400 transition duration-200"
            >
              Action
            </button>
          </div>
        </div>
      </template>

      <!-- Empty State -->
      <div v-if="durations.length === 0" class="text-center py-6 text-gray-500">
        No durations found.
      </div>
    </div>
  </DefaultLayout>
</template>

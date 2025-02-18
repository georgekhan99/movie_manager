<!-- <script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

// Get Inertia Props
const page = usePage();
const durations = ref<{ id: number; start_date: string }[]>(page.props.durations || []);

defineProps<{
  Movie_details: { id: number; movies_name: string; movies_release_date: string };
  PlacementList: Array<{ cinema_name: string; placements: { placement_id: number; placement_name: string }[] }>;
}>();

// Store selected bookings (placement + duration)
const selectedBookings = ref<{ placementId: number; durationId: number }[]>([]);
const isSubmitting = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

// Function to add/remove selections
const toggleBooking = (placementId: number, durationId: number) => {
  const index = selectedBookings.value.findIndex(
    (b) => b.placementId === placementId && b.durationId === durationId
  );

  if (index === -1) {
    selectedBookings.value.push({ placementId, durationId });
  } else {
    selectedBookings.value.splice(index, 1);
  }
};

// Function to submit bookings
const submitBookings = async () => {
  if (selectedBookings.value.length === 0) {
    alert("Please select at least one placement and duration.");
    return;
  }

  isSubmitting.value = true;
  successMessage.value = "";
  errorMessage.value = "";

  try {
    await axios.post("/bookings/create", {
      movie_id: page.props.Movie_details.id,
      selected_bookings: selectedBookings.value,
    });

    successMessage.value = "Booking successfully created!";
    selectedBookings.value = []; // Clear selections after success
  } catch (error) {
    errorMessage.value = error.response?.data?.error || "Booking failed.";
  } finally {
    isSubmitting.value = false;
  }
};

// Function to format date as "DD/MM/YYYY"
const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, "0");
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

// Function to generate "Start Date - End Date" format
const getDateRange = (startDate: string): string => {
  const start = new Date(startDate);
  const end = new Date(start);
  end.setDate(start.getDate() + 14); // Add 14 days to get the correct period
  return `${formatDate(startDate)} - ${formatDate(end.toISOString().split("T")[0])}`;
};
</script>

<template>
  <DefaultLayout title="Cinema List">
    <div class="p-6 bg-white rounded shadow-md">
      <div class="flex justify-between">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Booking Calendar</h1>
        <button
          :disabled="isSubmitting || selectedBookings.length === 0"
          @click="submitBookings"
          class="w-20 h-10 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-400 transition delay-100 disabled:bg-gray-400"
        >
          Submit
        </button>
      </div>

      <div v-if="successMessage" class="bg-green-200 text-green-700 p-3 rounded-md mb-4">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="bg-red-200 text-red-700 p-3 rounded-md mb-4">
        {{ errorMessage }}
      </div>

      <div class="flex justify-between mx-3">
        <h4 class="text-xl font-semibold my-3">Movie Name: {{ Movie_details.movies_name }}</h4>
        <h4 class="text-xl font-semibold my-3">Release Date: {{ Movie_details.movies_release_date }}</h4>
      </div>

      <div class="overflow-x-auto w-full">
        <table class="min-w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-200 text-gray-700">
              <th class="border border-gray-300 px-6 py-3 w-1/5">Cinema</th>
              <th class="border border-gray-300 px-6 py-3 w-1/5">Placement</th>
              <th
                v-for="(duration, index) in durations"
                :key="duration.id"
                class="border border-gray-300 px-6 py-3 text-center w-1/10"
              >
                {{ getDateRange(duration.start_date) }}
              </th>
            </tr>
          </thead>

          <tbody>
            <template v-for="(row, index) in PlacementList" :key="index">
              <template v-for="(placement, idx) in row.placements" :key="placement.placement_id">
                <tr>
                  <!-- Render cinema name only in the first row -->
                  <!-- <td
                    v-if="idx === 0"
                    :rowspan="row.placements.length"
                    class="border border-gray-300 px-6 py-4 font-medium text-gray-900 align-top"
                  >
                    {{ row.cinema_name }}
                  </td> -->
                  <!-- Placement Name -->
                  <!-- <td class="border border-gray-300 px-6 py-4 text-gray-700">
                    {{ placement.placement_name }}
                  </td> -->
                  <!-- Checkboxes for each duration -->
                  <!-- <td
                    v-for="(duration, i) in durations"
                    :key="duration.id"
                    class="border border-gray-300 px-6 py-4 text-center text-gray-600"
                  > -->
                    <!-- <input
                      type="checkbox"
                      @change="toggleBooking(placement.placement_id, duration.id)"
                    /> -->
                  <!-- </td> 
                </tr>
              </template>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </DefaultLayout>
</template> -->

<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";

// Get Inertia Props
const page = usePage();
const durations = ref<{ id: number; start_date: string }[]>(page.props.durations || []);

defineProps<{
  Movie_details: { id: number; movies_name: string; movies_release_date: string };
  PlacementList: Array<{ cinema_name: string; placements: { placement_id: number; placement_name: string; is_confirmed: number; accepted_movie?: string }[] }>;
}>();

const selectedBookings = ref<{ placementId: number; durationId: number }[]>([]);
const isSubmitting = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

const toggleBooking = (placementId: number, durationId: number, isConfirmed: number) => {
  if (isConfirmed) return;
  const index = selectedBookings.value.findIndex((b) => b.placementId === placementId && b.durationId === durationId);
  if (index === -1) {
    selectedBookings.value.push({ placementId, durationId });
  } else {
    selectedBookings.value.splice(index, 1);
  }
};

const submitBookings = async () => {
  if (selectedBookings.value.length === 0) {
    alert("Please select at least one placement and duration.");
    return;
  }
  isSubmitting.value = true;
  successMessage.value = "";
  errorMessage.value = "";
  try {
    await axios.post("/bookings/create", {
      movie_id: page.props.Movie_details.id,
      selected_bookings: selectedBookings.value,
    });
    successMessage.value = "Booking successfully created!";
    selectedBookings.value = [];
  } catch (error) {
    errorMessage.value = error.response?.data?.error || "Booking failed.";
  } finally {
    isSubmitting.value = false;
  }
};

const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  return `${date.getDate().toString().padStart(2, "0")}/${(date.getMonth() + 1).toString().padStart(2, "0")}/${date.getFullYear()}`;
};

const getDateRange = (startDate: string): string => {
  const start = new Date(startDate);
  const end = new Date(start);
  end.setDate(start.getDate() + 14);
  return `${formatDate(startDate)} - ${formatDate(end.toISOString().split("T")[0])}`;
};
</script>

<template>
  <DefaultLayout title="Cinema List">
    <div class="p-6 bg-white rounded shadow-md">
      <div class="flex justify-between mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Booking Calendar</h1>
        <button :disabled="isSubmitting || selectedBookings.length === 0" @click="submitBookings" class="w-20 h-10 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-400 transition delay-100 disabled:bg-gray-400">Submit</button>
      </div>

      <div v-if="successMessage" class="bg-green-200 text-green-700 p-3 rounded-md mb-4">{{ successMessage }}</div>
      <div v-if="errorMessage" class="bg-red-200 text-red-700 p-3 rounded-md mb-4">{{ errorMessage }}</div>

      <div class="flex justify-between mx-3">
        <h4 class="text-xl font-semibold my-3">Movie Name: {{ Movie_details.movies_name }}</h4>
        <h4 class="text-xl font-semibold my-3">Release Date: {{ Movie_details.movies_release_date }}</h4>
      </div>

      <div class="overflow-x-auto w-full">
        <table class="min-w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-200 text-gray-700">
              <th class="border border-gray-300 px-6 py-3 w-1/5">Cinema</th>
              <th class="border border-gray-300 px-6 py-3 w-1/5">Placement</th>
              <th v-for="(duration, index) in durations" :key="duration.id" class="border border-gray-300 px-6 py-3 text-center w-1/10">{{ getDateRange(duration.start_date) }}</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(row, index) in PlacementList" :key="index">
              <template v-for="(placement, idx) in row.placements" :key="placement.placement_id">
                <tr>
                  <td v-if="idx === 0" :rowspan="row.placements.length" class="border border-gray-300 px-6 py-4 font-medium text-gray-900 align-top">{{ row.cinema_name }}</td>
                  <td class="border border-gray-300 px-6 py-4 text-gray-700">{{ placement.placement_name }}</td>
                  <td v-for="(duration, i) in durations" :key="duration.id" class="border border-gray-300 px-6 py-4 text-center text-gray-600">
                    <span v-if="placement.is_confirmed" class="text-green-600 font-bold">{{ placement.accepted_movie || 'Confirmed' }}</span>
                    <input v-else type="checkbox" @change="toggleBooking(placement.placement_id, duration.id, placement.is_confirmed)" />
                  </td>
                </tr>
              </template>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </DefaultLayout>
</template>

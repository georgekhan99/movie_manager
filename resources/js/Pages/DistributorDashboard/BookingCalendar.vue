<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import axios from "axios";

// Get Inertia Props
const page = usePage();
const durations = ref<{ id: number; start_date: string }[]>(page.props.durations || []);

defineProps<{
  Movie_details: { id: number; movies_name: string; movies_release_date: string };
  PlacementList: Array<{ 
    cinema_name: string;
    placements: { 
      placement_id: number;
      placement_name: string;
      width_height: string;
      price: string;
      duration_id: number;
      is_confirmed: number;
      accepted_movie: string | null;
    }[] 
  }> ;
}>();

// Store selected bookings
const selectedBookings = ref<{ placementId: number; durationId: number }[]>([]);
const isSubmitting = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

// Function to clear checkboxes after submit
const clearSelections = () => {
  selectedBookings.value = [];
  document.querySelectorAll("input[type=checkbox]").forEach((checkbox) => {
    (checkbox as HTMLInputElement).checked = false;
  });
};

// Function to add/remove selections
const toggleBooking = (placementId: number, durationId: number, isConfirmed: number) => {
  if (isConfirmed) return; // Prevent selection if already confirmed

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
    clearSelections();
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
  end.setDate(start.getDate() + 14);
  return `${formatDate(startDate)} - ${formatDate(end.toISOString().split("T")[0])}`;
};

// Show Change Movie Modal 

// Ensure correct grouping of placements & durations
const getPlacementDurations = computed(() => {
  return page.props.PlacementList.map(cinema => ({
    cinema_name: cinema.cinema_name,
    placements: cinema.placements.reduce((acc, placement) => {
      const existingPlacement = acc.find((p: any) => p.placement_id === placement.placement_id);
      
      const durationInfo = {
        duration_id: placement.duration_id,
        is_confirmed: placement.is_confirmed,
        accepted_movie: placement.accepted_movie,
      };

      if (existingPlacement) {
        existingPlacement.durations.push(durationInfo);
      } else {
        acc.push({
          placement_id: placement.placement_id,
          placement_name: placement.placement_name,
          placement_width: placement.placement_width,
          placement_height: placement.placement_height,
          placement_price: placement.placement_price,
          durations: [durationInfo],
        });
      }
      return acc;
    }, [] as any[])
  }));
});

</script>

<template>
  <DefaultLayout title="Cinema List">
    <div class="p-6 bg-white rounded shadow-md">
      <div class="flex justify-between mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Booking Calendar</h1>
        <button 
          :disabled="isSubmitting || selectedBookings.length === 0" 
          @click="submitBookings" 
          class="w-20 h-10 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-400 transition delay-100 disabled:bg-gray-400">
          Submit
        </button>
      </div>

      <div v-if="successMessage" class="bg-green-200 text-green-700 p-3 rounded-md mb-4">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="bg-red-200 text-red-700 p-3 rounded-md mb-4">
        {{ errorMessage }}
      </div>

      <div class="overflow-x-auto w-full">
        <table class="min-w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-200 text-gray-700 text-center">
              <th class="border border-gray-300 px-6 py-4 whitespace-nowrap text-left">Placement</th>
              <th class="border border-gray-300 px-6 py-4 whitespace-nowrap text-center">W x H in cm</th>
              <th class="border border-gray-300 px-6 py-4 whitespace-nowrap text-center">Price</th>
              <th v-for="(duration, index) in durations" :key="duration.id" class="border border-gray-300 px-6 py-4">
                {{ getDateRange(duration.start_date) }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-gray-100">
              <td colspan="3" class="border border-gray-300  font-bold text-center">Deadline</td>
              <td v-for="(duration, index) in durations" :key="duration.id" class="border border-gray-300 py-3 text-center font-bold text-red-500">
                  {{ duration.start_date }}
              </td>
            </tr>
            <template v-for="(cinema, index) in getPlacementDurations" :key="cinema.cinema_name">
        
              <tr class="bg-black text-white text-sm">
                <td :colspan="4 + durations.length" class="px-4 py-2 font-bold">
                  {{ cinema.cinema_name }}
                </td>
              </tr>
          
              <template v-for="placement in cinema.placements" :key="placement.placement_id">
                <tr class="bg-white text-gray-700 text-sm">
                  <!-- Placement Name -->
                  <td class="border border-gray-300 px-4 py-3">
                    {{ placement.placement_name }}
                  </td>
                  <!-- Width x Height -->
                  <td class="border border-gray-300 px-4 py-3 text-center">
                    {{ placement.placement_width }} x {{ placement.placement_height }}
                  </td>
                  <!-- Price -->
                  <td class="border border-gray-300 px-4 py-3 text-center font-bold text-green-600">
                    {{ placement.placement_price }}
                  </td>

                  <!-- Duration Cells -->
                  <td v-for="duration in durations" :key="duration.id" class="border border-gray-300 px-4 py-3 text-center">
                    <span
                      v-if="placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed)"
                      class="bg-green-500 text-white px-2 py-1 rounded-md text-xs font-bold">
                      {{ placement.durations.find(d => d.duration_id === duration.id)?.accepted_movie }}
                    </span>
                    <span
                     
                      v-else-if="placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed)"
                      class="bg-yellow-400 text-black px-2 py-1 rounded-md text-xs font-bold">
                      Pending
                    </span>
                    <div v-else class="flex items-center justify-center space-x-2">
                      <span class="text-gray-500">Available</span>
                      <input
                        type="checkbox"
                        class="w-5 h-5"
                        @change="toggleBooking(placement.placement_id, duration.id, 0)"
                      />
                    </div>
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


<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import dayjs from 'dayjs'
import { router } from '@inertiajs/vue3';
// Get Inertia Props
const page = usePage();
const durations = ref<{ id: number; start_date: string }[]>(page.props.durations || []);

//Show Hide Movie Name
const showMovieName = ref(false);

defineProps<{
  Movie_details: { id: number; movies_name: string; movies_release_date: string };
  userMovie: { id: number; movies_name: string };
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
  }>;
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

const toggleBooking = (placementId: number, durationId: number, isPending: boolean) => {
  const index = selectedBookings.value.findIndex(
    (b) => b.placementId === placementId && b.durationId === durationId
  );

  if (index === -1) {
    // If not found, add to the list with Pending status
    selectedBookings.value.push({
      placementId,
      durationId,
      Pending: isPending
    });
  } else {
    // If found, remove it (toggle off)
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
    clearSelections();
    router.reload({ only: ['PlacementList', 'durations'] });
  } catch (error) {
    errorMessage.value = error.response?.data?.error || "Booking failed.";
  } finally {
    isSubmitting.value = false;
  }
};

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

const isMovieChangeOpen = ref<boolean>(false);
const selectedChange = ref<{ placementId: number; durationId: number } | null>(null);
const selectedMovieId = ref<number | null>(null);

const OpenChangMovieModal = (placement: any, durationId: number) => {
  selectedChange.value = {
    placementId: placement.placement_id,
    durationId: durationId,
  };
  isMovieChangeOpen.value = true;
};

const changeConfirmedMovie = async () => {
  if (!selectedChange.value || !selectedMovieId.value) {
    errorMessage.value = "Please select a movie before submitting.";
    return;
  }

  try {
    await axios.post("/bookings/change-movie", {
      placement_id: selectedChange.value.placementId,
      duration_id: selectedChange.value.durationId,
      new_movie_id: selectedMovieId.value,
    });

    // ปิด modal และรีเซ็ตค่าต่างๆ
    isMovieChangeOpen.value = false;
    selectedChange.value = null;
    selectedMovieId.value = null;

    successMessage.value = "Movie changed successfully!";
    errorMessage.value = "";
    
    // รีโหลดเฉพาะข้อมูลที่เปลี่ยน
    router.reload({ only: ["PlacementList"] });

  } catch (error: any) {
    console.error("Failed to change movie:", error);
    errorMessage.value = error.response?.data?.error || "Failed to change movie.";
    successMessage.value = "";
  }
};


const CloseChangMovieModal = () => {
  isMovieChangeOpen.value = false;
}

const getWeekNumber = (dateString: string): number => {
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return 0;

  const oneJan = new Date(date.getFullYear(), 0, 1);
  const days = Math.floor((date.getTime() - oneJan.getTime()) / (24 * 60 * 60 * 1000));
  return Math.ceil((days + oneJan.getDay() + 1) / 7);
};

const getDateRangeWithWeeks = (startDate: string): string => {
  const start = new Date(startDate);
  if (isNaN(start.getTime())) return "";

  const end = new Date(start);
  end.setDate(start.getDate() + 14);

  const startWeek = getWeekNumber(startDate);
  const endWeek = getWeekNumber(end.toISOString().split("T")[0]);

  return `${formatDateShort(startDate)} - ${formatDateShort(end.toISOString().split("T")[0])}\nWEEK ${startWeek} - ${endWeek}`;
};

const formatProductionDeadline = (dateString: string | null): string => {
  if (!dateString) return "-"; // Handle missing deadline
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, "0");
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  return `${day}/${month}`;
};

function formatDateShort(dateString) {
  if (!dateString) return "";
  const date = new Date(dateString);
  if (isNaN(date.getTime())) return "";
  return date.toLocaleDateString("en-GB", { day: "2-digit", month: "2-digit" });
}

const calculateGraceDate = (releaseDate: string): string => {
  return dayjs(releaseDate).subtract(35, 'day').format('YYYY-MM-DD');
};

</script>
<template>
  <DefaultLayout title="Cinema List">
    <div class="p-6 bg-white rounded shadow-md">
      <div class="flex justify-between mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Booking Calendar</h1>
        <button :disabled="isSubmitting || selectedBookings.length === 0" @click="submitBookings"
          class="w-20 h-10 bg-blue-500 text-white font-semibold rounded-xl hover:bg-blue-400 transition delay-100 disabled:bg-gray-400">
          Submit
        </button>
      </div>
      <div class="flex justify-between mb-5">
        <div class="w-full flex">
          <div class="w-4/12">
            <h2 class="text-2xl font-bold">Movie Name: {{ Movie_details.movies_name }}</h2>
          </div>
          <div class="w-4/12 flex justify-center">
            <div class="flex items-center mt-2">
              <h2 class="text-2xl font-bold"> Cinemas: </h2>
              <select class="input-style input-style h-4 w-full" name="" id="">
                <option value="Fokus Biograferne"> Fokus Biograferne </option>
              </select>
            </div>
          </div>
          <div class="w-4/12 flex justify-end font-bold">
            <h3 class="text-2xl"> Release Date: {{ Movie_details.movies_release_date }} </h3>
          </div>
        </div>
      </div>

      <div class="flex items-center space-x-2 mb-4 mx-3">
        <input id="toggle-other-movies" type="checkbox" v-model="showMovieName"
          class="w-5 h-5 text-blue-500 border-gray-300 rounded focus:ring-blue-400" />
        <label for="toggle-other-movies" class="text-gray-800 font-medium select-none text-sm">
          Show Movie Names
        </label>
      </div>
      <div v-if="successMessage" class="bg-green-200 text-green-700 p-3 rounded-md mb-4">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="bg-red-200 text-red-700 p-3 rounded-md mb-4">
        {{ errorMessage }}
      </div>

      <div class="overflow-x-auto w-full relative max-h-[700px]">
        <table class="relative min-w-full border-collapse border border-black">
          <thead class="sticky top-0 bg-[#8c8c8c] z-10">
            <tr class="text-white text-gray-700 text-center">
              <th class="border border-black px-6 py-4 whitespace-nowrap text-left">Placement</th>
              <th class="border border-black px-6 py-4 whitespace-nowrap text-center">W x H in cm</th>
              <th class="border border-black px-6 py-4 whitespace-nowrap text-center">Price</th>
              <th v-for="(duration, index) in durations" :key="duration.id"
                :class="index === 4 ? 'border border-black py-4 bg-[#677489] text-sm text-white' : 'border border-black bg-[#8c8c8c] text-white py-4 text-sm'">
                {{ getDateRangeWithWeeks(duration.start_date) }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="sticky top-17 bg-black z-50">
              <td colspan="3" class="border border-black font-bold text-center text-sm text-white">Deadline</td>
              <td v-for="(duration, index) in durations" :key="duration.id"
                class="border border-black py-3 text-center font-bold text-white text-sm">
                {{ formatProductionDeadline(duration.production_deadline) }}
              </td>
            </tr>
            <template v-for="(cinema, index) in getPlacementDurations" :key="cinema.cinema_name">
              <tr class="bg-white text-black text-sm">
                <td :colspan="4 + durations.length" class="px-4 py-2 font-bold">
                  {{ cinema.cinema_name }}
                </td>
              </tr>
              <template v-for="placement in cinema.placements" :key="placement.placement_id">
                <tr class="bg-white text-gray-700 text-sm">
                  <td class="border border-black px-4 py-3">
                    {{ placement.placement_name }}
                  </td>
                  <td class="border border-black px-4 py-3 text-center">
                    {{ placement.placement_width }} x {{ placement.placement_height }}
                  </td>
                  <td class="border border-black px-4 py-3 text-center font-bold text-black">
                    {{ placement.placement_price }}
                  </td>
                  <td v-for="duration in durations" :key="duration.id" class="border border-black px-4 py-3 text-center"
                    :class="{
                      'bg-[#c8c8c8] text-black': placement.durations.some(d => d.duration_id === duration.id && d.accepted_movie === 'N/A'), // N/A - Grey

                      'bg-green-500 text-white': placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed && d.accepted_movie !== 'N/A') &&
                        new Date(calculateGraceDate(Movie_details.movies_release_date)) > new Date(duration.start_date), // Light Green if BEFORE grace date

                      'bg-orange-400 text-white': placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed && d.accepted_movie !== 'N/A') &&
                        new Date(calculateGraceDate(Movie_details.movies_release_date)) <= new Date(duration.start_date), // Orange if AFTER grace date

                      'bg-yellow-200 text-black cursor-pointer': placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed), // Pending - Yellow & Clickable
                    }">
                    <template
                      v-if="placement.durations.some(d => d.duration_id === duration.id && d.accepted_movie === 'N/A')">
                      <div class="flex items-center justify-center space-x-2">
                        {{placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed) ? "Pending" :
                          "N/A"}}
                        <input
                          v-if="placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed) === true"
                          type="checkbox" class="w-5 h-5 ml-2"
                          :checked="selectedBookings.some(b => b.placementId === placement.placement_id && b.durationId === duration.id)"
                          @change="toggleBooking(placement.placement_id, duration.id, placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed))" />
                      </div>
                    </template>
                    <template v-else-if="placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed)">
               <!-- ✅ ถ้า Checkbox โชว์ชื่อหนังถูกเปิด -->
                    <div  @click="OpenChangMovieModal(placement, duration.id)" v-if="showMovieName" class="w-full h-full text-black cursor-pointer">
                      {{ placement.durations.find(d => d.duration_id === duration.id)?.accepted_movie || 'N/A' }}
                    </div>

                    <!-- 🔄 Logic เดิม เมื่อต้องเปรียบเทียบชื่อหนัง และให้คลิกเปลี่ยน -->
                    <template v-else>
                      <!-- คลิกเพื่อเปลี่ยนหนังถ้าอยู่ใน grace period -->
                      <div class="w-full h-full cursor-pointer text-black"
                        v-if="placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed && d.accepted_movie !== 'N/A')
                          && new Date(calculateGraceDate(Movie_details.movies_release_date)) > new Date(duration.start_date)"
                        @click="OpenChangMovieModal(placement, duration.id)">
                        {{ Movie_details.movies_name === placement.durations.find(d => d.duration_id === duration.id)?.accepted_movie
                          ? placement.durations.find(d => d.duration_id === duration.id)?.accepted_movie
                          : 'Confirm' }} 
                      </div>

                      <!-- กรณีแสดง Pending ถ้าไม่ใช่หนังเจ้าของ -->
                      <div class="w-full h-full text-black" v-else>
                        {{ placement.durations.find(d => d.duration_id === duration.id)?.accepted_movie === Movie_details.movies_name
                          ? placement.durations.find(d => d.duration_id === duration.id)?.accepted_movie
                          : 'Pending' }}
                      </div>
                    </template>
                  </template>
                    <template v-else>
                      <div class="flex items-center justify-center space-x-2">
                        <span class="text-black">Available</span>
                        <input type="checkbox" class="w-5 h-5"
                          @change="toggleBooking(placement.placement_id, duration.id, 0)" />
                      </div>
                    </template>
                  </td>
                </tr>
              </template>
            </template>
          </tbody>
        </table>
      </div>


      <!-- Change Movie Modal -->
      <div v-if="isMovieChangeOpen"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-50 transition z-90">
        <div class="bg-white w-96 rounded-lg shadow-lg p-6">
          <h2 class="text-xl font-semibold mb-4">Select a Movie</h2>

          <div class="space-y-2 max-h-60 overflow-y-auto">
            <select v-model="selectedMovieId" class="input-style w-full" name="usermovie" id="user-movie">
              <option disabled selected value="">Select Movie</option>
              <option :value="movie.id" v-for="movie in userMovie" :key="movie.id">
                {{ movie.movies_name }}
              </option>
            </select>
          </div>

          <div class="mt-4 flex justify-end space-x-2">
            <button @click="changeConfirmedMovie" class="px-4 py-2 bg-green-300 rounded-lg">Submit</button>
            <button @click="CloseChangMovieModal" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

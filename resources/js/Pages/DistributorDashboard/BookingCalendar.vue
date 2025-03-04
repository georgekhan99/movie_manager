<script setup lang="ts">
  import DefaultLayout from "../../Layouts/DefaultLayout.vue";
  import { usePage } from "@inertiajs/vue3";
  import { ref, computed, onMounted } from "vue";
  import axios from "axios";
  import dayjs from 'dayjs'
  // Get Inertia Props
  const page = usePage();
  const durations = ref<{ id: number; start_date: string }[]>(page.props.durations || []);
  
  defineProps<{
    Movie_details: { id: number; movies_name: string; movies_release_date: string };
    userMovie: {id: number; movies_name: string};
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
        console.log(acc);
        return acc;
      }, [] as any[])
    }));
  });
  
  const isMovieChangeOpen = ref<boolean>(false);
  
  const OpenChangMovieModal = (info) => {
    console.log(info)
    isMovieChangeOpen.value = true;
   
  }
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

const calculateGraceDate = (startDate: string): string => {
    const date = new Date(startDate);
    return dayjs(startDate).subtract(35, 'day').format('YYYY-MM-DD');
};

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
  
        <div v-if="successMessage" class="bg-green-200 text-green-700 p-3 rounded-md mb-4">
          {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="bg-red-200 text-red-700 p-3 rounded-md mb-4">
          {{ errorMessage }}
        </div>
  
        <div class="overflow-x-auto w-full">
          <table class="min-w-full border-collapse border border-gray-300">
            <thead>
              <tr class="bg-[#8c8c8c] text-white text-gray-700 text-center">
              <th class="border border-gray-300 px-6 py-4 whitespace-nowrap text-left">Placement</th>
              <th class="border border-gray-300 px-6 py-4 whitespace-nowrap text-center">W x H in cm</th>
              <th class="border border-gray-300 px-6 py-4 whitespace-nowrap text-center">Price</th>
              <th v-for="(duration, index) in durations" :key="duration.id" :class="index === 4 ? 'border border-grey-300 py-4 bg-[#677489] text-sm text-white' : 'border border-grey-300 bg-[#8c8c8c] text-white py-4 text-sm'">
               {{ getDateRangeWithWeeks(duration.start_date) }}
               

              </th>
            </tr>
            </thead>
            <tbody>
              <tr class="bg-black">
                <td colspan="3" class="border border-gray-300  font-bold text-center text-sm text-white">Deadline</td>
                <td v-for="(duration, index) in durations" :key="duration.id" :class="'border border-gray-300 py-3 text-center font-bold text-white text-sm'">
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
              <td v-for="duration in durations" :key="duration.id" 
                class="border border-gray-300 px-4 py-3 text-center"
                :class="{
                  'bg-[#c8c8c8] text-black': placement.durations.some(d => d.duration_id === duration.id && d.accepted_movie === 'N/A'),   // N/A - Grey
                  'bg-green-500 text-black': placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed && d.accepted_movie !== 'N/A'),  // Confirmed - Green
                  'bg-yellow-200 text-black cursor-pointer': placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed), // Pending - Yellow & Clickable
                  'bg-green-300 text-black': placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed) &&
                  calculateGraceDate(duration.start_date) !== duration.start_date, // Light Green if not grace date
                }"
                @click="placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed) ? OpenChangMovieModal(placement) : null"
              >
                <template v-if="placement.durations.some(d => d.duration_id === duration.id && d.accepted_movie === 'N/A')">
                {{ placement.durations.some(d => d.duration_id === duration.id && !d.is_confirmed) ? "Pending" : "N/A" }}
                </template>
                <template v-else-if="placement.durations.some(d => d.duration_id === duration.id && d.is_confirmed)">
                  {{ placement.durations.find(d => d.duration_id === duration.id)?.accepted_movie || 'Confirmed' }}
                </template>
                <template v-else>
                  <div class="flex items-center justify-center space-x-2">
                    <span class="text-gray-500">Available</span>
                    <input
                      type="checkbox"
                      class="w-5 h-5"
                      @change="toggleBooking(placement.placement_id, duration.id, 0)"
                    />
                  </div>
                </template>
              </td>
            </tr>
          </template> 
        </template>
            </tbody>
          </table>
        </div>
        </
        <!-- Change Movie Modal -->
        <div v-if="isMovieChangeOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 transition ">
          <div class="bg-white w-96 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Select a Movie</h2>
            
            <div class="space-y-2 max-h-60 overflow-y-auto">
              <select class="input-style" name="usermovie" id="user-movie">
                <option> Select Movie </option>
                <option value="" v-for="movie in userMovie" >{{ movie.movies_name }}</option>
              </select>
            </div>
  
            <div class="mt-4 flex justify-end space-x-2">
              <button @click="(()=> isMovieChangeOpen.value = false)" class="px-4 py-2 bg-green-300 rounded-lg"> Submit </button>
              <button @click="CloseChangMovieModal" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </DefaultLayout>
  </template>
  

<script setup lang="ts">
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";

// Define Interfaces
interface Duration {
  duration_id: number;
  start_date: string;
  delivery_date: string;
  status: string;
  movies_competing: string | null; // All movies competing for this slot
}

interface Placement {
  placement_id: number;
  placement_name: string;
}

// Get Inertia Props
const page = usePage();
const placement = ref<Placement>(page.props.placement);
const durations = ref<Duration[]>(page.props.durations);
const statusFilter = ref<string>(page.props.statusFilter || "all");

// State for modal confirmation
const showModal = ref(false);
const selectedDurationId = ref<number | null>(null);
const selectedMovie = ref<string | null>(null);
const competingMovies = ref<string[]>([]);

// Function to update filter via Inertia
const updateFilter = (status: string) => {
  router.get(`/bookings/placements/status/${placement.value.placement_id}`, { status });
};

// Open confirmation modal
const openConfirmModal = (duration: Duration) => {
  selectedDurationId.value = duration.duration_id;

  // ✅ Convert string into an array (fix for .join issue) duration.movies_competing.split(",")
  competingMovies.value = duration.movies_competing ?  JSON.parse(duration.movies_competing) : [];

  showModal.value = true;
};

// Confirm booking and cancel others
const confirmBooking = () => {
  if (!selectedDurationId.value || !selectedMovie.value) {
    alert("Please select a movie.");
    return;
  }

  console.log(selectedMovie.value);

  router.post(`/bookings/placements/confirm`, {
    placement_id: placement.value.placement_id,
    duration_id: selectedDurationId.value,
    selected_movie: selectedMovie.value,
  }, {
    onSuccess: () => {
      alert("Booking confirmed successfully! Competing bookings have been canceled.");
      showModal.value = false;
      router.reload();
    },
    onError: (error) => alert("Error: " + error)
  });
};

// Invite a new movie to book the slot
// const inviteToBooking = (durationId: number) => {
//   router.post(`/bookings/placements/invite`, { 
//     placement_id: placement.value.placement_id, 
//     duration_id: durationId 
//   }, {
//     onSuccess: () => alert("Invitation sent successfully!"),
//     onError: (error) => alert("Error: " + error)
//   });
// };
</script>

<template>
<pre>
  {{ durations }}
</pre>
  <DefaultLayout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <h2 class="text-title-md2 font-bold text-black dark:text-white">
        Placement: {{ placement.placement_name }}
      </h2>
      <nav>
        <ol class="flex items-center gap-2">
          <li>
            <a class="font-medium" href="/">Cinemas /</a>
          </li>
          <li class="font-medium text-primary">Placement Status</li>
        </ol>
      </nav>
    </div>

    <!-- Filter Tabs -->
    <div class="flex mb-4">
      <div role="tablist" class="tabs tabs-boxed">
        <a role="tab" :class="['tab', statusFilter === 'all' ? 'tab-active' : '']" @click="updateFilter('all')">All</a>
        <a role="tab" :class="['tab', statusFilter === 'pending' ? 'tab-active' : '']" @click="updateFilter('pending')">Pending</a>
        <a role="tab" :class="['tab', statusFilter === 'accepted' ? 'tab-active' : '']" @click="updateFilter('accepted')">Accepted</a>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto w-full">
      <table class="min-w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-200 text-gray-700">
            <th class="border border-gray-300 px-6 py-3 w-1/3">Duration</th>
            <th class="border border-gray-300 px-6 py-3 w-1/3">Status</th>
            <th class="border border-gray-300 px-6 py-3 w-1/3">Action</th>
          </tr>
        </thead>

        <tbody>
          <template v-for="(duration, index) in durations" :key="index">
            <tr class="hover:bg-gray-100">
              <!-- Duration -->
              <td class="border border-gray-300 px-6 py-4 text-gray-700">
                {{ duration.start_date }} - {{ duration.delivery_date }}
              </td>
              
              <!-- Status -->
              <td class="border border-gray-300 px-6 py-4 text-gray-700">
                <span v-if="duration.status.split(':')[0] === 'Accepted'" class="text-blue-500 font-semibold">
                  {{ duration.status }} 
                </span>
               
               <span v-else-if="duration.status === 'Pending'" class="text-yellow-500 font-semibold">
                  {{ duration.status }} 
                
                </span>
                <span v-else class="text-green-500 font-semibold">Available</span> 
              </td>

              <!-- Action -->
              <td class="border border-gray-300 px-6 py-4 text-center">
                <button
                  v-if="duration.status === 'Pending'"
                  class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-400 mr-2"
                  @click="openConfirmModal(duration)"
                >
                  Confirm
                </button>
                <button
                  v-if="duration.status === 'Available'"
                  class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-400"
                  @click="inviteToBooking(duration.duration_id)"
                >
                  Invite
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-md shadow-md w-96">
        <h2 class="text-lg font-semibold mb-4">Select a Movie</h2>
        <select v-model="selectedMovie" class="w-full p-2 border rounded-md">
          <option v-for="movie in competingMovies" :key="movie" :value="movie.id">
            {{ movie.movie_name }} 
          </option>
        </select>
        <div class="flex justify-end mt-4">
          <button class="bg-green-500 text-white px-4 py-2 rounded-md mr-2" @click="confirmBooking">
            Confirm
          </button>
          <button class="bg-gray-400 text-white px-4 py-2 rounded-md" @click="showModal = false">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

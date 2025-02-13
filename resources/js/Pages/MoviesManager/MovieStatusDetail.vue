<script setup lang="ts">
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";


interface Durations {
  duration_id:number;
  start_date: string;
  delivery_date: string;
  status: string;
  movie_name: string | null;
}

type Placements = {
  placement_id: string;
  placement_name: string;

}

// Get Placement & Durations
const page = usePage();
const placement = ref<Placements[]>(page.props.placement);
const durations = ref<Durations[]>(page.props.durations);
const statusFilter = ref(page.props.statusFilter || "all");

// Filter Change Handler
const updateFilter = (status: string) => {
  router.get(`/bookings/placements/status/${placement.value.placement_id}`, { status });
};

// Confirm Booking
const confirmBooking = (durationId: number) => {
  alert(`Confirming booking for duration ID: ${durationId}`);
};

// Decline Booking
const declineBooking = (durationId: number) => {
  alert(`Declining booking for duration ID: ${durationId}`);
};

// Invite Function (Not Implemented)
const inviteToBooking = (durationId: number) => {
  alert(`Inviting for duration ID: ${durationId}`);
};

//Add function To Add 15 Date Of the Duration

</script>

<template>
  {{ durations }}
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
        <a role="tab" :class="['tab', statusFilter === 'aceepted' ? 'tab-active' : '']" @click="updateFilter('accepted')">Aceepted</a>
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
                <span v-if="duration.status === 'Accepted'" class="text-blue-500 font-semibold">{{ duration.status }} ({{ duration.movie_name }}) </span>
                <span v-else-if="duration.status === 'Pending'" class="text-yellow-500 font-semibold">{{ duration.status }}</span>
                <span v-else class="text-green-500 font-semibold">Available</span>
              </td>

              <!-- Action -->
              <td class="border border-gray-300 px-6 py-4 text-center">
                <button
                  v-if="duration.status === 'Pending'"
                  class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-400 mr-2"
                  @click="confirmBooking(duration.duration_id)"
                >
                  Confirm
                </button>
                <button
                  v-if="duration.status === 'Pending'"
                  class="bg-yellow-500 text-white px-3 mx-3 py-1 rounded-md hover:bg-red-400"
                  
                >
                  Invite
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
  </DefaultLayout>
</template>

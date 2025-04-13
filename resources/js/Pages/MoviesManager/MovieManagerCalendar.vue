<script setup lang="ts">
import { ref, computed, watch } from "vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import isSameOrAfter from "dayjs/plugin/isSameOrAfter";
import isSameOrBefore from "dayjs/plugin/isSameOrBefore";
dayjs.extend(isSameOrAfter);
dayjs.extend(isSameOrBefore);

const page = usePage();
const calendarData = ref(page.props.calendarData || []);
const movieList = ref(page.props.movies || []);
const currentOffset = ref(parseInt(page.props.currentOffset) || 0);
const selectedMovieId = ref(page.props.selectedMovieId || "");

const modalVisible = ref(false);
const selectedBookings = ref([]);
const selectedPlacement = ref(null);
const selectedDuration = ref(null);
const isLoading = ref(false);

// ✅ Durations 15-day chunks with pagination (every +7 days)
const durations = computed(() => {
  const result = [];
  let start = dayjs().startOf("day").add(currentOffset.value * 7, "day");

  for (let i = 0; i < 7; i++) {
    const end = start.add(14, "day");
    const deadline = start.subtract(1, "day");
    result.push({
      id: i + 1,
      start_date: start.format("YYYY-MM-DD"),
      end_date: end.format("YYYY-MM-DD"),
      production_deadline: deadline.format("YYYY-MM-DD"),
    });
    start = end;
  }

  return result;
});

const formatRange = (start, end) =>
  `${dayjs(start).format("DD/MM")} - ${dayjs(end).format("DD/MM")}`;
const formatDeadline = (deadline) => dayjs(deadline).format("DD/MM");

const getBookings = (placement, duration) => {
  return (placement.bookings || []).filter((b) => {
    if (!b.duration_start_date) return false;
    const d = dayjs(b.duration_start_date);
    return d.isSameOrAfter(duration.start_date) &&
           d.isSameOrBefore(duration.end_date) &&
           (!selectedMovieId.value || b.movie_id == selectedMovieId.value);
  });
};

const cellColor = (placement, duration) => {
  const bookings = getBookings(placement, duration);
  if (bookings.some((b) => b.status === "accepted")) return "bg-green-200";
  if (bookings.length) return "bg-yellow-100";
  return "bg-white";
};

const openModal = (placement, duration) => {
  selectedBookings.value = getBookings(placement, duration);
  selectedPlacement.value = placement.placement_id;
  selectedDuration.value = duration;
  modalVisible.value = true;
};

const confirmBooking = (booking) => {
  console.log("🎯 Confirm booking:", booking);
  modalVisible.value = false;
};

const goToOffset = (offset) => {
  isLoading.value = true;
  router.get(route("adminpage.cinema.bookingCalendar"), {
    offset,
    movie_id: selectedMovieId.value
  }, {
    preserveScroll: true,
    onFinish: () => {
      isLoading.value = false;
    }
  });
};

watch(selectedMovieId, (val) => {
  router.get(route("adminpage.cinema.bookingCalendar"), {
    movie_id: val,
    offset: 0,
  }, { preserveState: false });
});
</script>

<template>
  <DefaultLayout title="Cinema Booking Calendar">
    <div class="p-6 bg-white rounded shadow relative">
      <div v-if="isLoading" class="absolute inset-0 bg-white bg-opacity-60 flex items-center justify-center z-50">
        <span class="text-lg font-semibold text-gray-600">Loading...</span>
      </div>

      <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Booking Calendar</h1>
      </div>

      <div class="flex justify-between gap-4 mb-3 my-5">
        <div>
          <select v-model="selectedMovieId" class="border border-gray-300 px-2 py-1 rounded text-sm">
          <option value=""> All Movies</option>
          <option v-for="movie in movieList" :key="movie.id" :value="movie.id">
            {{ movie.movies_name }}
          </option>
        </select>
        </div>

        <div class="space-x-3">
          <button class="text-sm px-3 py-1 bg-gray-200 rounded" @click="goToOffset(currentOffset - 1)">
            ← Previous
          </button>
          <button class="text-sm px-3 py-1 bg-gray-200 rounded" @click="goToOffset(currentOffset + 1)">
            Next →
          </button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 text-sm">
          <thead class="bg-gray-800 text-white">
            <tr>
              <th class="px-3 py-2 border">Placement</th>
              <th class="px-3 py-2 border">W x H</th>
              <th v-for="d in durations" :key="d.id" class="px-2 py-1 border text-center">
                {{ formatRange(d.start_date, d.end_date) }}
                <div class="text-gray-300 text-xs">DL: {{ formatDeadline(d.production_deadline) }}</div>
              </th>
            </tr>
          </thead>
          <tbody>
            <template v-for="group in calendarData" :key="group.group_name">
              <tr>
                <td :colspan="2 + durations.length" class="bg-gray-100 px-3 py-2 font-bold">
                   {{ group.group_name }}
                </td>
              </tr>
              <tr v-for="placement in group.placements" :key="placement.placement_id">
                <td class="border px-4 py-3">{{ placement.placement_name }}</td>
                <td class="border px-4 py-3 text-center">{{ placement.width }} x {{ placement.height }}</td>
                <td v-for="d in durations" :key="d.id" :class="['border px-4 py-3 text-center', cellColor(placement, d)]">
                  <template v-if="getBookings(placement, d).length">
                    <div v-for="booking in getBookings(placement, d)" :key="booking.movie_id"
                      class="truncate cursor-pointer" @click="openModal(placement, d)">
                      {{ booking.movie_name }}<span v-if="booking.status === 'pending'">(รอ)</span>
                    </div>
                  </template>
                  <template v-else>
                    <span class="text-gray-400">Available</span>
                  </template>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <!-- Modal -->
      <div v-if="modalVisible" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white w-[400px] p-6 rounded shadow">
          <h2 class="text-lg font-bold mb-4">Confirm Booking</h2>
          <div v-for="booking in selectedBookings" :key="booking.movie_id" class="border p-2 mb-2 flex justify-between">
            <span>{{ booking.movie_name }}</span>
            <button class="bg-green-500 text-white px-3 py-1 text-xs rounded hover:bg-green-600"
              @click="confirmBooking(booking)">
              Accept
            </button>
          </div>
          <div class="text-right">
            <button class="text-sm text-gray-600" @click="modalVisible = false">Close</button>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<style scoped>
th, td {
  min-width: 120px;
  white-space: nowrap;
}
</style>

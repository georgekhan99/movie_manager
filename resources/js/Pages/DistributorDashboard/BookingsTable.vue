<script setup lang="ts">
import { usePage, router } from "@inertiajs/vue3";
import { ref } from "vue";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";

const page = usePage();
const bookings = ref(page.props.bookings?.data || []);
const paginationLinks = ref(page.props.bookings?.links || []);

// Function to format date as "DD/MM/YYYY"
const formatDate = (dateString: string): string => {
  const date = new Date(dateString);
  const day = date.getDate().toString().padStart(2, "0");
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

// Function to update booking status
const updateStatus = async (bookingId: number, status: string) => {
  try {
    await router.put(`/bookings/${bookingId}/update-status`, { status });
    alert(`Booking updated to ${status} successfully!`);
    router.reload();
  } catch (error) {
    alert("Failed to update booking status.");
  }
};

// Function to cancel booking
const cancelBooking = async (bookingId: number) => {
  try {
    await router.post(`/bookings/${bookingId}/cancel`);
    alert("Booking cancelled successfully!");
    router.reload();
  } catch (error) {
    alert("Failed to cancel booking.");
  }
};
</script>

<template>
  <DefaultLayout title="Booking Management">
    <div class="rounded-sm border bg-white shadow dark:border-strokedark dark:bg-boxdark">
      <div class="py-6 px-4 md:px-6 xl:px-7.5">
        <h4 class="text-xl font-bold text-black dark:text-white">Booking Management</h4>
      </div>

      <!-- Table Header -->
      <div class="grid grid-cols-6 border-t py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
        <div class="col-span-2 flex items-center"><p class="font-medium">Movie Name</p></div>
        <div class="col-span-2 hidden items-center sm:flex"><p class="font-medium">Cinema Name</p></div>
        <div class="col-span-2 hidden items-center sm:flex"><p class="font-medium">Placement Name</p></div>
        <div class="col-span-1 hidden items-center sm:flex"><p class="font-medium">Status</p></div>
        <div class="col-span-1 hidden items-center sm:flex"><p class="font-medium">Duration</p></div>
      </div>

      <!-- Table Rows -->
      <div v-for="(booking, index) in bookings" :key="index"
           class="grid grid-cols-6 border-t py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
        <div class="col-span-2 flex items-center">
          <p class="text-sm font-medium text-black dark:text-white">{{ booking?.movies_name || 'N/A' }}</p>
        </div>
        <div class="col-span-2 hidden items-center sm:flex">
          <p class="text-sm font-medium text-black dark:text-white">{{ booking?.cinema_name || 'N/A' }}</p>
        </div>
        <div class="col-span-2 hidden items-center sm:flex">
          <p class="text-sm font-medium text-black">{{ booking?.placement_name || 'N/A' }}</p>
        </div>
        <div class="col-span-1 hidden items-center sm:flex">
          <p :class="{
            'text-sm font-bold text-yellow-600': booking?.booking_status === 'pending',
            'text-sm font-bold text-red-600': booking?.booking_status === 'cancelled',
            'text-sm font-bold text-green-600': booking?.booking_status === 'accepted'
          }">
            {{ booking?.booking_status || 'Pending' }}
          </p>
        </div>
        <div class="col-span-1 hidden items-center sm:flex">
          <p class="text-sm font-medium text-black dark:text-white">{{ booking?.start_date ? formatDate(booking.start_date) : 'N/A' }}</p>
        </div>
      </div>

      <!-- Pagination -->
      <div class="py-4 px-4 md:px-6 xl:px-7.5 flex justify-center">
   <nav class="flex space-x-2">
    <template v-for="(link, index) in paginationLinks" :key="index">
      <button
        v-if="link.url"
        @click.prevent="router.get(link.url)"
        class="px-4 py-2 rounded-2xl border border-gray-300 bg-white shadow-sm text-sm font-medium text-gray-700 hover:bg-primary hover:text-black transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary"
        :class="{ 'bg-blue-600 text-black shadow-md text-opacity-80': link.active }"
        v-html="link.label"
      ></button>
      <span v-else class="px-4 py-2 rounded-2xl border border-gray-300 bg-gray-100 text-gray-500 text-sm font-medium shadow-sm" v-html="link.label"></span>
    </template>
  </nav>
</div>

    </div>
  </DefaultLayout>
</template>

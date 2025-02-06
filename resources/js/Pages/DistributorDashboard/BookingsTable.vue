<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";

const page = usePage();
const bookings = ref(page.props.bookings || []);

// Booking statuses
const statusOptions = ["Pending", "Accepted", "Denied", "Invitation"];

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
    await axios.put(`/bookings/${bookingId}/update-status`, { status });
    alert(`Booking updated to ${status} successfully!`);
    window.location.reload(); // Refresh the page after update
  } catch (error) {
    alert("Failed to update booking status.");
  }
};

// Function to cancel booking
const cancelBooking = async (bookingId: number) => {
  try {
    await axios.post(`/bookings/${bookingId}/cancel`);
    alert("Booking cancelled successfully!");
    window.location.reload();
  } catch (error) {
    alert("Failed to cancel booking.");
  }
};
</script>

<template>
    <DefaultLayout title="Booking Management">
      <!-- Table Container -->
      <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
        <div class="py-6 px-4 md:px-6 xl:px-7.5">
          <h4 class="text-xl font-bold text-black dark:text-white">Booking Management</h4>
        </div>
  
        <!-- Table Header -->
        <div class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
          <div class="col-span-2 flex items-center">
            <p class="font-medium">Movie Name</p>
          </div>
          <div class="col-span-2 hidden items-center sm:flex">
            <p class="font-medium">Cinema Name</p>
          </div>
          <div class="col-span-2 hidden items-center sm:flex">
            <p class="font-medium">Placement Name</p>
          </div>
          <div class="col-span-1 hidden items-center sm:flex">
            <p class="font-medium">Status</p>
          </div>
          <div class="col-span-1 hidden items-center sm:flex">
            <p class="font-medium">Duration</p>
          </div>
        </div>
  
        <!-- Table Rows -->
        <div v-for="(booking, index) in bookings" :key="booking.id"
          class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
          
          <!-- Movie Name (Fix Here with `?` Operator) -->
          <div class="col-span-2 flex items-center">
            <p class="text-sm font-medium text-black dark:text-white">{{ booking?.movies_name || 'N/A' }}</p>
          </div>
  
          <!-- Cinema Name -->
          <div class="col-span-2 hidden items-center sm:flex">
            <p class="text-sm font-medium text-black dark:text-white">
              {{ booking?.cinema_name || 'N/A' }}
            </p>
          </div>
  
          <!-- Placement Name -->
          <div class="col-span-2 hidden items-center sm:flex">
            <p class="text-sm font-medium text-black dark:text-white">
              {{ booking?.placement_name || 'N/A' }}
            </p>
          </div>
  
          <!-- Booking Status -->
          <div class="col-span-1 hidden items-center sm:flex">
            <p class="text-sm font-medium text-black dark:text-white">
              {{ booking?.status || 'Pending' }}
            </p>
          </div>
  
          <!-- Duration -->
          <div class="col-span-1 hidden items-center sm:flex">
            <p class="text-sm font-medium text-black dark:text-white">
              {{ booking?.start_date ? formatDate(booking.start_date) : 'N/A' }}
            </p>
          </div>
        </div>
      </div>
    </DefaultLayout>
  </template>

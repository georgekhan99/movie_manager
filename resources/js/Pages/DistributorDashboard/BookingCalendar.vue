<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";

defineProps({
    releaseDate: Object,
    PlacementList: Object,
});
import { ref } from "vue";
const periods = ref([
  "01.01-15.01",
  "15.01-29.01",
  "29.01-12.02",
  "12.02-26.02",
  "01.01-15.01",
  "15.01-29.01",
  "29.01-12.02",
]);



</script>

<template>
    <DefaultLayout title="Cinema List">
        <div class="p-6 bg-white rounded shadow-md">
            <!-- Title -->
            <h1 class="text-3xl font-semibold text-gray-800 mb-6">
                Booking Calendar
            </h1>
            <h4 class="text-xl font-semibold">
                Movie Name: 
            </h4>
            <!-- Dynamic Table -->
            <div class="overflow-x-auto w-full">
  <table class="min-w-full border-collapse border border-gray-300">
    <!-- Table Header -->
    <thead>
      <tr class="bg-gray-200 text-gray-700">
        <th class="border border-gray-300 px-6 py-3 w-1/5">Cinema</th>
        <th class="border border-gray-300 px-6 py-3 w-1/5">Placement</th>
        <th
          v-for="period in periods"
          :key="period"
          class="border border-gray-300 px-6 py-3 text-center w-1/10"
        >
          {{ period }}
        </th>
      </tr>
    </thead>

    <!-- Table Body -->
    <tbody>
      <template v-for="(row, index) in PlacementList" :key="index">
        <tr class="hover:bg-gray-100">
          <!-- Cinema name spans all placement rows -->
          <td
            v-if="row.placement_name.length > 0"
            :rowspan="row.placement_name.length"
            class="border border-gray-300 px-6 py-4 font-medium text-gray-900 align-top"
          >
            {{ row.cinema_name }}
          </td>
          <td class="border border-gray-300 px-6 py-4 text-gray-700">
            {{ row.placement_name[0] }}
          </td>
          <td
            v-for="(status, i) in row.periodData"
            :key="i"
            class="border border-gray-300 px-6 py-4 text-center text-gray-600"
          >
            N/A
          </td>
        </tr>
        <!-- Additional rows for placements -->
        <tr v-for="(placement, idx) in row.placement_name.slice(1)" :key="idx" class="hover:bg-gray-100">
          <td class="border border-gray-300 px-6 py-4 text-gray-700">
            {{ placement }}
          </td>
          <td
            v-for="(status, i) in row.periodData"
            :key="i"
            class="border border-gray-300 px-6 py-4 text-center text-gray-600"
          >
           {{status || 'N/A'}}
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
        </div>
    </DefaultLayout>
</template>

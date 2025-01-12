<script setup lang="ts">
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import { ref } from "vue";
const periods = ref([
  "01.01-15.01",
  "15.01-29.01",
  "29.01-12.02",
  "12.02-26.02",
  "01.01-15.01",
  "15.01-29.01",
  "29.01-12.02",
  "12.02-26.02",
]);

// Rows dynamically created from file details
const rows = ref([
  {
    branch: "SF",
    cinema: "Cinema 1",
    placement: "By the toilet",
    periodData: ["x", null, null, null, "x", null, null, null],
  },
  {
    branch: "SF",
    cinema: "Cinema 2",
    placement: "Facadeplacering ud mod Kalvebod",
    periodData: ["x", null, null, "x", null, null, null, null],
  },
  {
    branch: "Cinemaplex",
    cinema: "Cinema 3",
    placement: "Foyer 1 Venstre",
    periodData: [null, "Minecraft", null, null, null, "Minecraft", null, null],
  },
]);
</script>

<template>
    <DefaultLayout title="Cinema List">
        <div class="p-6 bg-white rounded shadow-md">
    <!-- Title -->
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Booking Calendar</h1>

    <!-- Dynamic Table -->
    <div class="overflow-x-auto">
      <table class="table-auto w-full border-collapse border border-gray-300">
        <!-- Table Header -->
        <thead>
          <tr class="bg-gray-200 text-gray-700">
            <th class="border border-gray-300 px-4 py-2">Branch</th>
            <th class="border border-gray-300 px-4 py-2">Cinema</th>
            <th class="border border-gray-300 px-4 py-2">Placement</th>
            <th
              v-for="period in periods"
              :key="period"
              class="border border-gray-300 px-4 py-2 text-center "
            >
              {{ period }}
            </th>
          </tr>
        </thead>

        <!-- Table Body -->
        <tbody>
          <tr
            v-for="(row, index) in rows"
            :key="index"
            class="hover:bg-gray-100"
          >
            <!-- Branch -->
            <td class="border border-gray-300 px-4 py-2">{{ row.branch }}</td>
            <!-- Cinema -->
            <td class="border border-gray-300 px-4 py-2">{{ row.cinema }}</td>
            <!-- Placement -->
            <td class="border border-gray-300 px-4 py-2">{{ row.placement }}</td>
            <!-- Dynamic Period Data -->
            <td
              v-for="(status, i) in row.periodData"
              :key="i"
              class="border border-gray-300 px-4 py-2 text-center"
            >
              {{ status || 'N/A' }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import { defineProps, ref } from "vue";
import { router as Inertia } from '@inertiajs/vue3'
import dayjs from 'dayjs'

// Interface for your database durations
interface Duration {
    id: number;
    start_date: string;
}

// Editable date reference
const editingDate = ref<{ id: number; newDate: string | null }>({
    id: 0,
    newDate: null,
});
const showPopover = ref(false);

// Props: Your existing database data
const props = defineProps({
    duration: Array<Duration>, // Data fetched from the database
});

// Function to calculate the end date for a 15-day duration
const calculateEndDate = (startDate: string): string => {
    const startDateObj = new Date(startDate);
    const endDateObj = new Date(startDateObj);
    endDateObj.setDate(startDateObj.getDate() + 16); // Add 14 days for a 15-day period
    return endDateObj.toISOString().split("T")[0];
};

// Function to calculate the next start date (1 day after the previous end date)
const calculateNextStartDate = (endDate: string): string => {
    const endDateObj = new Date(endDate);
    endDateObj.setDate(endDateObj.getDate() - 2); // Add 1 day
    return endDateObj.toISOString().split("T")[0];
};

const calculateNextEndDate = (startDate: string): string => {
    const nextStart = new Date(startDate);
    nextStart.setDate(nextStart.getDate()+ 14); // 14 days to include the start date
    return nextStart.toISOString().split("T")[0];
};

// Function to calculate the grace date (35 days before start_date)
const calculateGraceDate = (startDate: string): string => {
    const date = new Date(startDate);
    // date.setDate(date.getDate() - 35); // Subtract 35 days
    // return date.toISOString().split("T")[0];
    return dayjs(startDate).subtract(35, 'day').format('YYYY-MM-DD');


};

const updateDate = () => {
    if (editingDate.value.id && editingDate.value.newDate) {
        Inertia.post(`/duration/update/${editingDate.value.id}`, {
            new_date: editingDate.value.newDate,
        });
        console.log(`${editingDate.value.newDate} and ID ${editingDate.value.id}`)
        showPopover.value = false;
    }

};

</script>

<template>

    <DefaultLayout title="Booking Calendar">
        <!-- Date -->
        <div class="p-6 bg-white rounded shadow-md">
            <!-- Title -->
            <div class="flex justify-between">
                <h1 class="text-3xl font-semibold text-gray-800 mb-6">
                    Duration
                </h1>
            </div>

            <!-- Dynamic Table -->
            <div class="overflow-x-auto">
                <table
                    class="table-auto w-full border-collapse border border-gray-300"
                >
                    <!-- Table Header -->
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="border border-gray-300 px-4 py-2">
                                Duration
                            </th>
                            <th class="border border-gray-300 px-4 py-2">
                                Delivery Date
                            </th>
                            <th class="border border-gray-300 px-4 py-2">
                                Production (Art Work) Deadline
                            </th>
                            <th class="border border-gray-300 px-4 py-2">
                                Grace Date
                            </th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody>
                        <tr
                            v-for="(row, index) in duration"
                            :key="row.id"
                            class="hover:bg-gray-100"
                        >
                            <!-- Calculate and display duration -->
                            <td
                                class="border border-gray-300 px-4 py-2 font-semibold"
                            >
                                {{
                                    new Date(
                                        index === 0
                                            ? new Date(row.start_date)
                                            : calculateNextStartDate(
                                                  calculateEndDate(
                                                      duration[index - 1]
                                                          .start_date
                                                  )
                                              )
                                    ).toLocaleDateString("en-GB")
                                }}
                                -
                                {{ 
                                new Date(calculateNextEndDate(row.start_date)).toLocaleDateString("en-GB")
                                }}
                            

                            </td>
                            <!-- Display delivery date -->
                            <td class="border border-gray-300 px-4 py-2">
                                {{
                                    new Date(
                                        index === 0
                                            ? row.start_date
                                            : calculateNextStartDate(
                                                  calculateEndDate(
                                                      duration[index - 1]
                                                          .start_date
                                                  )
                                              )
                                    ).toLocaleDateString("en-GB")
                                }}
                            </td>
                            <!-- Calculate and display production deadline -->
                            <td
                                class="border border-gray-300 px-4 py-2 flex items-center justify-between"
                            >
                            {{ new Date(row.production_deadline).toLocaleDateString("en-GB") }}

                                <div class="relative group">
                                    <button
                                        @click="() => { showPopover = true; editingDate.id = row.id; }"
                                        class="bg-yellow-400 w-20 rounded-full"
                                    >
                                        Edit
                                    </button>
                                <div v-if="showPopover && editingDate.id === row.id" 
                                    class="absolute right-0 mt-2 w-64 bg-white border rounded shadow-lg p-4 z-10">
                                    <input 
                                       type="date" 
                                       v-model="editingDate.newDate"
                                       class="w-full p-2 border rounded mb-2"
                                    >
                                    <div class="flex justify-end gap-2">
                                       <button 
                                          @click="updateDate"
                                          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                       >
                                          Save
                                       </button>
                                       <button 
                                          @click="showPopover = false"
                                          class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                                       >
                                          Cancel
                                       </button>
                                    </div>
                                </div>
                                </div>
                            </td>

                            <!-- Calculate and display grace date -->
                            <td class="border border-gray-300 px-4 py-2">
                                {{
                                    new Date(
                                        calculateGraceDate(
                                            index === 0
                                                ? row.start_date
                                                : calculateNextStartDate(
                                                      calculateEndDate(
                                                          duration[index - 1]
                                                              .start_date
                                                      )
                                                  )
                                        )
                                    ).toLocaleDateString("en-GB")
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </div>
    </DefaultLayout>
</template>

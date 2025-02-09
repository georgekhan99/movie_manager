<script setup lang="ts">
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";

// ✅ Get data from Inertia props (Cinema + Placements)
const page = usePage();
const cinema = ref<{ id: number; cinema_name: string }>(page.props.cinema || {});
const placements = ref<Array<{ 
    placement_id: number; 
    placement_name: string; 
    free_count: number; 
    booked_count: number; 
    pending_count: number; 
}>>(page.props.placements || []);
</script>

<template>
    <DefaultLayout>
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Placements in {{ cinema.cinema_name }}
            </h2>
        </div>

        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="px-4 py-6 md:px-6 xl:px-7.5 flex justify-between">
                <h4 class="text-xl font-bold text-black dark:text-white">Placement List</h4>
            </div>

            <!-- ✅ Table Header (Exactly as you requested) -->
            <div class="grid grid-cols-5 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-7 md:px-6 2xl:px-7.5">
                <div class="col-span-3 flex items-center"><p class="font-medium">Placement Name</p></div>
                <div class="col-span-1 flex items-center"><p class="font-medium">Free</p></div>
                <div class="col-span-1 flex items-center"><p class="font-medium">Booked</p></div>
                <div class="col-span-1 flex items-center"><p class="font-medium">Pending</p></div>
                <div class="col-span-1 flex items-center"><p class="font-medium">Action</p></div>
            </div>

            <!-- ✅ Table Body (Placements with Status) -->
            <template v-if="placements.length > 0">
                <template v-for="placement in placements" :key="placement.placement_id">
                    <div class="grid grid-cols-5 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-7 md:px-6 2xl:px-7.5">
                        <div class="col-span-3 flex items-center">
                            <p class="text-sm font-medium text-black dark:text-white">
                                {{ placement.placement_name }}
                            </p>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <p class="text-sm font-medium text-green-500">{{ placement.free_count }}</p>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <p class="text-sm font-medium text-blue-500">{{ placement.booked_count }}</p>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <p class="text-sm font-medium text-yellow-500">{{ placement.pending_count }}</p>
                        </div>
                        <div class="col-span-1 flex items-center gap-2">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-400">View</button>
                        </div>
                    </div>
                </template>
            </template>

            <div v-else class="text-center py-6 text-gray-500">
                No placements found for this cinema.
            </div>
        </div>
    </DefaultLayout>
</template>

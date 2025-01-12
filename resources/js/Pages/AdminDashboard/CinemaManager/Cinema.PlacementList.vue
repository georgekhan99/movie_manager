<script setup lang="ts">
import { defineProps } from 'vue';
import DefaultLayout from '../../../Layouts/DefaultLayout.vue'

interface CinemaPlacement {
  id: number;
  cinema_name: string;
  address_1: string;
  address_2: string;
  zip: string;
  city: string;
  state: string;
  country: string;
  company_id: number;
  created_at: string; // Consider using Date if you parse these into Date objects
  updated_at: string; // Consider using Date if you parse these into Date objects
  placement_name: string;
  placement_width: string;
  placement_height: string;
  placement_colors: string;
  placement_material: string;
}

defineProps<{
  pageId: number;
  CinemaData: CinemaPlacement[];
}>();

</script>

<template>
<DefaultLayout title="Placement List">
    <!-- Placements List -->
    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
  <div class="px-4 py-6 md:px-6 xl:px-7.5 flex justify-between">
    <h4 class="text-xl font-bold text-black dark:text-white">Cinemas List</h4>
    <div class="px-4 w-20 h-10 bg-blue-300 flex items-center justify-center rounded-md text-xl text-bold hover:opacity-[0.5]">
        Add
    </div>
  </div>

  <div class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
    <div class="col-span-3 flex items-center">
      <p class="font-medium">Placements Name</p>
    </div>
    <div class="col-span-2 hidden items-center sm:flex">
      <p class="font-medium">Dimensions</p>
    </div>
    <div class="col-span-1 flex items-center">
      <p class="font-medium">Color</p>
    </div>
    <div class="col-span-1 flex items-center">
      <p class="font-medium">Action</p>
    </div>
  </div>
  
  <div v-for="c in CinemaData" :key="c.id" class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
    <div class="col-span-3 flex items-center">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
        <p class="text-sm font-medium text-black dark:text-white">
          {{c.placement_name}}
        </p>
      </div>
    </div>
    <div class="col-span-2 hidden items-center sm:flex">
      <p class="text-sm font-medium text-black dark:text-white">{{c.placement_height}}x{{c.placement_height}}</p>
    </div>
    <div class="col-span-1 flex items-center">
      <p class="text-sm font-medium text-black dark:text-white">{{c.placement_colors}}</p>
    </div>
    <div class="col-span-1 flex items-center">
      <p class="text-sm font-medium text-black mr-3 bg-red-300 w-10 text-center rounded-full">Edit</p>
      <p class="text-sm font-medium text-black bg-red-300 w-15 text-center rounded-full">Delete</p>
    </div>
  </div>
</div>
    <!-- End Placements List -->


</DefaultLayout>
</template>
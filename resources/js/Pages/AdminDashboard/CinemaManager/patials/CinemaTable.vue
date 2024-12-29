<script setup lang="ts">
import { defineProps, ref } from 'vue';

interface Cinema {
  id: number;
  cinema_name: string;
  address_1: string;
  address_2: string;
  zip: string;
  city: string;
  state: string;
  country: string;
}

defineProps<{
  cinemalist: Cinema[];
}>();


</script>


<template>

  

<div class="max-w-full overflow-x-auto">
    <div class="min-w-[1170px]">
      <!-- table header start -->
      <div class="grid grid-cols-12 bg-[#F9FAFB] px-5 py-4 dark:bg-meta-4 lg:px-7.5 2xl:px-11 rounded-sm">
        <div class="col-span-5">
          <h5 class="font-medium text-[#637381] dark:text-bodydark">Cinema Name</h5>
        </div>

        <div class="col-span-6">
          <h5 class="font-medium text-[#637381] dark:text-bodydark">Type</h5>
        </div>

        <div class="col-span-1">
          <h5 class="font-medium text-[#637381] dark:text-bodydark">Action</h5>
        </div>
      </div>
      <!-- table header end -->

      <!-- table body start -->
      <div class="bg-white dark:bg-boxdark">
        <!-- table row item -->
        <slot>

        </slot>
      </div>
      <!-- table body end -->
    </div>
  </div>


</template>
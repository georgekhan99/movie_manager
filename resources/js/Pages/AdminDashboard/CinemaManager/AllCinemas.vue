<script setup lang="ts">
import DefaultLayout from '../../../Layouts/DefaultLayout.vue'
//@/layouts/DefaultLayout.vue
import CinemaInfomations from './patials/CinemaInfomations.vue'
import CinemaTable from './patials/CinemaTable.vue'
import { ref, defineProps } from 'vue'
import { Link } from "@inertiajs/vue3";
import { Icon } from '../../../../Constraint/ContrantIcon'
interface Cinema {
    id: number;
    cinema_name: string;
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
    company_id: number;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    cinemaList: Cinema[]
}>();
</script>

<template>
    <DefaultLayout title="Cinema List">
        <CinemaInfomations />
        <div class="mt-10">
            <CinemaTable :cinemalist="cinemaList">
    <!-- table body start -->
          <div class="bg-white dark:bg-boxdark">
        <div v-for="cinema in cinemaList" :key="cinema.id" 
             class="grid grid-cols-12 border-t border-[#EEEEEE] px-5 py-4 dark:border-strokedark lg:px-7.5 2xl:px-11">
          <div class="col-span-10">
            <p class="text-[#637381] dark:text-bodydark">{{ cinema.cinema_name }}</p>
          </div>

          <div class="col-span-2">
            <Link href="/" class="float-left text-primary">
              View
            </Link>
          </div>
        </div>
      </div>
        </CinemaTable>
        </div>
    </DefaultLayout>
</template>
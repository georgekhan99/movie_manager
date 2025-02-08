<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import CinemaTable from "../AdminDashboard/CinemaManager/patials/CinemaTable.vue";
import { Link, router } from "@inertiajs/vue3";
type Cinema = {
    id: number;
    cinema_name: string;
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
    image: string;
    Difference_Address: string | null;
    company_id: number;
    created_at: string;
    updated_at: string;
};

defineProps<{ cinemaList: Cinema[] }>();
</script>

<template>
    <DefaultLayout>
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
              <h2 class="text-title-md2 font-bold text-black dark:text-white">
                My Cinemas
              </h2>
              <nav>
                <ol class="flex items-center gap-2">
                  <li>
                    <a class="font-medium" href="index.html">Cinemas /</a>
                  </li>
                  <li class="font-medium text-primary">My Cinemas</li>
                </ol>
              </nav>
            </div>
        <div class="mt-10">
            <CinemaTable :cinemalist="cinemaList">
                <!-- table body start -->
                <div class="bg-white dark:bg-boxdark">
                    <div
                        v-for="cinema in cinemaList"
                        :key="cinema.id"
                        class="grid grid-cols-12 border-t border-[#EEEEEE] px-5 py-4 dark:border-strokedark lg:px-7.5 2xl:px-11"
                    >
                        <div class="col-span-10">
                            <p class="text-[#637381] dark:text-bodydark">
                                {{ cinema.cinema_name }}
                            </p>
                        </div>

                        <div class="col-span-2">
                            <Link
                                href="/dashboard"
                                class="float-left text-primary"
                            >
                                View
                            </Link>
                        </div>
                    </div>
                </div>
            </CinemaTable>
        </div>
    </DefaultLayout>
</template>

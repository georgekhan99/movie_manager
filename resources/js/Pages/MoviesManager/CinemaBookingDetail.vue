<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import CinemaTable from "../AdminDashboard/CinemaManager/patials/CinemaTable.vue";
import { Link, router } from "@inertiajs/vue3";
</script>

<template>
    <DefaultLayout>
        <div
            class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Placement Detail
            </h2>
            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium" href="index.html"
                            >My Cinemas /</a
                        >
                    </li>
                    <li class="font-medium text-primary">Placement Detail</li>
                </ol>
            </nav>
        </div>
        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div class="px-4 py-6 md:px-6 xl:px-7.5 flex justify-between">
                <h4 class="text-xl font-bold text-black dark:text-white">
                    Placements List
                </h4>
            </div>
            <!-- Placement Type Sort -->

            <!-- <div class="flex">
                <div class="flex items-center justify-center w-30 h-10 bg-red-300">
                    Free
                </div>
            </div> -->
            
            <div
                class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <p class="font-medium">Placements Name</p>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="font-medium">Free</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Bookings</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Pending</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Action</p>
                </div>
            </div>
            <!--v-if-->
            <div
                class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center"
                    >
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            facadesddddddd
                        </p>
                    </div>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="text-sm font-medium text-black dark:text-white">
                        320ddx320dd
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-sm font-medium text-black dark:text-white">
                        blue
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p
                        class="text-sm font-medium text-black mr-3 bg-red-300 w-10 text-center rounded-full"
                    >
                        Edit
                    </p>
                    <p
                        class="text-sm font-medium text-black bg-red-300 w-15 text-center rounded-full"
                    >
                        Delete
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p
                        class="text-sm font-medium text-black mr-3 bg-red-300 w-10 text-center rounded-full"
                    >
                        Edit
                    </p>
                    <p
                        class="text-sm font-medium text-black bg-red-300 w-15 text-center rounded-full"
                    >
                        Delete
                    </p>
                </div>
            </div>

        </div>
    </DefaultLayout>
</template>

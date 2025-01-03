<script setup lang="ts">
import DefaultLayout from '../../Layouts/DefaultLayout.vue';
import { ref } from "vue";
</script>

<template>
    <DefaultLayout>
        <table class="table-fixed">
            <thead>
                <tr>
                    <th class="min-w-[220px] text-start">Cinema Name</th>
                    <th class="min-w-[220px] text-start">Cinema Placement</th>
                    <th class="min-w-[150px] text-start">1/12/2024</th>
                    <th class="min-w-[150px] text-start">14/12/2024</th>
                    <th class="min-w-[150px] text-start">28/12/2024</th>
                    <th class="min-w-[150px] text-start">11/1/2025</th>
                    <th class="min-w-[150px] text-start">15/1/2025</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="min-w-[220px] text-start">Sf Cinema</td>
                    <td>Malcol</td>
                    <td>1961</td>
                    <td>1961</td>
                    <td>1961</td>
                    <td>1961</td>
                    <td>1961</td>
                </tr>
            </tbody>
        </table>

        <div class="overflow-x-auto">
            <table
                class="min-w-full table-auto border-collapse border border-gray-300 text-sm text-left"
            >
                <thead>
                    <tr>
                        <th
                            class="border border-gray-300 px-4 py-2 bg-red-500 text-white text-center"
                            colspan="1"
                        ></th>
                        <th
                            class="border border-gray-300 px-4 py-2 bg-red-500 text-white text-center"
                        >
                            18. december
                        </th>
                        <th
                            class="border border-gray-300 px-4 py-2 bg-red-500 text-white text-center"
                        >
                            2. januar
                        </th>
                        <th
                            class="border border-gray-300 px-4 py-2 bg-red-500 text-white text-center"
                        >
                            15. januar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- First Section -->
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 font-bold"
                            rowspan="2"
                        >
                            SF
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 font-semibold"
                        >
                            Cinema 1
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            By the toilet
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-center"
                        >
                            x
                        </td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">
                            Entrance
                        </td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                    </tr>
                    <!-- Second Section -->
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 font-bold"
                            rowspan="2"
                        >
                            SF
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 font-semibold"
                        >
                            Cinema 2
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            Facadeplacering ud mod Kalvebod
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 text-center"
                        >
                            x
                        </td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                    </tr>
                    <tr>
                        <td
                            class="border border-gray-300 px-4 py-2 font-bold"
                            rowspan="2"
                        >
                            Cinemaplex
                        </td>
                        <td
                            class="border border-gray-300 px-4 py-2 font-semibold"
                        >
                            Cinema 3
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            Foyer 1 Venstre
                        </td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">
                            Foyer 2 Venstre
                        </td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                        <td class="border border-gray-300 px-4 py-2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </DefaultLayout>
</template>


///dashboard/cinema/placement-detail/{id}
<script setup lang="ts">
import CinemaTable from "../AdminDashboard/CinemaManager/patials/CinemaTable.vue";

</script>

<template>
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
                                :href="`/dashboard/cinema/placement-detail/${cinema.id}`"
                                class="float-left text-primary"
                            >
                                View
                            </Link>
                        </div>
                    </div>
                </div>
            </CinemaTable>
        </div>
</template>
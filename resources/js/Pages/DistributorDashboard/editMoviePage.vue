<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
</script>

<template>
    <DefaultLayout title="UserList">
        <div
            class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Edit Movie
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li class="font-medium">Edit Movie</li>
                    /
                    <li class="font-medium text-primary">Movie Manager</li>
                </ol>
            </nav>
        </div>

        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div
                class="border-b border-stroke p-5 px-7.5 dark:border-strokedark flex justify-between"
            >
                <h4 class="text-xl font-semibold text-black dark:text-white">
                    <a
                        aria-current="page"
                        href="/ui-elements/cards#"
                        class="router-link-active router-link-exact-active"
                        >Movie Name: Minecraft</a
                    >
                </h4>
                <h4 class="text-xl font-semibold text-black dark:text-white">
                    <a
                        aria-current="page"
                        href="/ui-elements/cards#"
                        class="router-link-active router-link-exact-active"
                        >Premiere Date: 1/1/2026</a
                    >
                </h4>
            </div>
            <div class="px-7.5 pt-6 pb-9">
                <div
                    class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1"
                >
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                    <th
                                        class="min-w-[220px] py-4 px-4 font-medium text-black dark:text-white xl:pl-11"
                                    >
                                        Movie
                                    </th>
                                    <th
                                        class="min-w-[150px] py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Cinema
                                    </th>
                                    <th
                                        class="min-w-[120px] py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Placement
                                    </th>
                                    <th
                                        class="py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Date Start
                                    </th>
                                    <th
                                        class="py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Date End
                                    </th>
                                    <th
                                        class="py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Action
                                    </th>
                                    <th
                                        class="py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Status
                                    </th>
                                    <th
                                        class="py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Poster
                                    </th>
                                    <th
                                        class="py-4 px-4 font-medium text-black dark:text-white"
                                    >
                                        Change Request
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            Minecreaft
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            Cinema A
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            Near Toilet
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            Jan, 1 2025
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            Jan, 14 2025
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            View / Invite
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            Accepted
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            No
                                        </h5>
                                    </td>
                                    <td cclass="py-5 px-4 pl-9 xl:pl-11">
                                        <h5
                                            class="font-medium text-black dark:text-white"
                                        >
                                            Cancel /
                                            <a
                                                href="/distributor/request/edit/1/placement"
                                            >
                                                Edit
                                            </a>
                                        </h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- button page -->
                <div class="my-5">
                    <a
                        href="#"
                        class="inline-flex items-center justify-center rounded-md bg-meta-3 px-10 py-4 text-center font-medium text-white hover:bg-opacity-90 lg:px-8 xl:px-10"
                    >
                        Book Placement
                    </a>
                </div>

                <!-- button page -->
            </div>
        </div>
    </DefaultLayout>
</template>

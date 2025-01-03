<script setup lang="ts">
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
</script>

<template>
    <DefaultLayout title="UserList">
        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div
                class="border-b border-stroke px-6.5 py-4 dark:border-strokedark"
            >
                <h3 class="font-medium text-black dark:text-white">
                    Request Edit Placement For : MineCraft
                </h3>
            </div>
            <div class="flex flex-col gap-5.5 p-6.5">
                <div class="w-1/2">
                    <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                    >
                        Movie Name
                    </label>
                    <input
                        type="text"
                        disabled
                        placeholder="Default Input"
                        value="MineCraft"
                        class="input-style"
                    />
                </div>

                <div class="w-full flex flex-row">
                    <div class="w-2/4">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Edit Premiere Date
                        </label>
                        <input
                            type="Date"
                            placeholder="Active Input"
                            class="input-style"
                        />
                    </div>
                </div>

                <a
                    href="#"
                    class="w-50 inline-flex items-center justify-center rounded-md bg-meta-3 px-10 py-4 text-center font-medium text-white hover:bg-opacity-90 lg:px-8 xl:px-10"
                >
                    Request Change
                </a>
            </div>
        </div>
    </DefaultLayout>
</template>

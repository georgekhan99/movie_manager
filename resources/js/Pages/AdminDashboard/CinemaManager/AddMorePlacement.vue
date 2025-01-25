<script setup lang="ts">
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import { ref, defineProps } from "vue";
</script>

<template>
    <!-- v-if="showPlacement" -->
    <DefaultLayout title="Cinema List">
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white"> Create Placement </h3>
                
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                </div>
            <div class="w-full mt-5">
                <div class="w-[20%] h-10 flex items-center text-black text-2xl">
                    Cinema Placement
                </div>
                <!-- Loop through placements to generate dynamic forms -->
                <!-- v-for="(placement, index) in placements"
                        :key="index" -->
                <div class="w-full mt-2 border-b pb-4">
                    <label
                        class="mb-1 block text-sm font-medium text-black dark:text-white"
                    >
                        Place Name
                    </label>
                    <input
                        type="text"
                        class="input-style"
                        placeholder="Enter place name"
                    />

                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Width
                            </label>
                            <input
                                type="text"
                                class="input-style"
                                placeholder="Enter width"
                            />
                        </div>
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Height
                            </label>
                            <input
                                type="text"
                                class="input-style"
                                placeholder="Enter height"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                            <div class="w-full xl:w-1/2">
                                <label
                                    class="mb-3 block text-sm font-medium text-black dark:text-white"
                                >
                                    Material
                                </label>
                                <select name="materials" class="input-style">
                                    <option value="plasstic">plasstic</option>
                                    <option value="Vinyl">Vinyl</option>
                                    <option value="Vinyl">Meterials 1</option>
                                    <option value="Vinyl">Meterials 2</option>
                                    <option value="Vinyl">Meterials 3</option>
                                </select>
                            </div>

                            <div class="w-full xl:w-1/2">
                                <label
                                    class="mb-3 block text-sm font-medium text-black dark:text-white"
                                >
                                    Price
                                </label>
                                <input
                                    type="text"
                                    class="input-style"
                                    placeholder="Enter price"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row w-full">
                        <div class="w-1/2 flex items-center">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Colors
                            </label>
                            <div>
                                <input
                                    type="radio"
                                    value="1-4"
                                    id="color-1"
                                    class="input-style"
                                />
                                <label
                                    for="color-1"
                                    class="ml-2 text-sm text-black dark:text-white"
                                >
                                    1-4
                                </label>
                            </div>
                            <div>
                                <input
                                    type="radio"
                                    value="Pantone"
                                    id="color-2"
                                    class="input-style"
                                />
                                <label
                                    for="color-2"
                                    class="ml-2 text-sm text-black dark:text-white"
                                >
                                    Pantone
                                </label>
                            </div>
                        </div>

                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Description
                            </label>
                            <textarea
                                class="input-style"
                                placeholder="Enter description"
                                cols="4"
                            />
                        </div>
                    </div>
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row w-full">
                        <!-- <div class="xl:w-1/2">
                                    <label
                                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                                        >Upload Image</label
                                    >
                                    <input
                                        @change="
                                            (event) =>
                                                previewPlacement(event, index)
                                        "
                                        type="file"
                                        class="input-style"
                                    />
                                </div> -->
                        <div class="w-full xl:w-1/2">
                            <!-- Show Image Preview for the Current Placement -->
                            <!-- <div v-if="placement.imagePreview">
                                        <h3>Image Preview</h3>
                                        <div
                                            class="w-[250px] h-[250px] mt-2 rounded-md"
                                        >
                                            <img
                                                :src="placement.imagePreview"
                                                class="object-cover w-[250px] h-[250px] rounded-md"
                                                alt="Placement Image Preview"
                                            />
                                        </div>
                                    </div> -->
                        </div>
                    </div>
                    <!-- Remove Placement Button -->
                    <button class="mt-2 bg-red-500 text-white p-2 rounded">
                        Remove Placement
                    </button>
                </div>

                <!-- Add Placement Button -->
                <button class="mt-2 mx-1 bg-blue-500 text-white p-2 rounded">
                    Add Placement
                </button>

                <!-- Submit Button -->
                <button class="mt-4 w-20 bg-green-500 text-white p-2 rounded">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
    </DefaultLayout>
</template>

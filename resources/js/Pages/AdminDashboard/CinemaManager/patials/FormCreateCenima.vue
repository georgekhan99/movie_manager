<script setup lang="ts">
import { ref } from "vue";

let inputCount = ref<number>(1);
let showPlacement = ref<boolean>(false);

const handleraddInput = () => {
    inputCount.value += 1;
    console.log(inputCount.value);
};
</script>

<template>
    <div
        class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
    >
        <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
            <h3 class="font-medium text-black dark:text-white">
                Create Cinemas
            </h3>
        </div>
        <form action="#">
            <div class="p-6.5">
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/1">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Name
                        </label>
                        <input type="text" class="input-style" />
                    </div>
                </div>

                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/1">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Address 1
                        </label>
                        <input type="text" class="input-style" />
                    </div>
                </div>
                <!-- Address -->
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/1">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Address 2
                        </label>
                        <input type="text" class="input-style" />
                    </div>
                </div>
                <!-- End Address -->

                <!-- Zip/City -->
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/2">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Zip
                        </label>
                        <input type="text" class="input-style" />
                    </div>
                    <div class="w-full xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            City
                        </label>
                        <input type="text" class="input-style" />
                    </div>
                </div>
                <!-- End Zip/City -->
                <!-- Regioin / Country -->
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Region/State
                        </label>
                        <input type="text" class="input-style" />
                    </div>
                    <div class="w-full xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Country
                        </label>
                        <input type="text" class="input-style" />
                    </div>
                </div>
                <!-- End Regioin / Country -->

                <!-- Select Company -->
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Company
                        </label>
                        <select class="input-style">
                            <option value="">Comapny</option>
                            <option value="">Comapny</option>
                            <option value="">Comapny</option>
                            <option value="">Comapny</option>
                            <option value="">Comapny</option>
                        </select>
                    </div>
                </div>
                <!-- End Select Company -->

                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Logo
                        </label>
                        <input type="file" class="input-style" />
                    </div>
                </div>

                <div v-if="showPlacement" class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full mt-5">
                        <div class="w-[20%] h-10 flex items-center text-black text-2xl">
                            Cinema Placement
                        </div>

                        <div class="w-full mt-2">
                            <label class="mb-1 block text-sm font-medium text-black dark:text-white">
                                Place Name
                            </label>
                            <input v-for="i in inputCount" :key="i" type="text" class="input-style"/>
                            <!-- Placement Details -->
                            <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                                        Description
                                    </label>
                                    <input type="text" class="input-style" />
                                </div>

                                <div class="w-full xl:w-1/2">
                                    <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                                        Height
                                    </label>
                                    <input type="text" class="input-style" />
                                </div>
                            </div>

                            <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                                <div class="w-full xl:w-1/2">
                                    <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                                        Width
                                    </label>
                                    <input type="text" class="input-style" />
                                </div>

                                <div class="w-full xl:w-1/2">
                                    <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                                        colors
                                    </label>
                                    <input type="text" class="input-style" />
                                </div>
                            </div>

                            <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                                <div class="w-full xl:w-1/2">
                                    <label
                                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                                    >
                                        Material
                                    </label>
                                    <input type="text" class="input-style" />
                                </div>

                                <div class="w-full xl:w-1/2">
                                    <label
                                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                                    >
                                        price
                                    </label>
                                    <input type="text" class="input-style" />
                                </div>
                            </div>
                            <button @click.prevent="handleraddInput" class="mt-2 bg-blue-500 text-white p-2 rounded">
                                Add Placement
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End Placement Details -->
                <button
                    @click="() => { showPlacement = true;}"
                    class="flex w-50 justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90"
                >
                    {{ showPlacement === false ? "Add Placement" : "submit" }}
                </button>
            </div>
        </form>
    </div>
</template>

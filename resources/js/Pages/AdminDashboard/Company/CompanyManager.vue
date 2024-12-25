<script setup>
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { onBeforeMount, ref } from "vue";
import { router } from "@inertiajs/vue3";

const getDataRef = ref({
    data: [],
});

onBeforeMount(() => {
    getCompanyData();
});
//get All data
const getCompanyData = async () => {
    try {
        const response = await fetch("/dashboard/get/company/all");
        getDataRef.value.data = await response.json();
    } catch (error) {
        console.error("Error fetching company data:", error);
    }
};
const getTypeSort = async () => {};
//get Selected Data
</script>

<template>
    <DefaultLayout title="Admin Dashboard">
        <div
            class="grid grid-cols-12 justify-center items-center h-20 mb-5 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div class="col-span-3 px-4">
                <input
                    type="text"
                    placeholder="Search by username"
                    class="w-full h-12 px-5 rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                />
            </div>
            <div class="col-span-3 px-4">
                <select name="" id="" class="input-style">
                    <option value="">Select Role</option>
                    <option value="">Distributor</option>
                    <option value="">Cinema</option>
                </select>
            </div>
            <div class="col-span-2 px-2">
                <button
                    class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90"
                >
                    Filter
                </button>
            </div>
        </div>
        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div class="py-6 px-4 md:px-6 xl:px-7.5">
                <h4 class="text-xl font-bold text-black dark:text-white">
                    User Management
                </h4>
            </div>

            <!-- Table Header -->
            <div
                class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <p class="font-medium">Company Name</p>
                </div>
                <div class="col-span-3 hidden items-center sm:flex">
                    <p class="font-medium">Type</p>
                </div>
                <div class="col-span-2 flex justify-center items-center">
                    <p class="font-medium">Action</p>
                </div>
            </div>

            <!-- Table Rows -->
            <!-- Table Rows -->
            <div
                v-for="data in getDataRef.data"
                :key="data.id"
                class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center"
                    >
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            {{ data.company_name }}
                        </p>
                    </div>
                </div>
                <div class="col-span-3 hidden items-center sm:flex">
                    <p class="text-sm font-medium text-black dark:text-white">
                        {{ data.label }}
                    </p>
                </div>
                <div class="col-span-2 flex items-center justify-center">
                    <button class="hover:text-primary" href="/dashboard">
                        <svg
                            class="fill-current"
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M8.99981 14.8219C3.43106 14.8219 0.674805 9.50624 0.562305 9.28124C0.47793 9.11249 0.47793 8.88749 0.562305 8.71874C0.674805 8.49374 3.43106 3.17812 8.99981 3.17812C14.5686 3.17812 17.3248 8.49374 17.4373 8.71874C17.5217 8.88749 17.5217 9.11249 17.4373 9.28124C17.3248 9.50624 14.5686 14.8219 8.99981 14.8219ZM1.85605 8.99999C2.4748 10.0406 4.89356 13.9937 8.99981 13.9937C13.1061 13.9937 15.5248 10.0406 16.1436 8.99999C15.5248 7.95936 13.1061 4.00624 8.99981 4.00624C4.89356 4.00624 2.4748 7.95936 1.85605 8.99999Z"
                                fill=""
                            />
                            <path
                                d="M9 11.3906C7.67812 11.3906 6.60938 10.3219 6.60938 9C6.60938 7.67813 7.67812 6.60938 9 6.60938C10.3219 6.60938 11.3906 7.67813 11.3906 9C11.3906 10.3219 10.3219 11.3906 9 11.3906ZM9 7.875C8.38125 7.875 7.875 8.38125 7.875 9C7.875 9.61875 8.38125 10.125 9 10.125C9.61875 10.125 10.125 9.61875 10.125 9C10.125 8.38125 9.61875 7.875 9 7.875Z"
                                fill=""
                            />
                        </svg>
                    </button>
                    <Link class="hover:text-primary mx-3" href="/">
                        <svg
                            class="fill-current"
                            width="18"
                            height="18"
                            viewBox="0 0 18 18"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M8.99981 14.8219C3.43106 14.8219 0.674805 9.50624 0.562305 9.28124C0.47793 9.11249 0.47793 8.88749 0.562305 8.71874C0.674805 8.49374 3.43106 3.17812 8.99981 3.17812C14.5686 3.17812 17.3248 8.49374 17.4373 8.71874C17.5217 8.88749 17.5217 9.11249 17.4373 9.28124C17.3248 9.50624 14.5686 14.8219 8.99981 14.8219ZM1.85605 8.99999C2.4748 10.0406 4.89356 13.9937 8.99981 13.9937C13.1061 13.9937 15.5248 10.0406 16.1436 8.99999C15.5248 7.95936 13.1061 4.00624 8.99981 4.00624C4.89356 4.00624 2.4748 7.95936 1.85605 8.99999Z"
                                fill=""
                            />
                            <path
                                d="M9 11.3906C7.67812 11.3906 6.60938 10.3219 6.60938 9C6.60938 7.67813 7.67812 6.60938 9 6.60938C10.3219 6.60938 11.3906 7.67813 11.3906 9C11.3906 10.3219 10.3219 11.3906 9 11.3906ZM9 7.875C8.38125 7.875 7.875 8.38125 7.875 9C7.875 9.61875 8.38125 10.125 9 10.125C9.61875 10.125 10.125 9.61875 10.125 9C10.125 8.38125 9.61875 7.875 9 7.875Z"
                                fill=""
                            />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

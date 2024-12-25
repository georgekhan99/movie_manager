<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { router, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Icon } from "../../../../Constraint/ContrantIcon";
const props = defineProps({
    CompanyData: Object,
    labels: Object,
});

const form = useForm({
    id: props.CompanyData.id,
    legalName: props.CompanyData.legalName,
    BrandName: props.CompanyData.BrandName,
    Organization: props.CompanyData.Organization,
    Parent_Company: props.CompanyData.Parent_Company,
    CVR: props.CompanyData.CVR,
    address_1: props.CompanyData.address_1,
    address_2: props.CompanyData.address_2,
    zip_code: props.CompanyData.zip_code,
    City: props.CompanyData.City,
    State: props.CompanyData.State,
    Country: props.CompanyData.Country,
    label_name: props.CompanyData.label_name,
    lebel_id: props.CompanyData.label_id,
});
</script>

<template>
    <DefaultLayout>
        <div
            class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Create Company
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <Link class="font-medium" href="">Dashboard</Link>
                    </li>
                    /
                    <li class="font-medium text-primary">Create Company</li>
                </ol>
            </nav>
        </div>
        <!-- Form -->
        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div
                class="border-b border-stroke px-6.5 py-4 dark:border-strokedark"
            >
                <h3 class="font-medium text-black dark:text-white">
                    Create Company form
                </h3>
            </div>
            <form>
                <div class="p-6.5">
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Label
                            </label>
                            <select v-model="form.lebel_id" class="input-style">
                                <option
                                    v-for="lable in props.labels"
                                    :value="lable.id"
                                    :key="lable.id"
                                >
                                    {{ lable.label_name }}
                                </option>
                            </select>
                        </div>

                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Legalname (Billing)
                            </label>
                            <input
                                v-model="form.legalName"
                                type="text"
                                class="input-style"
                            />
                        </div>
                    </div>

                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Brand name
                            </label>
                            <input
                                v-model="form.BrandName"
                                type="text"
                                class="input-style"
                            />
                        </div>

                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Organization
                            </label>
                            <input
                                v-model="form.Organization"
                                type="text"
                                class="input-style"
                            />
                        </div>
                    </div>
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Parent Company
                            </label>
                            <div
                                class="relative z-20 bg-transparent dark:bg-form-input"
                            >
                                <input
                                    v-model="form.Parent_Company"
                                    type="text"
                                    class="input-style"
                                />
                                <span
                                    class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                                >
                                </span>
                            </div>
                        </div>

                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                CVR.
                            </label>
                            <div
                                class="relative z-20 bg-transparent dark:bg-form-input"
                            >
                                <input
                                    v-model="form.CVR"
                                    type="text"
                                    class="input-style"
                                />
                                <span
                                    class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                                >
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Address 1
                        </label>
                        <textarea
                            v-model="form.address_1"
                            rows="1"
                            class="input-style"
                        ></textarea>
                    </div>

                    <div class="mb-6">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Address 2
                        </label>
                        <textarea
                            v-model="form.address_2"
                            rows="1"
                            class="input-style"
                        ></textarea>
                    </div>
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Zip Code
                            </label>
                            <div
                                class="relative z-20 bg-transparent dark:bg-form-input"
                            >
                                <input
                                    v-model="form.zip_code"
                                    type="text"
                                    class="input-style"
                                />
                                <span
                                    class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                                >
                                </span>
                            </div>
                        </div>

                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                City
                            </label>
                            <div
                                class="relative z-20 bg-transparent dark:bg-form-input"
                            >
                                <input
                                    v-model="form.City"
                                    type="text"
                                    class="input-style"
                                />
                                <span
                                    class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                                >
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Region/State
                            </label>
                            <div
                                class="relative z-20 bg-transparent dark:bg-form-input"
                            >
                                <input
                                    v-model="form.State"
                                    type="text"
                                    class="input-style"
                                />
                                <span
                                    class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                                >
                                </span>
                            </div>
                        </div>

                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Country
                            </label>
                            <div
                                class="relative z-20 bg-transparent dark:bg-form-input"
                            >
                                <input
                                    v-model="form.Country"
                                    type="text"
                                    class="input-style"
                                />
                                <span
                                    class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                                >
                                </span>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-30 flex w-2xl justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-70"
                    >
                        Update
                    </button>
                </div>
            </form>

            <!-- Add User To Company View -->
            <div class="p-6.5">
                <div class="w-full flex items-center justify-between border-b border-stroke">
                    <p class="text-xl font-medium mb-2">Sub Company</p>
                    <button
                        type="submit"
                        class="w-30 mb-2 flex w-2xl justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-70"
                    >
                        <div v-html="Icon.Plus"></div>
                    </button>
                </div>
                <!-- Table Header -->
                <div
                    class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                >
                    <div class="col-span-3 flex items-center">
                        <p class="font-medium">Company Name</p>
                    </div>
                    <div class="col-span-3 hidden items-center sm:flex">
                        <p class="font-medium">Role</p>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <p class="font-medium">Action</p>
                    </div>
                </div>

                <div class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
                    <div class="col-span-3 flex items-center">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <p class="text-md font-medium text-black dark:text-white">
                            Company Name 1
                        </p>
                    </div>
                </div>
                <div class="col-span-3 hidden items-center sm:flex">
                    <p class="text-md font-medium text-black dark:text-white">
                        Movie Manager
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-md font-medium text-black dark:text-white hover:text-primary cursor-pointer">
                       Delete /  Edit
                    </p>
                </div>

                </div>
            </div>
            <!-- End Table Header -->
            <!-- Company User -->
            <div class="p-6.5">
                <div class="w-full flex items-center justify-between border-b border-stroke">
                    <p class="text-xl mb-2 font-medium">Users</p>
                    <button
                        type="submit"
                        class="w-30 mb-2 flex w-2xl justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-70"
                    >
                        <div v-html="Icon.Plus"></div>
                    </button>
                </div>
                <!-- Table Header -->
                <div
                    class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                >
                    <div class="col-span-3 flex items-center">
                        <p class="font-medium">Company Name</p>
                    </div>
                    <div class="col-span-3 hidden items-center sm:flex">
                        <p class="font-medium">Role</p>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <p class="font-medium">Action</p>
                    </div>
                </div>

                <div class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5">
                    <div class="col-span-3 flex items-center">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <p class="text-md font-medium text-black dark:text-white">
                            Company Name 1
                        </p>
                    </div>
                </div>
                <div class="col-span-3 hidden items-center sm:flex">
                    <p class="text-md font-medium text-black dark:text-white">
                        Distributor
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-md font-medium text-black dark:text-white hover:text-primary cursor-pointer">
                       Delete /  Edit
                    </p>
                </div>

                </div>
            </div>
            <!-- End Company User -->



            <!-- / Add User To Company View -->
        </div>
    </DefaultLayout>
</template>

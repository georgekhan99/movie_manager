<script setup lang="ts">
import { Link, useForm, } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3'

const labels =ref([]);
const get_label = async () => {
    const response = await fetch(route('company.getlabel'));
    const data = await response.json(); 
    
    labels.value = data
}
const form = useForm({
    label_name: '',
    Legalname :'',
    Brandname: '',
    Organization: '',
    ParentCompany: '',
    CVR:'',
    Address_1:'',
    Address_2:'',
    Zip_Code: '',
    City:'',
    Region:'',
    Country:''
});
const submit = () => {
    form.post(route('company.save_company'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        }
    });
}

//Life Cycle
onMounted(() => {
    get_label();
});

</script>

<template>
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
            <form @submit.prevent="submit">
                <div class="p-6.5">
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Label
                            </label>
                            <select v-model="form.label_name" class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" value="">
                                <option disabled> Select Label </option>
                                <option v-for="label in labels" :value="label.id" :key="label.id"> {{ label.label_name }} </option>
                            </select>
                        </div>

                        <div class="w-full xl:w-1/2">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >
                                Legalname (Billing)
                            </label>
                            <input
                                v-model="form.Legalname"
                                type="text"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
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
                                v-model="form.Brandname"
                                type="text"
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
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
                                class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
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
                            v-model="form.ParentCompany"
                            type="text"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                        />
                            <span
                                class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                            >
                            </span>
                        </div>
                    </div>

                    <div class="w-full xl:w-1/2">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            CVR.
                        </label>
                        <div class="relative z-20 bg-transparent dark:bg-form-input">
                        <input
                            v-model="form.CVR"
                            type="text"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
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
                            rows="1"
                            v-model="form.Address_1"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                        ></textarea>
                    </div>

                    <div class="mb-6">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Address 2
                        </label>
                        <textarea
                            rows="1"
                            v-model="form.Address_2"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                        ></textarea>
                    </div>
                    <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/2">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Zip Code
                        </label>
                        <div class="relative z-20 bg-transparent dark:bg-form-input">
                        <input
                             v-model="form.Zip_Code"
                            type="text"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                        />
                            <span
                                class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                            >
                            </span>
                        </div>
                    </div>

                    <div class="w-full xl:w-1/2">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            City
                        </label>
                        <div class="relative z-20 bg-transparent dark:bg-form-input">
                        <input
                            v-model="form.City"
                            type="text"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
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
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                           Region/State
                        </label>
                        <div class="relative z-20 bg-transparent dark:bg-form-input">
                        <input
                            v-model="form.Region"
                            type="text"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                        />
                            <span
                                class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                            >
                            </span>
                        </div>
                    </div>

                    <div class="w-full xl:w-1/2">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                          Country
                        </label>
                        <div class="relative z-20 bg-transparent dark:bg-form-input">
                        <input
                            v-model="form.Country"
                            type="text"
                            class="w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                        />
                            <span
                                class="absolute right-4 top-1/2 z-30 -translate-y-1/2"
                            >
                            </span>
                        </div>
                    </div>
                </div>
                   
                    <button
                        :disabled="form.processing"
                        type="submit"
                        class="flex w-2xl justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-70"
                    >
                        Send Message
                    </button>
                
                </div>
            </form>
        </div>
</template>

<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Icon } from "../../../Constraint/ContrantIcon";
import { ref, reactive, watch } from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    usersList: Array,
    filters: {
        type: Object,
        default: () => ({
            sortColumn: "users.name",
            sortDirection: "asc",
            search: "",
        }),
    },
    show: {
        type: Boolean,
        default: true,
    },
});

const showConfirmDeleteUserModal = ref(false);

const confirmDeleteUser = () => {
    showConfirmDeleteUserModal.value = true;
};

const closeModal = () => {
    showConfirmDeleteUserModal.value = false;
};

// Search and Sort State
const query = reactive({
    search: props.filters.search || "",
    sortColumn: props.filters.sortColumn || "users.name",
    sortDirection: props.filters.sortDirection || "asc",
});

// Update filters and fetch new data
const applyFilters = () => {
    router.get(window.location.href, {
        search: query.search,
        sortColumn: query.sortColumn,
        sortDirection: query.sortDirection,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Watch for changes to the filters prop and update the reactive query
watch(
    () => props.filters,
    (newFilters) => {
        query.search = newFilters.search;
        query.sortColumn = newFilters.sortColumn;
        query.sortDirection = newFilters.sortDirection;
    },
    { immediate: true }
);
</script>


<template>
    <DefaultLayout title="UserList">
        <!-- Filter and Sort Header -->
        <div
            class="grid grid-cols-12 justify-center items-center h-20 mb-5 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <!-- Search Input -->
            <div class="col-span-3 px-4">
                <input
                    v-model="query.search"
                    @input="applyFilters"
                    type="text"
                    placeholder="Search by username"
                    class="w-full h-12 px-5 rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                />
            </div>

            <!-- Sort Dropdown -->
            <div class="col-span-3 px-4">
                <select v-model="query.sortColumn" @change="applyFilters" class="input-style">
                    <option value="users.name">Sort by Name</option>
                    <option value="user_roles.name">Sort by Role</option>
                    <option value="company.company_legalname">Sort by Company</option>
                </select>
            </div>
            <div class="col-span-2 h-10 px-2">
                <select v-model="query.sortDirection" @change="applyFilters" class="input-style">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>

        <!-- User Table -->
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
                    <p class="font-medium">Username</p>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="font-medium">Role</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Company</p>
                </div>
                <div class="col-span-1 flex justify-center items-center">
                    <p class="font-medium">Action</p>
                </div>
            </div>

            <!-- Table Rows -->
            <div
                v-for="user in usersList"
                :key="user.id"
                class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <p class="text-sm font-medium text-black dark:text-white">
                            {{ user.name + " " + user.surname }}
                        </p>
                    </div>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="text-sm font-medium text-black dark:text-white">
                        {{ user.role }}
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-sm font-medium text-black dark:text-white">
                        {{ user.company_legalname || '-' }}
                    </p>
                </div>
                <div class="col-span-1 flex items-center justify-center">
                    <Link
                        class="hover:text-primary relative group"
                        :href="`/dashboard/user/${user.id}/edit`"
                    >
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="#343C54"
                            xmlns="http://www.w3.org/2000/svg"
                            transform="rotate(0 0 0)"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M19.3028 3.7801C18.4241 2.90142 16.9995 2.90142 16.1208 3.7801L14.3498 5.5511C14.3442 5.55633 14.3387 5.56166 14.3333 5.5671C14.3279 5.57253 14.3225 5.57803 14.3173 5.58359L5.83373 14.0672C5.57259 14.3283 5.37974 14.6497 5.27221 15.003L4.05205 19.0121C3.9714 19.2771 4.04336 19.565 4.23922 19.7608C4.43508 19.9567 4.72294 20.0287 4.98792 19.948L8.99703 18.7279C9.35035 18.6203 9.67176 18.4275 9.93291 18.1663L20.22 7.87928C21.0986 7.0006 21.0986 5.57598 20.22 4.6973L19.3028 3.7801ZM14.8639 7.15833L6.89439 15.1278C6.80735 15.2149 6.74306 15.322 6.70722 15.4398L5.8965 18.1036L8.56029 17.2928C8.67806 17.257 8.7852 17.1927 8.87225 17.1057L16.8417 9.13619L14.8639 7.15833ZM17.9024 8.07553L19.1593 6.81862C19.4522 6.52572 19.4522 6.05085 19.1593 5.75796L18.2421 4.84076C17.9492 4.54787 17.4743 4.54787 17.1814 4.84076L15.9245 6.09767L17.9024 8.07553Z"
                                fill="#343C54"
                            />
                        </svg>
                        <span
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden w-max px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 group-hover:block transition-opacity duration-300"
                        >
                            Edit User
                        </span>
                    </Link>
                    <button
                        class="hover:text-primary mx-3 relative group"
                        @click="confirmDeleteUser"
                    >
                        <div v-html="Icon.Trash"></div>
                    </button>
                </div>
            </div>
        </div>
        <!-- Confirmation Modal -->
        <ConfirmationModal
            :show="showConfirmDeleteUserModal"
            @close="closeModal"
        >
            <template v-slot:title>Confirm Delete User</template>
            <template v-slot:content>
                <div class="h-20 flex items-center">
                    <h2 class="text-xl">Are you sure to delete this user?</h2>
                </div>
            </template>
            <template v-slot:footer>
                <div class="flex justify-end">
                    <DangerButton class="mr-3">Confirm</DangerButton>
                    <PrimaryButton @click="closeModal">Cancel</PrimaryButton>
                </div>
            </template>
        </ConfirmationModal>
    </DefaultLayout>
</template>

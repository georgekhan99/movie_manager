<script setup lang="ts">
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import { router, useForm, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Icon } from "../../../../Constraint/ContrantIcon";
import { onMounted, onUnmounted, ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";


const props = defineProps({
    CompanyData: Object,
    labels: Object,
    cinemasData: Object,
});

const showModal = ref(false);
let Addusers = ref([]);
let attachedUsers = ref([]);

const getAttachedUsers = async (companyId: number) => {
    try {
        if (!companyId) {
            console.error("Invalid company ID:", companyId);
            return;
        }

        const response = await fetch(
            `/dashboard/get/company/${companyId}/users`,
            {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            }
        );

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        attachedUsers.value = data;
    } catch (error) {
        console.error("Error fetching users:", error);
    }
};

watch(
    () => props.CompanyData?.id,
    (newId) => {
        if (newId) {
            getAttachedUsers(newId);
        }
    },
    { immediate: true }
);

onMounted(() => {
    console.log("Company ID on mount:", props.CompanyData.id); // Debug log
    if (props.CompanyData && props.CompanyData.id) {
        getAttachedUsers(props.CompanyData.id);
    }
});

onUnmounted(() => {
    attachedUsers.value = [];
});

// const openModal = async () => {
//     showModal.value = true;
//     let users = await fetch("/dashboard/company/addusers");
//     Addusers.value = await users.json();
// };

/**
 * แปลง label_name → role_name สำหรับดึง user ตามประเภทบริษัท
 */
function getRoleFromLabel(labelName: string): string {
    switch (labelName) {
        case "Distributor":
            return "Distribution Manager";
        case "Cinema":
            return "Cinema Manager";
        default:
            return "";
    }
}

/**
 * ดึง user ที่ตรงกับ label_name ของบริษัทนั้น ๆ
 */
const openModal = async () => {
    showModal.value = true;

    const labelName = props.CompanyData?.label_name || "";
    const userRole = getRoleFromLabel(labelName);

    console.log("🔍 Fetching users with role:", userRole);

    if (!userRole) {
        Addusers.value = [];
        return;
    }

    const response = await fetch(
        `/dashboard/company/addusers?role=${encodeURIComponent(userRole)}`
    );
    Addusers.value = await response.json();
};

const closeModal = () => {
    showModal.value = false;
};

const AttachUsertoCompany = async ([companyId, userId]) => {
    try {
        await router.post(
            route("add.company_users", {
                company_id: companyId,
                users_id: userId,
            })
        );
        closeModal();
        getAttachedUsers(props.CompanyData.id);
    } catch (error) {
        console.log(error);
    } finally {
        closeModal();
    }
};

const form = useForm({
    id: props.CompanyData.id,
    legalName: props.CompanyData.legalName,
    BrandName: props.CompanyData.BrandName,
    Initials: props.CompanyData.Initials,
    Parent_Company: props.CompanyData.Parent_Company,
    CVR: props.CompanyData.CVR,
    address_1: props.CompanyData.address_1,
    address_2: props.CompanyData.address_2,
    zip_code: props.CompanyData.zip_code,
    City: props.CompanyData.City,
    State: props.CompanyData.State,
    Country: props.CompanyData.Country,
    label_name: props.CompanyData.label_name,
    lebel_id: props.CompanyData["label.id"],
});


const updateCompany = () => {
    form.post(route("update.company"), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Data updated successfully!", {
                autoClose: 2000,
                position: "top-right",
                theme: "colored",
            });
        },
        onError: (errors) => {
            toast.error("❌ Failed to update data. Please check your input.", {
                autoClose: 3000,
                position: "top-right",
                theme: "colored",
            });
            console.error(errors);
        },
    });
};


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
            <form @submit.prevent="updateCompany">
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
                                Initials
                            </label>
                            <input
                                v-model="form.Initials"
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
                <div
                    class="w-full flex items-center justify-between border-b border-stroke"
                >
                    <p class="text-xl font-medium mb-2">Sub Company</p>
                    <Link
                        :href="route('adminpage.dashboard')"
                        class="w-30 mb-2 flex w-2xl justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-70"
                    >
                        <div v-html="Icon.Plus"></div>
                    </Link>
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

                <div
                    class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                >
                    <div class="col-span-3 flex items-center">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <p
                                class="text-md font-medium text-black dark:text-white"
                            >
                                Company Name 1
                            </p>
                        </div>
                    </div>
                    <div class="col-span-3 hidden items-center sm:flex">
                        <p
                            class="text-md font-medium text-black dark:text-white"
                        >
                            Movie Manager
                        </p>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <p
                            class="text-md font-medium text-black dark:text-white hover:text-primary cursor-pointer"
                        >
                            Delete / Edit
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Table Header -->
            <!-- Company User -->
            <div class="p-6.5">
                <div
                    class="w-full flex items-center justify-between border-b border-stroke"
                >
                    <p class="text-xl mb-2 font-medium">Users</p>
                    <button
                        @click="openModal()"
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
                        <p class="font-medium">User Name</p>
                    </div>
                    <div class="col-span-3 hidden items-center sm:flex">
                        <p class="font-medium">Role</p>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <p class="font-medium">Action</p>
                    </div>
                </div>

                <div
                    v-for="user in attachedUsers"
                    class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                >
                    <div class="col-span-3 flex items-center">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <p
                                class="text-md font-medium text-black dark:text-white"
                            >
                                {{ user.name }} {{ user.surname }}
                            </p>
                        </div>
                    </div>
                    <div class="col-span-3 hidden items-center sm:flex">
                        <p
                            class="text-md font-medium text-black dark:text-white"
                        >
                            {{ user.role_name }}
                        </p>
                    </div>
                    <div class="col-span-1 flex items-centers">
                        <Link
                            preserve-scroll
                            :href="`/dashboard/delete/company/${user.c_id}/users`"
                            as="button"
                            class="text-md font-medium text-black dark:text-white hover:text-primary cursor-pointer"
                        >
                            <div v-html="Icon.Trash" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Cinema -->
            <template></template>
            <div class="p-6.5">
                <div
                    class="w-full flex items-center justify-between border-b border-stroke"
                >
                    <p class="text-xl mb-2 font-medium">Cinema List</p>
                    <button
                        @click="openModal()"
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
                        <p class="font-medium">Cinema Name</p>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <p class="font-medium">Action</p>
                    </div>
                </div>

                <div
                    v-for="cinema in cinemasData"
                    class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                >
                    <div class="col-span-3 flex items-center">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <p
                                class="text-md font-medium text-black dark:text-white"
                            >
                                {{ cinema.cinema_name }}
                            </p>
                        </div>
                    </div>
                    <div class="col-span-1 flex items-centers">
                        <!-- <Link preserve-scroll :href="`/dashboard/delete/company/${user.c_id}/users`" as="button"  class="text-md font-medium text-black dark:text-white hover:text-primary cursor-pointer">
                            <div v-html="Icon.Trash" /> 
                        </Link> -->
                    </div>
                </div>
            </div>

            <!-- End Company User -->
            <!-- Modal Open -->
            <Modal :show="showModal">
                <div
                    class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
                >
                    <div
                        class="border-b flex justify-between border-stroke px-6.5 py-4 dark:border-strokedark"
                    >
                        <h3
                            class="font-medium text-black text-xl dark:text-white"
                        >
                            Assign user to company
                        </h3>
                        <h3
                            @click="closeModal()"
                            class="font-medium text-black text-xl dark:text-white cursor-pointer hover:text-danger"
                        >
                            x
                        </h3>
                    </div>
                    <div
                        class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                    >
                        <div class="col-span-3 flex items-center">
                            <p class="font-medium">Username</p>
                        </div>
                        <div class="col-span-3 hidden items-center sm:flex">
                            <p class="font-medium">Role</p>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <p class="font-medium">Action</p>
                        </div>
                    </div>

                    <div
                        v-for="user in Addusers"
                        class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                    >
                        <div class="col-span-3 flex items-center">
                            <div
                                class="flex flex-col gap-4 sm:flex-row sm:items-center"
                            >
                                <p
                                    class="text-md font-medium text-black dark:text-white"
                                >
                                    {{ user.name }} {{ user.surname }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-3 hidden items-center sm:flex">
                            <p
                                class="text-md font-medium text-black dark:text-white"
                            >
                                {{ user.role_name }}
                            </p>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <button
                                @click="
                                    AttachUsertoCompany([
                                        props.CompanyData.id,
                                        user.id,
                                    ])
                                "
                                class="w-10 flex w-2xl justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-70"
                            >
                                <div>
                                    <svg
                                        width="15"
                                        viewBox="0 0 24 24"
                                        fill="#f4f4f4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        transform="rotate(0 0 0)"
                                    >
                                        <path
                                            d="M11.2502 6C11.2502 5.58579 11.586 5.25 12.0002 5.25C12.4145 5.25 12.7502 5.58579 12.7502 6V11.2502H18.0007C18.4149 11.2502 18.7507 11.586 18.7507 12.0002C18.7507 12.4145 18.4149 12.7502 18.0007 12.7502H12.7502V18.0007C12.7502 18.4149 12.4145 18.7507 12.0002 18.7507C11.586 18.7507 11.2502 18.4149 11.2502 18.0007V12.7502H6C5.58579 12.7502 5.25 12.4145 5.25 12.0002C5.25 11.586 5.58579 11.2502 6 11.2502H11.2502V6Z"
                                            fill="#f4f4f4"
                                        ></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </Modal>

            <Modal :show="SubcompanyModal">
                <div
                    class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
                >
                    <div
                        class="border-b flex justify-between border-stroke px-6.5 py-4 dark:border-strokedark"
                    >
                        <h3
                            class="font-medium text-black text-xl dark:text-white"
                        >
                            Assign user to company
                        </h3>
                        <h3
                            @click="CloseSubcompanyModal()"
                            class="font-medium text-black text-xl dark:text-white cursor-pointer hover:text-danger"
                        >
                            x
                        </h3>
                    </div>
                    <div
                        class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                    >
                        <div class="col-span-3 flex items-center">
                            <p class="font-medium">Username</p>
                        </div>
                        <div class="col-span-3 hidden items-center sm:flex">
                            <p class="font-medium">Role</p>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <p class="font-medium">Action</p>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
                    >
                        <div class="col-span-3 flex items-center">
                            <div
                                class="flex flex-col gap-4 sm:flex-row sm:items-center"
                            >
                                <p
                                    class="text-md font-medium text-black dark:text-white"
                                >
                                    Company_1
                                </p>
                            </div>
                        </div>
                        <div class="col-span-3 hidden items-center sm:flex">
                            <p
                                class="text-md font-medium text-black dark:text-white"
                            >
                                Cinema
                            </p>
                        </div>
                        <div class="col-span-1 flex items-center">
                            <button
                                class="w-10 flex w-2xl justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-70"
                            >
                                <div>
                                    <svg
                                        width="15"
                                        viewBox="0 0 24 24"
                                        fill="#f4f4f4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        transform="rotate(0 0 0)"
                                    >
                                        <path
                                            d="M11.2502 6C11.2502 5.58579 11.586 5.25 12.0002 5.25C12.4145 5.25 12.7502 5.58579 12.7502 6V11.2502H18.0007C18.4149 11.2502 18.7507 11.586 18.7507 12.0002C18.7507 12.4145 18.4149 12.7502 18.0007 12.7502H12.7502V18.0007C12.7502 18.4149 12.4145 18.7507 12.0002 18.7507C11.586 18.7507 11.2502 18.4149 11.2502 18.0007V12.7502H6C5.58579 12.7502 5.25 12.4145 5.25 12.0002C5.25 11.586 5.58579 11.2502 6 11.2502H11.2502V6Z"
                                            fill="#f4f4f4"
                                        ></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </Modal>

            <!-- End Modal -->

            <!-- / Add User To Company View -->
        </div>
    </DefaultLayout>
</template>

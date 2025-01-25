<script setup lang="ts">
import { defineProps, ref, computed } from "vue";
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import { router } from "@inertiajs/vue3";
import DialogModal from "@/Components/DialogModal.vue";

interface CinemaPlacement {
    id: number;
    cinema_name: string;
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
    company_id: number;
    created_at: string; // Consider using Date if you parse these into Date objects
    updated_at: string; // Consider using Date if you parse these into Date objects
    placement_name: string;
    placement_width: string;
    placement_height: string;
    placement_colors: string;
    placement_material: string;
}
interface Cinemas {
    id: number;
    cinema_name: string;
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
    company_id: number | null | any;
    image: string | null;
    Difference_Address?: string | null;
}

interface DifferenceCinemas {
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
}

const props = defineProps<{
    pageId: number;
    CinemaData: CinemaPlacement[];
    cinema_data: {
        Difference_Address: string | object | null;
    };
}>();


function parseDifferenceAddress(
    data: string | object | null
): DifferenceCinemas {
    if (!data) {
        // Return default empty address if data is null
        return {
            address_1: "",
            address_2: "",
            zip: "",
            city: "",
            state: "",
            country: "",
        };
    }
    if (typeof data === "string") {
        try {
            const parsed = JSON.parse(data);
            return Array.isArray(parsed) && parsed.length > 0 ? parsed[0] : {};
        } catch (error) {
            console.error("Invalid JSON format:", error);
            return {
                address_1: "",
                address_2: "",
                zip: "",
                city: "",
                state: "",
                country: "",
            };
        }
    }
    if (typeof data === "object" && !Array.isArray(data)) {
        // If already an object, return it as is
        return data as DifferenceCinemas;
    }
    // Return default empty address if not valid
    return {
        address_1: "",
        address_2: "",
        zip: "",
        city: "",
        state: "",
        country: "",
    };
}

// Safely initialize the address
const DiffenceAddress = ref<DifferenceCinemas>(
    parseDifferenceAddress(props.cinema_data?.Difference_Address)
);

const isDifferenceEmpty = ():boolean => {
    if(props.cinema_data?.Difference_Address.length > 0){
        return true
    }else {
        return false
    }
}

const isOpen = ref(false);
const image = ref(null);
const PreviewImage = ref(null);
const previewImage = (event) => {
    const input = event.target.files[0];
    if (input) {
        cinemas.value.image = input;
        const reader = new FileReader();
        reader.onload = (e) => {
            PreviewImage.value = e.target.result;
        };
        reader.readAsDataURL(input); // Read the file as a data URL
        image.value = input.name; // Optionally store the file name
    }
    console.log(image);
};

const cinemas = ref<Cinemas>({
    id: props.cinema_data.id,
    cinema_name: props.cinema_data.cinema_name,
    address_1: props.cinema_data.address_1,
    address_2: props.cinema_data.address_2,
    zip: props.cinema_data.zip,
    city: props.cinema_data.city,
    state: props.cinema_data.state,
    country: props.cinema_data.country,
    company_id: props.cinema_data.company_id,
    image: props.cinema_data.image,
});
//เปิด Accordion
const toggleAccordion = () => {
    isOpen.value = !isOpen.value;
};
const isModalOpen = ref(false);
const closeModal = () => {
    isModalOpen.value = false;
    PlacementsDetials.value = [];
};
//End Modal
const PlacementsDetials = ref([]);
//Open Modal
const OpenModal = async (id) => {
    isModalOpen.value = true;
    try {
        let response = await fetch(`/dashboard/placement/${id}`);
        PlacementsDetials.value = await response.json();
        console.log(PlacementsDetials.value);
    } catch (error) {
        console.log("Error fetching placement:", error);
        throw error;
    }
};

const updateCinemasData = async () => {
    const formData = new FormData();
    formData.append("id", cinemas.value.id);
    formData.append("cinema_name", cinemas.value.cinema_name);
    formData.append("address_1", cinemas.value.address_1);
    formData.append("address_2", cinemas.value.address_2);
    formData.append("zip", cinemas.value.zip);
    formData.append("city", cinemas.value.city);
    formData.append("state", cinemas.value.state);
    formData.append("country", cinemas.value.country);
    formData.append("company_id", cinemas.value.company_id);
    if (cinemas.value.image instanceof File) {
        formData.append("image", cinemas.value.image);
    }
    try {
        await router.post("/dashboard/cinema/update", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    } catch (error) {
        console.error("Error updating cinema data:", error);
    }
};
//Placement Function
const updatePlacementDetails = async () => {
    let formData = new FormData();
    formData.append("placement_id", PlacementsDetials.value.id);
    formData.append("placement_name", PlacementsDetials.value.placement_name);
    formData.append("placement_width", PlacementsDetials.value.placement_width);
    formData.append(
        "placement_height",
        PlacementsDetials.value.placement_height
    );
    formData.append(
        "placement_material",
        PlacementsDetials.value.placement_material
    );
    formData.append("placement_price", PlacementsDetials.value.placement_price);
    formData.append(
        "placement_colors",
        PlacementsDetials.value.placement_colors
    );
    formData.append(
        "placement_description",
        PlacementsDetials.value.placement_description
    );
    formData.append(
        "placement_colors",
        PlacementsDetials.value.placement_colors
    );
    if (Placementsimage.value instanceof File) {
        formData.append("placement_image", Placementsimage.value);
    }
    try {
        await router.post("/dashboard/placement/update", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        closeModal();
        PlacementimagePreview.value = null;
    } catch (error) {
        console.error(error);
    }
};
const isShowDeletePlacement = ref(false);
const ShowDeletePlacement = (id) => {
    isShowDeletePlacement.value = true;
    DeleteConfirmationId.value = id;
};
const DeleteConfirmationId = ref(null);
const closeDeletePlacementModal = () => {
    isShowDeletePlacement.value = false;
};
const DeletePlacement = async () => {
    let formData = new FormData();
    formData.append("id", DeleteConfirmationId.value);
    try {
        await router.post("/dashboard/placement/delete", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        DeleteConfirmationId.value = null;
        closeDeletePlacementModal();
    } catch (error) {
        console.log(error);
    }
};

const isDifferenceAddressShow = ref(false);
const ToggleDifferenceAddress = () => {
  isDifferenceAddressShow.value = !isDifferenceAddressShow.value; 
}

const Placementsimage = ref<File | null>(null);
const PlacementimagePreview = ref<string | null>(null);
const previewPlacementImage = (event: Event) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        Placementsimage.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            PlacementimagePreview.value = e.target?.result // Generate base64 preview
        };
        reader.readAsDataURL(file); // Read the file as a data URL
    }
    console.log(Placementsimage.value);
};
</script>

<template>
    <DefaultLayout title="Placement List">
        <!-- Delete Placement Confirmation -->
        <DialogModal :show="isShowDeletePlacement">
            <template #title>
                <h2>DeletePlacement</h2>
            </template>
            <template #content>
                <p class="text-xl text-red">
                    Are you sure to Delete This Placement
                </p>
            </template>
            <template #footer>
                <button
                    @click="DeletePlacement"
                    class="w-20 h-10 bg-red-500 rounded-md text-white hover:bg-red-400 mx-3"
                >
                    Confirm
                </button>
                <button
                    @click="closeDeletePlacementModal"
                    class="w-20 h-10 bg-blue-500 rounded-md text-white hover:bg-blue-400"
                >
                    Close
                </button>
            </template>
        </DialogModal>
        <!-- End Delete Placement Confirmation -->
        <!-- Edit Placement Modal -->
        <DialogModal :show="isModalOpen">
            <template #title>
                <div class="flex">
                    <h2 class="text-2xl">Edit Placement</h2>
                </div>
            </template>
            <template #content>
                <div class="w-full my-2">
                    <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                    >
                        Placement Name
                    </label>
                    <input
                        type="text"
                        v-model="PlacementsDetials.placement_name"
                        class="input-style"
                        placeholder="Enter width"
                    />
                </div>
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Width
                        </label>
                        <input
                            type="text"
                            v-model="PlacementsDetials.placement_width"
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
                            v-model="PlacementsDetials.placement_height"
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
                            <select
                                v-model="PlacementsDetials.placement_material"
                                name="materials"
                                class="input-style"
                            >
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
                                v-model="PlacementsDetials.placement_price"
                                placeholder="Enter price"
                            />
                        </div>
                    </div>
                </div>
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
                                v-model="PlacementsDetials.placement_colors"
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
                            v-model="PlacementsDetials.placement_description"
                            cols="4"
                        />
                    </div>
                </div>
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row w-full">
                    <div class="xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                            >Upload Image</label
                        >
                        <input
                            type="file"
                            @change="previewPlacementImage"
                            class="input-style"
                        />
                    </div>
                    <div class="w-1/2">
                        <img
                            :src="
                                PlacementimagePreview == null
                                    ? `/storage/${PlacementsDetials.placement_image}`
                                    : PlacementimagePreview
                            "
                            alt="Placement Image"
                            class="object-cover w-full h-40 rounded-md"
                        />
                    </div>
                </div>
            </template>

            <template #footer>
                <button
                    @click="updatePlacementDetails"
                    class="w-20 h-10 bg-blue-500 rounded-md mx-2 text-white hover:bg-blue-400"
                >
                    Save
                </button>
                <button
                    @click="closeModal"
                    class="w-20 h-10 bg-red-500 rounded-md text-white hover:bg-red-400"
                >
                    Close
                </button>
            </template>
        </DialogModal>
        <!-- End Edit Placement Modal -->

        <!-- Add More Placement -->
        <DialogModal>
            <template #title>
                <h2>DeletePlacement</h2>
            </template>
            <template #content>
                <p class="text-xl text-red">
                    Are you sure to Delete This Placement
                </p>
            </template>
            <template #footer>
                <button
                    @click="DeletePlacement"
                    class="w-20 h-10 bg-red-500 rounded-md text-white hover:bg-red-400 mx-3"
                >
                    Confirm
                </button>
                <button
                    @click="closeDeletePlacementModal"
                    class="w-20 h-10 bg-blue-500 rounded-md text-white hover:bg-blue-400"
                >
                    Close
                </button>
            </template>
        </DialogModal>
        <!-- End Add More Placement -->

        <!-- Cinema Data -->
        <div
            class="mb-3 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <!-- Accordion Header -->
            <div
                class="px-4 py-6 md:px-6 xl:px-7.5 flex justify-between cursor-pointer"
                @click="toggleAccordion"
            >
                <h4 class="text-xl font-bold text-black dark:text-white">
                    Cinemas Name: {{ cinema_data.cinema_name }}
                </h4>
                <button>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        :class="isOpen ? 'rotate-180' : ''"
                        class="h-5 w-5 transform transition-transform"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>

            <!-- Accordion Content -->
            <div
                v-if="isOpen"
                class="px-4 py-4 md:px-6 xl:px-7.5 border-t border-stroke dark:border-strokedark"
            >
                <div
                    v-if="isDifferenceEmpty()"
                    class="mt-5 mb-5 w-full flex"
                >
                    <div class="mx-1">
                        <button
                            @click="ToggleDifferenceAddress"
                            :class="'font-bold text-md bg-blue-300 p-2 rounded-md text-black'"
                        >
                            Show Difference address
                        </button>
                    </div>
                </div>
                <div v-if="!isDifferenceAddressShow"> 
              
                    <div class="w-full flex">
       
                        <div class="w-1/2 my-1 mx-3">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                Cinema Name
                            </label>
                            <input
                                type="text"
                                v-model="cinemas.cinema_name"
                                class="input-style"
                            />
                        </div>
                        <div class="w-1/2 xl:w-1/1 my-1 mx-3">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                address_1
                            </label>
                            <input
                                type="text"
                                v-model="cinemas.address_1"
                                class="input-style"
                            />
                        </div>
                    </div>
                    <div class="w-full flex">
                        <div class="w-1/2 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                address_2
                            </label>
                            <input
                                type="text"
                                v-model="cinemas.address_2"
                                class="input-style text-[15px]"
                            />
                        </div>
                        <div class="w-1/2 xl:w-1/1 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                zip code
                            </label>
                            <input
                                type="text"
                                v-model="cinemas.zip"
                                class="input-style"
                            />
                        </div>
                    </div>
                    <div class="w-full flex">
                        <div class="w-1/2 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                city
                            </label>
                            <input
                                type="text"
                                v-model="cinemas.city"
                                class="input-style"
                            />
                        </div>
                        <div class="w-1/2 xl:w-1/1 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                state
                            </label>
                            <input
                                type="text"
                                v-model="cinemas.state"
                                class="input-style"
                            />
                        </div>
                    </div>
                </div>
                <!-- Start Difference Address -->
                <div v-if="isDifferenceAddressShow">
                    <div class="w-full">
                            <h2 class="text-2xl font-bold my-1 mx-3 mt-3 mb-3"> Difference Delivery Address </h2>
                        </div>
                    <div class="w-1/1 flex flex-col mx-3 my-1">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                        >
                            address_1
                        </label>
                        <input
                            type="text"
                            v-model="DiffenceAddress.address_1"
                            class="input-style"
                        />
                    </div>
                    <div class="w-full flex">
                        <div class="w-1/2 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                address_2
                            </label>
                            <input
                                type="text"
                                v-model="DiffenceAddress.address_2"
                                class="input-style text-[15px] "
                            />
                        </div>
                        <div class="w-1/2 xl:w-1/1 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                zip code
                            </label>
                            <input
                                type="text"
                                v-model="DiffenceAddress.zip"
                                class="input-style"
                            />
                        </div>
                    </div>
                    <div class="w-full flex">
                        <div class="w-1/2 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                city
                            </label>
                            <input
                                type="text"
                                v-model="DiffenceAddress.city"
                                class="input-style"
                            />
                        </div>
                        <div class="w-1/2 xl:w-1/1 my-1 mx-3 font-bold">
                            <label
                                class="mb-3 block text-sm font-medium text-black dark:text-white text-[15px]"
                            >
                                state
                            </label>
                            <input
                                type="text"
                                v-model="DiffenceAddress.state"
                                class="input-style"
                            />
                        </div>
                    </div>
                </div>
                <div
                    v-if="!isDifferenceAddressShow"
                    class="w-1/2 xl:w-1/1 my-1 mx-3 w-[250px] font-bold flex flex-col"
                >
                    <div v-if="cinema_data.image !== null">
                        <h2 class="my-4">Cinema Image</h2>
                        <img
                            width="250"
                            class="w-full rounded-md max-h-[300px] object-fill"
                            :src="
                                PreviewImage === null
                                    ? `/storage/${cinemas.image}`
                                    : PreviewImage
                            "
                            alt="Cinema Image"
                        />
                        <input
                            @change="previewImage"
                            type="file"
                            class="mt-4"
                        />
                    </div>
                    <div v-else class="my-4">
                        <p v-if="PreviewImage === null">No Image Found</p>
                        <img
                            v-if="PreviewImage"
                            :src="PreviewImage"
                            class="w-full rounded-md max-h-[300px] object-fill"
                            alt="Preview Image"
                        />
                        <input
                            @change="previewImage"
                            type="file"
                            class="mt-4"
                        />
                    </div>
                </div>
                <div class="my-5 mt-4" v-if="!isDifferenceAddressShow">
                    <button
                        @click="updateCinemasData"
                        class="w-30 h-10 mt-4 rounded-md text-black font-bold my-1 mx-3 bg-blue-400"
                    >
                        Update
                    </button>
                </div>
            </div>
        </div>
        <!-- End Cinema Data -->
        <!-- Placements List -->
        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div class="px-4 py-6 md:px-6 xl:px-7.5 flex justify-between">
                <h4 class="text-xl font-bold text-black dark:text-white">
                    Placements List
                </h4>
                <div
                    class="px-4 w-20 h-10 bg-blue-300 flex items-center justify-center rounded-md text-xl text-bold hover:opacity-[0.5]"
                >
                    Add
                </div>
            </div>

            <div
                class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <p class="font-medium">Placements Name</p>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="font-medium">Dimensions</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Color</p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="font-medium">Action</p>
                </div>
            </div>

            <div v-if="CinemaData.length === 0">No Data</div>

            <div
                v-if="CinemaData.length > 0"
                v-for="c in CinemaData"
                :key="c.id"
                class="grid grid-cols-6 border-t border-stroke px-4 py-4.5 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center"
                    >
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            {{ c.placement_name }} {{ c.id }}
                        </p>
                    </div>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="text-sm font-medium text-black dark:text-white">
                        {{ c.placement_height }}x{{ c.placement_height }}
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p class="text-sm font-medium text-black dark:text-white">
                        {{ c.placement_colors }}
                    </p>
                </div>
                <div class="col-span-1 flex items-center">
                    <p
                        @click="OpenModal(c.placement_ID)"
                        class="text-sm font-medium text-black mr-3 bg-red-300 w-10 text-center rounded-full"
                    >
                        Edit
                    </p>
                    <p
                        @click="ShowDeletePlacement(c.placement_ID)"
                        class="text-sm font-medium text-black bg-red-300 w-15 text-center rounded-full"
                    >
                        Delete
                    </p>
                </div>
            </div>
        </div>
        <!-- End Placements List -->
    </DefaultLayout>
</template>

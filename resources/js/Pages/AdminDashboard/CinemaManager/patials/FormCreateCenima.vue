<script setup lang="ts">
import { ref, defineProps, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    CinemasList: Object,
    Cinemas_id: Number,
});

const imageFile = ref(null);
const imagePreview = ref(null);

const previewImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        imageFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result; // Generate base64 preview
        };
        reader.readAsDataURL(file);
        cinemas.value[0].image = file;

        console.log(reader);
    }
};

const cinemaId = ref<number | null>(null);
const AdddifferenceAddress = ref(false);

interface Placement {
    cinema_id: number | null;
    name: string;
    description: string;
    height: string;
    width: string;
    colors: string;
    material: string;
    price: string;
    image: null;
    imagePreview: null;
}

interface Cinemas {
    name: string;
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
    company_id: number | any;
    image: null;
}
interface DifferenceCinemas {
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
}
const placements = ref<Placement[]>([]);

const cinemas = ref<Cinemas[]>([
    {
        name: "",
        address_1: "",
        address_2: "",
        zip: "",
        city: "",
        state: "",
        country: "",
        company_id: null,
        image: null,
    },
]);
const showPlacement = ref(true);
const DifferenceAddress = ref<DifferenceCinemas[]>([
    {
        address_1: "",
        address_2: "",
        zip: "",
        city: "",
        state: "",
        country: "",
    },
]);

const addPlacement = () => {
    placements.value.push({
        cinema_id: cinemaId.value,
        name: "",
        description: "",
        height: "",
        width: "",
        colors: "",
        material: "",
        price: "",
        image: null,
        imagePreview: null,
    });
};

const previewPlacement = (event, index: number) => {
    const file = event.target.files[0];
    if (file) {
        placements.value[index].image = file;
        placements.value[index].imagePreview = URL.createObjectURL(file);
    }
};

const removePlacement = (index: number) => {
    placements.value.splice(index, 1);
};

onMounted(() => {
    const urlParts = window.location.pathname.split("/");
    cinemaId.value = parseInt(urlParts[4]);
});

const saveCinemas = async () => {
    const formData = new FormData();
    formData.append("cinema_name", cinemas.value[0].name);
    formData.append("address_1", cinemas.value[0].address_1);
    formData.append("address_2", cinemas.value[0].address_2);
    formData.append("zip", cinemas.value[0].zip);
    formData.append("city", cinemas.value[0].city);
    formData.append("state", cinemas.value[0].state);
    formData.append("country", cinemas.value[0].country);
    formData.append("company_id", cinemas.value[0].company_id);
    if (cinemas.value[0].image instanceof File) {
        formData.append("image", cinemas.value[0].image);
    }
    //Validate Difference Address 
    const validAddresses = DifferenceAddress.value.filter(address => 
        address.address_1 || address.address_2 || address.zip || address.city || address.state || address.country
    );
    if(validAddresses.length > 0){
        formData.append("difference_addresses", JSON.stringify(validAddresses));
    }
    if (placements.value.length > 0) {
        formData.append(
            "placements",
            JSON.stringify(
                placements.value.map((placements) => {
                    const { image, imagePreview, ...data } = placements;
                    return data;
                })
            )
        );
        placements.value.forEach((placement, index) => {
            if (placement.image instanceof File) {
                formData.append(`placement_image_${index}`, placement.image);
            } else {
                console.log("No Placements to save to the database");
            }
        });
    }
    for (const [key, value] of formData.entries()) {
            console.log(key, value);
        }
    await router.post("/dashboard/addcinema", formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });
    showPlacement.value = true;
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
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Name
                        </label>
                        <input
                            v-model="cinemas[0].name"
                            type="text"
                            class="input-style"
                        />
                    </div>
                </div>

                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/1">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Address 1
                        </label>
                        <input
                            v-model="cinemas[0].address_1"
                            type="text"
                            class="input-style"
                        />
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
                        <input
                            v-model="cinemas[0].address_2"
                            type="text"
                            class="input-style"
                        />
                    </div>
                </div>
                <!-- End Address -->

                <!-- Zip/City -->
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Zip
                        </label>
                        <input
                            v-model="cinemas[0].zip"
                            type="text"
                            class="input-style"
                        />
                    </div>
                    <div class="w-full xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            City
                        </label>
                        <input
                            v-model="cinemas[0].city"
                            type="text"
                            class="input-style"
                        />
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
                        <input
                            v-model="cinemas[0].state"
                            type="text"
                            class="input-style"
                        />
                    </div>
                    <div class="w-full xl:w-1/2">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Country
                        </label>
                        <input
                            v-model="cinemas[0].country"
                            type="text"
                            class="input-style"
                        />
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
                        <select
                            v-model="cinemas[0].company_id"
                            class="input-style"
                        >
                            <option
                                v-for="company in $page.props.companyList"
                                :value="company.id"
                            >
                                {{ company.company_legalname }}
                            </option>
                        </select>
                    </div>
                </div>
                <!-- End Select Company -->
                <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                    <div class="w-full">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Logo
                        </label>
                        <input
                            @change="previewImage"
                            type="file"
                            class="input-style"
                        />
                    </div>
                </div>
                <div v-if="imageFile">
                    <h3>Image Preview</h3>
                    <div class="w-[250px] h-[250px] mt-2 rounded-md">
                        <img
                            :src="imagePreview"
                            class="object-cover w-[250px] h-[250px] mt-2 rounded-md"
                            alt=""
                        />
                    </div>
                </div>

                <!-- Add Difference Address -->
                <div>
                    <div
                        class="mb-5.5 mt-5 flex items-center justify-between border w-70 h-10 p-3 rounded-md"
                    >
                        <label for="formCheckbox" class="flex cursor-pointer">
                            <div class="relative pt-0.5">
                                <input
                                    v-model="AdddifferenceAddress"
                                    type="checkbox"
                                    id="formCheckbox"
                                    class="taskCheckbox sr-only"
                                />
                                <div
                                    class="box mr-3 flex h-5 w-5 items-center justify-center rounded border border-stroke dark:border-form-strokedark dark:bg-form-input"
                                >
                                    <span class="text-white opacity-0">
                                        <svg
                                            class="fill-current"
                                            width="10"
                                            height="7"
                                            viewBox="0 0 10 7"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M9.70685 0.292804C9.89455 0.480344 10 0.734667 10 0.999847C10 1.26503 9.89455 1.51935 9.70685 1.70689L4.70059 6.7072C4.51283 6.89468 4.2582 7 3.9927 7C3.72721 7 3.47258 6.89468 3.28482 6.7072L0.281063 3.70701C0.0986771 3.5184 -0.00224342 3.26578 3.785e-05 3.00357C0.00231912 2.74136 0.10762 2.49053 0.29326 2.30511C0.4789 2.11969 0.730026 2.01451 0.992551 2.01224C1.25508 2.00996 1.50799 2.11076 1.69683 2.29293L3.9927 4.58607L8.29108 0.292804C8.47884 0.105322 8.73347 0 8.99896 0C9.26446 0 9.51908 0.105322 9.70685 0.292804Z"
                                                fill=""
                                            ></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <p>Difference Delivery Address</p>
                        </label>
                    </div>
                    <!-- Start Difference Address -->
                    <div v-show="AdddifferenceAddress">
                        <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                            <div class="w-full xl:w-1/1">
                                <label
                                    class="mb-3 block text-sm font-medium text-black dark:text-white"
                                >
                                    Address 1
                                </label>
                                <input
                                    v-model="DifferenceAddress[0].address_1"
                                    type="text"
                                    class="input-style"
                                />
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
                                <input
                                    v-model="DifferenceAddress[0].address_2"
                                    type="text"
                                    class="input-style"
                                />
                            </div>
                        </div>
                        <!-- End Address -->

                        <!-- Zip/City -->
                        <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                            <div class="w-full xl:w-1/2">
                                <label
                                    class="mb-3 block text-sm font-medium text-black dark:text-white"
                                >
                                    Zip
                                </label>
                                <input
                                    v-model="DifferenceAddress[0].zip"
                                    type="text"
                                    class="input-style"
                                />
                            </div>
                            <div class="w-full xl:w-1/2">
                                <label
                                    class="mb-3 block text-sm font-medium text-black dark:text-white"
                                >
                                    City
                                </label>
                                <input
                                    v-model="DifferenceAddress[0].city"
                                    type="text"
                                    class="input-style"
                                />
                            </div>
                        </div>
                        <!-- End Zip/City -->

                        <!-- Region / Country -->
                        <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                            <div class="w-full xl:w-1/2">
                                <label
                                    class="mb-3 block text-sm font-medium text-black dark:text-white"
                                >
                                    Region/State
                                </label>
                                <input
                                    v-model="DifferenceAddress[0].state"
                                    type="text"
                                    class="input-style"
                                />
                            </div>
                            <div class="w-full xl:w-1/2">
                                <label
                                    class="mb-3 block text-sm font-medium text-black dark:text-white"
                                >
                                    Country
                                </label>
                                <input
                                    v-model="DifferenceAddress[0].country"
                                    type="text"
                                    class="input-style"
                                />
                            </div>
                        </div>
                        <!-- End Region / Country -->
                    </div>
                    <!-- End Difference Address -->
                </div>
                <div
                    v-if="showPlacement"
                    class="mb-4.5 flex flex-col gap-6 xl:flex-row"
                >
                    <div class="w-full mt-5">
                        <div
                            class="w-[20%] h-10 flex items-center text-black text-2xl"
                        >
                            Cinema Placement
                        </div>
                        <!-- Loop through placements to generate dynamic forms -->
                        <div
                            v-for="(placement, index) in placements"
                            :key="index"
                            class="w-full mt-2 border-b pb-4"
                        >
                            <label
                                class="mb-1 block text-sm font-medium text-black dark:text-white"
                            >
                                Place Name
                            </label>
                            <input
                                type="text"
                                v-model="placement.name"
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
                                        v-model="placement.width"
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
                                        v-model="placement.height"
                                        class="input-style"
                                        placeholder="Enter height"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div
                                    class="mb-4.5 flex flex-col gap-6 xl:flex-row"
                                >
                                    <div class="w-full xl:w-1/2">
                                        <label
                                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                                        >
                                            Material
                                        </label>
                                        <select
                                            v-model="placement.material"
                                            name="materials"
                                            class="input-style"
                                        >
                                            <option value="plasstic">
                                                plasstic
                                            </option>
                                            <option value="Vinyl">Vinyl</option>
                                            <option value="Vinyl">
                                                Meterials 1
                                            </option>
                                            <option value="Vinyl">
                                                Meterials 2
                                            </option>
                                            <option value="Vinyl">
                                                Meterials 3
                                            </option>
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
                                            v-model="placement.price"
                                            class="input-style"
                                            placeholder="Enter price"
                                        />
                                    </div>
                                </div>
                            </div>
                            <!-- Description -->
                            <div
                                class="mb-4.5 flex flex-col gap-6 xl:flex-row w-full"
                            >
                                <div class="w-1/2 flex items-center">
                                    <label
                                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                                    >
                                        Colors
                                    </label>
                                    <div>
                                        <input
                                            type="radio"
                                            v-model="placement.colors"
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
                                            v-model="placement.colors"
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
                                        v-model="placement.description"
                                        class="input-style"
                                        placeholder="Enter description"
                                        cols="4"
                                    />
                                </div>
                            </div>
                            <div
                                class="mb-4.5 flex flex-col gap-6 xl:flex-row w-full"
                            >
                                <div class="xl:w-1/2">
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
                                </div>
                                <div class="w-full xl:w-1/2">
                                    <!-- Show Image Preview for the Current Placement -->
                                    <div v-if="placement.imagePreview">
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
                                    </div>
                                </div>
                            </div>
                            <!-- Remove Placement Button -->
                            <button
                                class="mt-2 bg-red-500 text-white p-2 rounded"
                                @click="removePlacement(index)"
                            >
                                Remove Placement
                            </button>
                        </div>

                        <!-- Add Placement Button -->
                        <button
                            class="mt-2 mx-1 bg-blue-500 text-white p-2 rounded"
                            @click.prevent="addPlacement"
                        >
                            Add Placement
                        </button>

                        <!-- Submit Button -->
                        <button
                            class="mt-4 w-20 bg-green-500 text-white p-2 rounded"
                            @click.prevent="saveCinemas()"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

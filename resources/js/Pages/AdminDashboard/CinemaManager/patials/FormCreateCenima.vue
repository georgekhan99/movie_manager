<script setup lang="ts">
import { ref, defineProps, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    companyList: Object,
    Cinemas_id: Number
});

const cinemaId = ref<number | null>(null);

interface Placement {
    cinema_id: number | null;
    name: string;
    description: string;
    height: string;
    width: string;
    colors: string;
    material: string;
    price: string;
}

interface Cinemas {
    name: string;
    address_1: string;
    address_2: string;
    zip: string;
    city: string;
    state: string;
    country: string;
    company_id: number;
}

const placements = ref<Placement[]>([
    {
        cinema_id: null,
        name: "",
        description: "",
        height: "",
        width: "",
        colors: "",
        material: "",
        price: "",
    },
]);

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
    },
]);

const showPlacement = ref(false);

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
    });

    console.log(placements);

};

const removePlacement = (index: number) => {
    placements.value.splice(index, 1);
};

onMounted(() => {
    const urlParts = window.location.pathname.split('/');
    cinemaId.value = parseInt(urlParts[4]);
});

const saveCinemas = () => {
    router.post("/dashboard/addcinema", {
        cinemas: cinemas.value,
    });
    showPlacement.value = true;
};

const savePlacement = () => {
    console.log(props)
}


const ChecksavedData = () => {
    let saved = router.page.url.split('/')[4]
    return saved == null || saved == '' ? true : false;
};

</script>

<template>
  <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
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
                        <input type="file" class="input-style" />
                    </div>
                </div>
                <!-- submit Cinema -->
                 
                <button
                    class="mt-2 bg-blue-500 text-white p-2 rounded text-white p-2 rounded"
                    @click.prevent="saveCinemas"
                    v-show="ChecksavedData()"
                >
                    Submit
                </button>

                <!-- submit Cinema -->
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

                            <!-- Placement Details -->
                            <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                                <div class="w-full xl:w-1/2">
                                    <label
                                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                                    >
                                        Description
                                    </label>
                                    <input
                                        type="text"
                                        v-model="placement.description"
                                        class="input-style"
                                        placeholder="Enter description"
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
                                        Colors
                                    </label>
                                    <input
                                        type="text"
                                        v-model="placement.colors"
                                        class="input-style"
                                        placeholder="Enter colors"
                                    />
                                </div>
                            </div>

                            <div class="mb-4.5 flex flex-col gap-6 xl:flex-row">
                                <div class="w-full xl:w-1/2">
                                    <label
                                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                                    >
                                        Material
                                    </label>
                                    <input
                                        type="text"
                                        v-model="placement.material"
                                        class="input-style"
                                        placeholder="Enter material"
                                    />
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
                            class="mt-4 bg-green-500 text-white p-2 rounded"
                            @click.prevent="savePlacement"
                        >
                            Save Placements
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import DefaultLayout from "../../../Layouts/DefaultLayout.vue";
import { ref, defineProps, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    placement_id: Object,
    cinemas_data: Object || Boolean,
});

interface Placement {
    cinema_id?: number | null;
    name: string;
    description: string;
    height: string;
    width: string;
    colors: string;
    material: string;
    price: string;
    image: File | null;
    imagePreview: string | null;
}

const placements = ref<Placement[]>([
    {
        name: "",
        description: "",
        height: "",
        width: "",
        colors: "",
        material: "",
        price: "",
        image: null,
        imagePreview: null,
    },
]);

// Add a new placement form
const addPlacement = () => {
    placements.value.push({
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

// Remove a placement form
console.log(props.cinemas_data.id);
// Save all placement data
const savePlacements = () => {
    // You can send `placements.value` to the server for saving
    const formData = new FormData();
    // formData.append('Cinema_id',  cinemas_data);
    formData.append('Cinema_id', props.cinemas_data.id);
    placements.value.forEach((placement, index) => {
        formData.append(`placements[${index}][name]`, placement.name);
        formData.append(`placements[${index}][description]`, placement.description);
        formData.append(`placements[${index}][height]`, placement.height);
        formData.append(`placements[${index}][width]`, placement.width);
        formData.append(`placements[${index}][colors]`, placement.colors);
        formData.append(`placements[${index}][material]`, placement.material);
        formData.append(`placements[${index}][price]`, placement.price);

        // Handle image file upload if it exists
        if (placement.image) {
            formData.append(`placements[${index}][image]`, placement.image);
        }
    });
    router.post('/dashboard/placement/add/more', formData);
    // console.log("Saving placements:", placements.value);
   
};

// Preview image for a placement
const previewPlacementImage = (event: Event, index: number) => {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            placements.value[index].imagePreview = e.target?.result as string;
        };
        reader.readAsDataURL(file);
        placements.value[index].image = file;
    }
};
</script>
<template>
   
    <DefaultLayout v-if="!cinemas_data">
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <div class="flex items-center justify-center">
                <h1 class="flex items-center text-5xl text-center text-red-600">
                    Cinema Not Found
                </h1>
            </div>
        </div>
    </DefaultLayout>

    <DefaultLayout v-else title="Create Placement">
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <div
                class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
            >
                <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                    <h2 class="text-2xl font-bold">Create Placement</h2>
                </div>

                <!-- Dynamic Form Section -->
                <div class="px-6.5 py-4 space-y-6">
                    <div
                        v-for="(placement, index) in placements"
                        :key="index"
                        class="border-b pb-4"
                    >
                        <h3 class="text-xl font-semibold">
                            Placement {{ index + 1 }}
                        </h3>

                        <!-- Form Fields -->
                        <div>
                            <label class="block text-sm font-medium">
                                Place Name
                            </label>
                            <input
                                v-model="placement.name"
                                type="text"
                                class="input-style"
                                placeholder="Enter place name"
                            />

                            <div class="flex gap-4 mt-4">
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium">
                                        Width
                                    </label>
                                    <input
                                        v-model="placement.width"
                                        type="text"
                                        class="input-style"
                                        placeholder="Enter width"
                                    />
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-medium">
                                        Height
                                    </label>
                                    <input
                                        v-model="placement.height"
                                        type="text"
                                        class="input-style"
                                        placeholder="Enter height"
                                    />
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium">
                                    Material
                                </label>
                                <select
                                    v-model="placement.material"
                                    class="input-style"
                                >
                                    <option>Select Material</option>
                                    <option value="plastic">Plastic</option>
                                    <option value="vinyl">Vinyl</option>
                                    <option value="metal">Metal</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium">
                                    Color
                                </label>
                                <select
                                    v-model="placement.colors"
                                    class="input-style"
                                >
                                    <option>Select Color</option>
                                    <option value="1-4">1-4</option>
                                    <option value="Pantone">Pantone</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium">
                                    Price
                                </label>
                                <input
                                    v-model="placement.price"
                                    type="text"
                                    class="input-style"
                                    placeholder="Enter price"
                                />
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium">
                                    Description
                                </label>
                                <textarea
                                    v-model="placement.description"
                                    class="input-style"
                                    placeholder="Enter description"
                                ></textarea>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium">
                                    Upload Image
                                </label>
                                <input
                                    type="file"
                                    class="input-style"
                                    @change="(e) => previewPlacementImage(e, index)"
                                    
                                />
                                <div v-if="placement.imagePreview" class="mt-2">
                                    <h4 class="text-sm font-medium">
                                        Image Preview
                                    </h4>
                                    <img
                                        :src="placement.imagePreview"
                                        class="w-32 h-32 object-cover rounded"
                                        alt="Image Preview"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Add/Remove Buttons -->
                        <div class="flex justify-between mt-4">
                            <button
                                v-if="index > 0"
                                @click="removePlacement(index)"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                            >
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <button
                            @click="addPlacement"
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            Add Placement
                        </button>
                        <button
                            @click="savePlacements"
                            class="bg-green-500 text-white px-4 py-2 rounded"
                        >
                            Save Placements
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

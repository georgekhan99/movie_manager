<script setup>
import { defineProps, onMounted, onUpdated, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
const props = defineProps({
    data: Object,
    dataList: Array,
});

const response = ref([]);

const getData = async () => {
    let URL = await fetch("https://jsonplaceholder.typicode.com/users/");
    response.value = await URL.json();
};

const golog = (value) => {
    console.log(value)
}

onMounted(() => {
    getData();
});

const form = useForm({
    name: "",
    address: "",
    contact: "",
    distributer: "",
});

const submit = () => {
    form.post("/dashboard/addCinemas");
};
const navigateToDashboard = () => {
    window.location.href = '/dashboard';
};
</script>

<template>
    <AppLayout title="UserProfile">
        <main>
            <div class="w-full flex justify-center mt-2 pb-5">
                <div
                    class="w-[90%] bg-slate-200 rounded-md min-h-screen flex flex-row shadow-lg"
                >
                    <!-- sideBar -->
                    <div class="w-[20%] bg-white min-h-screen shadow-lg">
                        <div class="w-full h-[50px] bg-red-300 flex flex-col">
                            <div class="flex justify-center">
                                <p class="p-3 text-lg">Menu</p>
                            </div>
                        </div>
                    </div>
                    <!-- End sideBar -->
                    <!-- Content Area -->
                    <div class="w-full shadow-sm">
                        <div class="w-full bg-red-300 h-[50px] flex flex-row">
                            <div
                                class="w-full flex justify-center items-center"
                            >
                                <p class="text-2xl">Add Movie Placement</p>
                            </div>
                        </div>
                        <div class="p-5">
                            <div
                                class="flex flex-row flex-wrap justify-center row-wrap w-full"
                            >
                                <div
                                    v-for="row in response"
                                    :key="row.id"
                                    class="basis-1/4 bg-red-300 min-h-[150px] rounded-md m-2"
                                >
                                    <div
                                        class="w-full h-10 shadow-lg flex items-center shadow-md"
                                    >
                                        <div class="p-3">
                                            {{ row.name }}
                                        </div>
                                    </div>
                                    <!-- CardContents -->
                                     <!-- {{ row }} -->
                                     <div class="basis-2/4 m-3"> {{ row.website }} </div>       
                                     <div class="mt-3 flex flex-row justify-end ">
                                        <button @click="navigateToDashboard" class="w-[70px] h-7 bg-blue-300 rounded-md m-1 hover:bg-slate-300">Edit</button>
                                        <button class="w-[70px] h-7 bg-blue-300 rounded-md m-1 hover:bg-slate-300">Delete</button>
                                     </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content Area -->
                </div>
            </div>
        </main>
    </AppLayout>
</template>

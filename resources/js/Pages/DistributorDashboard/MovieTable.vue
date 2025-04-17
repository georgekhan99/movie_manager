<script setup lang="ts">
import { ref, computed } from "vue";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { router } from "@inertiajs/vue3";
import DialogModal from "@/Components/DialogModal.vue";
const props = defineProps({
    movie_list: Object,
    company_list: Object,
});

interface EditMovie {
    id?: number;
    movies_name: string;
    movies_release_date: string;
    company_id: number;
}
const isEditModalOpen = ref(false);
const EditMovieData = ref<EditMovie[]>([]);
const HandlerEditModal = (id: number, paylod: EditMovie[]) => {
    isEditModalOpen.value = true;
    EditMovieData.value = paylod;
    console.log(id, "Data:", EditMovieData.value);
};
const HandlerCloseModal = () => {
    isEditModalOpen.value = false;
};
const HandlerSaveMovie = async () => {
    try {
        let formData = new FormData();
        formData.append("id", EditMovieData.value.id);
        formData.append("movies_name", EditMovieData.value.movies_name);
        formData.append(
            "movies_release_date",
            EditMovieData.value.movies_release_date
        );
        formData.append("company_id", EditMovieData.value.company_id);
        await router.post("/distributor/edit/movie", formData);
        await HandlerCloseModal();
    } catch (error) {
        console.log("error:", error);
    }
};

const searchQuery = ref("");
const filteredMovies = computed(() => {
  return props.movie_list.filter((movie: any) =>
    movie.movies_name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const HandleDeleteMovie = async (id) => {
    try {
        await router.get("/distributor/delete/movie/" + id);
    } catch (error) {
        console.log(error);
    }
};
</script>
<template>
    <DialogModal :show="isEditModalOpen">
        <template #title>
            <h2>Edit Movie</h2>
        </template>
        <template #content>
            <div class="w-full">
                <div class="flex flex-col">
                    <div class="w-full mx-1 my-2">
                        <label for="Movie Name" class="text-black font-bold"
                            >Movie Name</label
                        >
                        <input
                            type="text"
                            id="Movie Name"
                            v-model="EditMovieData.movies_name"
                            class="input-style"
                        />
                    </div>
                    <div class="w-full mx-1 my-2">
                        <label for="moviecompany" class="text-black font-bold"
                            >Company</label
                        >
                        <select
                            name="MovieCompany"
                            id="moviecompany"
                            class="input-style"
                            v-model="EditMovieData.company_id"
                        >
                            <option value="">Select Company</option>
                            <option
                                v-for="company in company_list"
                                :value="company.id"
                            >
                                {{ company.company_legalname }}
                            </option>
                        </select>
                    </div>
                    <div class="w-full mx-1 my-2">
                        <label for="Movie Name" class="text-black font-bold"
                            >Release Date</label
                        >
                        <input
                            type="date"
                            id="Movie Name"
                            class="input-style"
                            v-model="EditMovieData.movies_release_date"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="w-full flex justify-end">
                <button
                    @click="HandlerSaveMovie"
                    class="w-10 bg-blue-500 text-white h-10 w-20 rounded-md"
                >
                    Update
                </button>
                <button
                    @click="HandlerCloseModal"
                    class="w-10 mx-3 bg-yellow-500 text-white h-10 w-20 rounded-md"
                >
                    Close
                </button>
            </div>
        </template>
    </DialogModal>

    <DefaultLayout title="UserList">
        <!-- Filter and Sort Header -->
        <div
            class="grid grid-cols-12 justify-center items-center h-20 mb-5 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <!-- Search Input -->
            <div class="col-span-3 px-4">
                <input
                    type="text"
                    placeholder="Search by Movie Name"
                    v-model="searchQuery"
                    class="w-full h-12 px-5 rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary"
                />
            </div>

            <!-- Sort Dropdown -->
            <div class="col-span-3 px-4">
                <select class="input-style">
                    <option value="users.name">Sort by Name</option>
                    <option value="user_roles.name">Sort by Role</option>
                    <option value="company.company_legalname">
                        Sort by Company
                    </option>
                </select>
            </div>
            <div class="col-span-2 h-10 px-2">
                <select class="input-style">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>

        <!-- User Table -->
        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div class="py-6 px-4 md:px-6 xl:px-7.5 flex justify-between">
                <h4 class="text-xl font-bold text-black dark:text-white">
                    Movie Management
                </h4>

                <a href="/bookings/manager/distributor" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 text-sm font-medium shadow-md">
                View Booking Detail
                </a>
            </div>

            <!-- Table Header -->
            <div
                class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <p class="font-medium">Movie Name</p>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="font-medium">Release date</p>
                </div>
                <div class="col-span-1 flex justify-center items-center">
                    <p class="font-medium">Action</p>
                </div>
            </div>

            <!-- Table Rows -->
            <div
                v-for="(movie, index) in filteredMovies"
                :key="movie.id"
                class="grid grid-cols-6 border-t border-stroke py-4.5 px-4 dark:border-strokedark sm:grid-cols-8 md:px-6 2xl:px-7.5"
            >
                <div class="col-span-3 flex items-center">
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center"
                    >
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            {{ movie.movies_name }}
                        </p>
                    </div>
                </div>
                <div class="col-span-2 hidden items-center sm:flex">
                    <p class="text-sm font-medium text-black dark:text-white">
                        {{ movie.movies_release_date }}
                    </p>
                </div>
                <div
                    class="col-span-1  flex items-center justify-center cursor-pointer"
                >
                    <a
                        class="hover:text-primary relative group"
                        @click="HandlerEditModal(movie.id, movie)"
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
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden w-max px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 group-hover:block transition-opacity duration-900"
                        >
                            Edit Movie
                        </span>
                    </a>
                    <a
                        class="hover:text-primary relative group mx-5"
                        @click="HandleDeleteMovie(movie.id)"
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
                                d="M14.7223 12.7585C14.7426 12.3448 14.4237 11.9929 14.01 11.9726C13.5963 11.9522 13.2444 12.2711 13.2241 12.6848L12.9999 17.2415C12.9796 17.6552 13.2985 18.0071 13.7122 18.0274C14.1259 18.0478 14.4778 17.7289 14.4981 17.3152L14.7223 12.7585Z"
                                fill="#343C54"
                            ></path>
                            <path
                                d="M9.98802 11.9726C9.5743 11.9929 9.25542 12.3448 9.27577 12.7585L9.49993 17.3152C9.52028 17.7289 9.87216 18.0478 10.2859 18.0274C10.6996 18.0071 11.0185 17.6552 10.9981 17.2415L10.774 12.6848C10.7536 12.2711 10.4017 11.9522 9.98802 11.9726Z"
                                fill="#343C54"
                            ></path>
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M10.249 2C9.00638 2 7.99902 3.00736 7.99902 4.25V5H5.5C4.25736 5 3.25 6.00736 3.25 7.25C3.25 8.28958 3.95503 9.16449 4.91303 9.42267L5.54076 19.8848C5.61205 21.0729 6.59642 22 7.78672 22H16.2113C17.4016 22 18.386 21.0729 18.4573 19.8848L19.085 9.42267C20.043 9.16449 20.748 8.28958 20.748 7.25C20.748 6.00736 19.7407 5 18.498 5H15.999V4.25C15.999 3.00736 14.9917 2 13.749 2H10.249ZM14.499 5V4.25C14.499 3.83579 14.1632 3.5 13.749 3.5H10.249C9.83481 3.5 9.49902 3.83579 9.49902 4.25V5H14.499ZM5.5 6.5C5.08579 6.5 4.75 6.83579 4.75 7.25C4.75 7.66421 5.08579 8 5.5 8H18.498C18.9123 8 19.248 7.66421 19.248 7.25C19.248 6.83579 18.9123 6.5 18.498 6.5H5.5ZM6.42037 9.5H17.5777L16.96 19.7949C16.9362 20.191 16.6081 20.5 16.2113 20.5H7.78672C7.38995 20.5 7.06183 20.191 7.03807 19.7949L6.42037 9.5Z"
                                fill="#343C54"
                            ></path>
                        </svg>
                        <span
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden w-max px-2 py-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 group-hover:block transition-opacity duration-900"
                        >
                            Delete Movie
                        </span>
                    </a>
                    <a
                        class="hover:text-primary relative group mx-1"
                        :href="`/distributor/bookings/${movie.id}`"
                    >
                    Booking
                    </a>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

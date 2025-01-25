<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import { ref, defineProps } from "vue";
interface Movie {
    Movie_name: string;
    Company_name: string;
    Release_date: string;
}

const MovieData = ref<Movie>({
    Movie_name: "",
    Company_name: "",
    Release_date: "",
});

const saveMovie = async () => {
    let form = new FormData();
    form.append("Movie_name", MovieData.value.Movie_name);
    form.append("Company_name", MovieData.value.Company_name);
    form.append("Release_date", MovieData.value.Release_date);

    await router.post("/distributor/movie/save", form);
};
</script>

<template>


    <div
        class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Create Movie
        </h2>

        <nav>
            <ol class="flex items-center gap-2">
                <li class="font-medium">Distributor Manager</li>
                /
                <li class="font-medium text-primary">Create Movie</li>
            </ol>
        </nav>
    </div>
    <div
        class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
    >
        <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
            <h3 class="font-medium text-black dark:text-white">Create Movie</h3>
        </div>
        <div class="p-6.5">
            <div class="mb-4.5">
                <label
                    class="mb-3 block text-sm font-bold text-black dark:text-white"
                >
                    Movie Name
                </label>
                <input
                    type="text"
                    name="name"
                    v-model="MovieData.Movie_name"
                    placeholder="Enter your full name"
                    class="input-style"
                />
                <!-- <InputError v-if="$page.props.errors.Name" :message="$page.props.errors.Name" /> -->
            </div>

            <div class="mb-4.5">
                <label
                    class="mb-3 block text-sm font-bold text-black dark:text-white"
                >
                    Select Company
                </label>
                <select
                    v-model="MovieData.Company_name"
                    class="input-style"
                    id=""
                >
                    <option value="">Please Select Company</option>
                    <option
                        v-for="companylist in $page.props.company_list"
                        :value="companylist.id"
                    >
                        {{ companylist.company_legalname }}
                    </option>
                </select>
                <!-- <InputError v-if="$page.props.errors.Surname" :message="$page.props.errors.Name" /> -->
            </div>
            <div class="mb-4.5">
                <label
                    class="mb-3 block text-sm font-bold text-black dark:text-white"
                >
                    Release Date
                </label>
                <input
                    v-model="MovieData.Release_date"
                    type="date"
                    placeholder="Enter your full name"
                    class="input-style"
                />
                <!-- <InputError v-if="$page.props.errors.Surname" :message="$page.props.errors.Name" /> -->
            </div>
            <button
                @click="saveMovie"
                class="flex w-40 p-3 justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90"
            >
                Submit
            </button>
        </div>
    </div>
</template>

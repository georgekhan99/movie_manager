<script setup lang="ts">
import { ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

// Define Interfaces
interface Duration {
    duration_id: number;
    start_date: string;
    delivery_date: string;
    booking_status: string; // Updated to reflect new status field
    movies_competing: string | null;
}

interface Placement {
    placement_id: number;
    placement_name: string;
}

const page = usePage();
const placement = ref<Placement>(page.props.placement);
const durations = ref<Duration[]>(page.props.durations);
const statusFilter = ref<string>(page.props.statusFilter || "all");

const showModal = ref(false);
const selectedDurationId = ref<number | null>(null);
const selectedMovie = ref<string | null>(null);
const competingMovies = ref<{ id: number; movie_name: string }[]>([]);

const updateFilter = (status: string) => {
    router.get(`/bookings/placements/status/${placement.value.placement_id}`, {
        status,
    });
};

const openConfirmModal = (duration: Duration) => {
    selectedDurationId.value = duration.duration_id;
    competingMovies.value = duration.movies_competing
        ? JSON.parse(duration.movies_competing)
        : [];
    showModal.value = true;
};

const confirmBooking = async () => {
    if (!selectedDurationId.value || !selectedMovie.value) {
        alert("Please select a movie.");
        return;
    }

    try {
        await router.post(
            `/bookings/placements/confirm`,
            {
                placement_id: placement.value.placement_id,
                duration_id: selectedDurationId.value,
                selected_movie: selectedMovie.value,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    showModal.value = false;
                    router.visit(window.location.href, {
                        only: ["durations"],
                        replace: true,
                    });
                    toast.success("You Have Confirmed Duration Successfully");
                },
                onError: (error) => {
                    console.error("Error confirming booking:", error);
                    alert("Failed to confirm booking. Please try again.");
                },
            }
        );
    } catch (err) {
        console.error("Unexpected error:", err);
        alert("An unexpected error occurred.");
    }
};
</script>

<template>
    <DefaultLayout>
        <div
            class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Placement: {{ placement.placement_name }}
            </h2>
            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium" href="/">Cinemas /</a>
                    </li>
                    <li class="font-medium text-primary">Placement Status</li>
                </ol>
            </nav>
        </div>

        <div class="flex mb-4">
            <div role="tablist" class="flex space-x-2">
                <button
                    class="px-4 py-2 rounded-full transition duration-300"
                    :class="
                        statusFilter === 'all'
                            ? 'bg-primary text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-primary hover:text-white'
                    "
                    @click="updateFilter('all')"
                >
                    All
                </button>
                <button
                    class="px-4 py-2 rounded-full transition duration-300"
                    :class="
                        statusFilter === 'pending'
                            ? 'bg-primary text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-primary hover:text-white'
                    "
                    @click="updateFilter('pending')"
                >
                    Pending
                </button>
                <button
                    class="px-4 py-2 rounded-full transition duration-300"
                    :class="
                        statusFilter === 'accepted'
                            ? 'bg-primary text-white'
                            : 'bg-gray-200 text-gray-700 hover:bg-primary hover:text-white'
                    "
                    @click="updateFilter('accepted')"
                >
                    Accepted
                </button>
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="min-w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="border border-gray-300 px-6 py-3 w-1/3">
                            Duration
                        </th>
                        <th class="border border-gray-300 px-6 py-3 w-1/3">
                            Status
                        </th>
                        <th class="border border-gray-300 px-6 py-3 w-1/3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template
                        v-for="(duration, index) in durations"
                        :key="index"
                    >
                        <tr class="hover:bg-gray-100">
                            <td
                                class="border border-gray-300 px-6 py-4 text-gray-700"
                            >
                                {{ duration.start_date }} -
                                {{ duration.delivery_date }}
                            </td>
                            <td
                                class="border border-gray-300 px-6 py-4 text-gray-700"
                            >
                                <span
                                    v-if="duration.status === 'Accepted'"
                                    class="text-blue-500 font-semibold"
                                >
                                    Accepted ({{ duration.accepted_movie }})
                                </span>
                                <span
                                    v-else-if="duration.status === 'Pending'"
                                    class="text-yellow-500 font-semibold"
                                >
                                    Pending
                                </span>
                                <span
                                    v-else
                                    class="text-green-500 font-semibold"
                                    >Available</span
                                >
                            </td>
                            <td
                                class="border border-gray-300 px-6 py-4 text-center"
                            >
                                <button
                                    v-if="duration.status === 'Pending'"
                                    class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-400 mr-2"
                                    @click="openConfirmModal(duration)"
                                >
                                    Confirm
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded-md shadow-md w-96">
                <h2 class="text-lg font-semibold mb-4">Select a Movie</h2>
                <select
                    v-model="selectedMovie"
                    class="w-full p-2 border rounded-md"
                >
                    <option
                        v-for="movie in competingMovies"
                        :key="movie.id"
                        :value="movie.id"
                    >
                        {{ movie.movie_name }}
                    </option>
                </select>
                <div class="flex justify-end mt-4">
                    <button
                        class="bg-green-500 text-white px-4 py-2 rounded-md mr-2"
                        @click="confirmBooking"
                    >
                        Confirm
                    </button>
                    <button
                        class="bg-gray-400 text-white px-4 py-2 rounded-md"
                        @click="showModal = false"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

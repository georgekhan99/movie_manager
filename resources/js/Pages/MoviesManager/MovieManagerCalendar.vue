<script setup lang="ts">
import { ref } from "vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { router, usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";

const page = usePage();
const durations = ref(page.props.durations || []);
const calendarData = ref(page.props.calendarData || []);
const currentOffset = ref(page.props.currentOffset || 0);

const modalVisible = ref(false);
const selectedBookings = ref([]);
const selectedPlacement = ref(null);
const selectedDuration = ref(null);

// ฟอร์แมตวันเริ่มต้น - วันสิ้นสุด
const formatRange = (start, end) => `${dayjs(start).format("DD/MM")} - ${dayjs(end).format("DD/MM")}`;
const formatDeadline = (deadline) => dayjs(deadline).format("DD/MM");

// 🔄 Filter bookings เฉพาะที่ duration_id ตรง และ release_date ตรงกับ start_date
const getBookings = (placement, durationId) => {
    const duration = durations.value.find(d => d.id === durationId);
    if (!duration) return [];

    return (placement.bookings || []).filter(b =>
        b.duration_id === durationId &&
        dayjs(b.movies_release_date).isSame(dayjs(duration.start_date), 'day')
    );
};

// กำหนดสีของ cell ตามสถานะ booking
const cellColor = (placement, durationId) => {
    const bookings = getBookings(placement, durationId);
    if (bookings.some(b => b.status === "accepted")) return "bg-green-200";
    if (bookings.length) return "bg-yellow-100";
    return "bg-white";
};

// เปิด modal สำหรับ confirm booking
const openModal = (placement, durationId) => {
    selectedBookings.value = getBookings(placement, durationId);
    selectedPlacement.value = placement.placement_id;
    selectedDuration.value = durationId;
    modalVisible.value = true;
};

// ไปยัง offset ถัดไป / ก่อนหน้า
const goToOffset = (offset) => {
    router.get(route('adminpage.cinema.bookingCalendar'), { offset }, { preserveState: true });
};

// ทดสอบ confirm booking (รอเชื่อม backend)
const confirmBooking = (booking) => {
    console.log("✅ Confirming movie:", booking.movie_name);
    modalVisible.value = false;
};
</script>

<template>
    <DefaultLayout title="Cinema Booking Calendar">
        <div class="p-6 bg-white rounded shadow">
            <h1 class="text-xl font-bold mb-4">Booking Calendar</h1>

            <!-- 🔄 ปุ่มเปลี่ยนช่วงเวลา -->
            <div class="flex justify-end gap-4 mb-2">
                <button class="text-sm px-3 py-1 bg-gray-200 rounded" @click="goToOffset(currentOffset - 1)">← Previous</button>
                <button class="text-sm px-3 py-1 bg-gray-200 rounded" @click="goToOffset(currentOffset + 1)">Next →</button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-3 py-2 border">Placement</th>
                            <th class="px-3 py-2 border">W x H</th>
                            <th v-for="d in durations" :key="d.id" class="px-2 py-1 border text-center">
                                {{ formatRange(d.start_date, d.end_date) }}
                                <div class="text-gray-300 text-xs">DL: {{ formatDeadline(d.production_deadline) }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(group, gIndex) in calendarData" :key="gIndex">
                            <tr>
                                <td :colspan="2 + durations.length" class="bg-gray-100 px-3 py-2 font-bold">
                                    🎬 {{ group.group_name }}
                                </td>
                            </tr>
                            <tr v-for="placement in group.placements" :key="placement.placement_id">
                                <td class="border px-2 py-1">{{ placement.placement_name }}</td>
                                <td class="border px-2 py-1 text-center">{{ placement.width }} x {{ placement.height }}</td>
                                <td v-for="d in durations" :key="d.id"
                                    :class="['border px-2 py-1 text-center', cellColor(placement, d.id)]">
                                    <template v-if="getBookings(placement, d.id).length">
                                        <div v-for="booking in getBookings(placement, d.id)" :key="booking.movie_id"
                                            class="truncate cursor-pointer"
                                            @click="openModal(placement, d.id)">
                                            {{ booking.movie_name }}
                                            <span v-if="booking.status === 'pending'">(รอ)</span>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <span class="text-gray-400">Available</span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- ✅ Modal -->
            <div v-if="modalVisible" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white w-[400px] p-6 rounded shadow">
                    <h2 class="text-lg font-bold mb-4">Confirm Booking</h2>
                    <div v-for="booking in selectedBookings" :key="booking.movie_id" class="border p-2 mb-2 flex justify-between">
                        <span>{{ booking.movie_name }}</span>
                        <button class="bg-green-500 text-white px-3 py-1 text-xs rounded hover:bg-green-600"
                                @click="confirmBooking(booking)">
                            Accept
                        </button>
                    </div>
                    <div class="text-right">
                        <button class="text-sm text-gray-600" @click="modalVisible = false">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<style scoped>
th, td {
    min-width: 120px;
    white-space: nowrap;
}
</style>

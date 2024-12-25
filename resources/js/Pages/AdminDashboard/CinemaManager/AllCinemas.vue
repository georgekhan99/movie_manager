<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import CinemaInfomations from './patials/CinemaInfomations.vue'
import CinemaTable from './patials/CinemaTable.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import { ref } from 'vue';

let is_modal = ref(false);



</script>

<template>
<DefaultLayout title="Create Cinema">
    <CinemaInfomations />
    <div class="mt-10">
        <CinemaTable />
    </div>

</DefaultLayout>
</template>
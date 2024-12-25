<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import PlacementTable from './patials/PlacementTable.vue'
import CinemaTable from './patials/CinemaTable.vue'
import CinemaInfomations from './patials/CinemaInfomations.vue'
</script>

<template>
<DefaultLayout title="Create Cinema">
    <div class="mt-5">
        <CinemaInfomations class="mb-10"/>
        <PlacementTable />
    </div>

</DefaultLayout>
</template>
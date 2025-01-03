<script setup lang="ts">
import DefaultLayout from '../../../Layouts/DefaultLayout.vue'
//@/layouts/DefaultLayout.vue
import CinemaTable from './patials/CinemaTable.vue'
import CinemaInfomations from './patials/CinemaInfomations.vue'
</script>

<template>
<DefaultLayout title="Create Cinema">
    <div class="mt-5">
        <PlacementTable />
    </div>

</DefaultLayout>
</template>
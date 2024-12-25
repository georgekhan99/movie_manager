<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue";

defineProps({
    CompanyData: String
})

</script>


<template>
<DefaultLayout>
    {{ CompanyData }}

    

</DefaultLayout>
</template>
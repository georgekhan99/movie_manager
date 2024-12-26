<script setup lang="ts">
import { computed, defineProps, onMounted, onUpdated, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import DefaultLayout from "@/layouts/DefaultLayout.vue";
const props = defineProps({
    user: Object,
    roleList: Array,
    company_list: Array,
    errors: Object,
});
const form = useForm({
    id: props.user.id,
    Name: props.user.name,
    Surname: props.user.surname,
    Email: props.user.email,
    Role: props.user.user_role_id,
    Company: props.user.user_company_id,
    Password: "",
    Retype: "",
});

const formsubmit = () => {
    form.post(route("update.user"), {
        onSuccess: () => {
            form.reset();
        },
    });

    console.log(form);
};
</script>

<template>
    <DefaultLayout title="Admin Dashboard">

        <div
            class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Create User
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li class="font-medium">Create Company</li>
                    /
                    <li class="font-medium text-primary">Create User</li>
                </ol>
            </nav>
        </div>
        <div
            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark"
        >
            <div
                class="border-b border-stroke px-6.5 py-4 dark:border-strokedark"
            >
                <h3 class="font-medium text-black dark:text-white">
                    Sign Up Form
                </h3>
            </div>
            <form @submit.prevent="formsubmit()">
                <div class="p-6.5">
                    <div class="mb-4.5">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Name
                        </label>
                        <input
                            v-model="form.Name"
                            type="text"
                            name="name"
                            placeholder="Enter your full name"
                            class="input-style"
                        />
                        <InputError message="$page.props.errors.Name" />
                    </div>

                    <div class="mb-4.5">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Surname
                        </label>
                        <input
                            v-model="form.Surname"
                            type="text"
                            placeholder="Enter your full name"
                            class="input-style"
                        />
                        <InputError />
                    </div>

                    <div class="mb-4.5">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Email
                        </label>
                        <input
                            v-model="form.Email"
                            type="email"
                            placeholder="Enter your email address"
                            class="input-style"
                        />
                        <InputError />
                    </div>

                    <div class="mb-4.5">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Role
                        </label>
                        <select
                            v-model="form.Role"
                            class="input-style"
                            name="role_selector"
                            id=""
                        >
                            <option
                                v-for="r in props.roleList"
                                :value="r.id"
                                :key="r.id"
                            >
                                {{ r.name }}
                            </option>
                        </select>

                        <InputError />
                    </div>
                    <div class="mb-4.5">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Company
                        </label>
                        <select
                            v-model="form.Company"
                            class="input-style"
                            name="company_selector"
                            id=""
                        >
                            <option
                                v-for="c in props.company_list"
                                :value="c.id"
                                :key="c.id"
                            >
                                {{ c.id }} - {{ c.company_legalname }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4.5">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Password
                        </label>
                        <input
                            type="password"
                            placeholder="Enter password"
                            class="input-style"
                        />
                        <InputError />
                    </div>

                    <div class="mb-5.5">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Re-type Password
                        </label>
                        <input
                            type="password"
                            placeholder="Re-enter password"
                            class="input-style"
                        />
                        <InputError />
                    </div>

                    <button
                        type="submit"
                        class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90"
                    >
                        Update
                    </button>
                </div>
            </form>
        </div>
    </DefaultLayout>
</template>

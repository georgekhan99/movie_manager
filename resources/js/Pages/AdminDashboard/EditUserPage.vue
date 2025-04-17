<script setup lang="ts">
import { computed, defineProps, onMounted, onUpdated, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
//@/layouts/DefaultLayout.vue
const props = defineProps({
    user: Object,
    roleList: Array,
    company_list: Array,
    errors: Object,
});

const showPassword = ref(false);
const showRetype = ref(false);

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
                                {{ c.company_legalname }}
                            </option>
                        </select>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4.5 relative">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Password
                        </label>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.Password"
                            placeholder="Enter password"
                            class="input-style pr-10"
                        />
                        <span
                            class="absolute right-3 top-12 cursor-pointer text-gray-500"
                            @click="showPassword = !showPassword"
                        >
                            <svg
                                v-if="!showPassword"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                />
                            </svg>

                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                                />
                            </svg>
                        </span>
                        <InputError :message="form.errors.Password" />
                    </div>

                    <!-- Retype Password Field -->
                    <div class="mb-4.5 relative">
                        <label
                            class="mb-3 block text-sm font-medium text-black dark:text-white"
                        >
                            Re-type Password
                        </label>
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.Retype"
                            placeholder="Re-enter password"
                            class="input-style pr-10"
                        />
                        <span
                            class="absolute right-3 top-12 cursor-pointer text-gray-500"
                            @click="showPassword = !showPassword"
                        >
                            <svg
                                v-if="!showPassword"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                />
                            </svg>

                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="size-6"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                                />
                            </svg>
                        </span>
                        <InputError :message="form.errors.Retype" />
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

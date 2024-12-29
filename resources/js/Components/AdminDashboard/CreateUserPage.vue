<script setup lang="ts">
import { computed, defineProps, onMounted, onUpdated, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import InputError from '../InputError.vue';
const SelectedRole = defineModel<number | string | null>({
    default: 1,
});


defineProps({
    company_list: Array<any>,
    users_role: Array<any>,
    errors: Object 
});

const Form = useForm({
    Name:'',
    Surname:'',
    Email:'',
    Role:null,
    Password:'',
    Retype:'',
    Company:null
});

const isAdmin = computed(() => {
    return Form.Role === 1;
});

const formsubmit = () =>{
    Form.post(route('create.user'), {
        onSuccess: ()=> {
            Form.reset();
        }
    });
}
</script>
<template>  
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
        <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
            <h3 class="font-medium text-black dark:text-white">Sign Up Form</h3>
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
                        v-model="Form.Name"
                        type="text"
                        name="name"
                        placeholder="Enter your full name"
                        class="input-style"
                    />
                     <InputError v-if="$page.props.errors.Name" :message="$page.props.errors.Name" />
                </div>

                <div class="mb-4.5">
                    <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                    >
                        Surname
                    </label>
                    <input
                        v-model="Form.Surname"
                        type="text"
                        placeholder="Enter your full name"
                        class="input-style"
                    />
                    <InputError v-if="$page.props.errors.Surname" :message="$page.props.errors.Name" />
                </div>

                <div class="mb-4.5">
                    <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                    >
                        Email
                    </label>
                    <input
                        v-model="Form.Email"
                        type="email"
                        placeholder="Enter your email address"
                        class="input-style"
                    />
                    <InputError v-if="$page.props.errors.Email" :message="$page.props.errors.Name" />
                </div>

                <div class="mb-4.5">
                    <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                    >
                        Role
                    </label>
                    <select
                        v-model="Form.Role"
                        class="input-style"
                        name="role_selector"
                        id=""
                    >
                        <option
                            v-for="role in $page.props.users_role"
                            :key="role.id"
                            :value="role.id"
                            
                        >
                            {{ role.name }}
                        </option>
                    </select>
                    <InputError v-if="$page.props.errors.Email" :message="$page.props.errors.Role" />
                </div>
                <div class="mb-4.5" v-if="!isAdmin">
                    <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                    >
                        Company
                    </label>
                    <select  v-model="Form.Company" class="input-style" name="role_selector" id="">
                        <option
                            v-for="c_list in $page.props.company_list"
                            :value="c_list.id"
                            :key="c_list.id"
                        >
                            {{ c_list.company_legalname }}
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
                         v-model="Form.Password"
                        type="password"
                        placeholder="Enter password"
                        autocomplete="password"
                        class="input-style"
                    />
                    <InputError v-if="$page.props.errors.Email" :message="$page.props.errors.Password" />
                </div>
                
                <div class="mb-5.5">
                    <label
                        class="mb-3 block text-sm font-medium text-black dark:text-white"
                    >
                        Re-type Password
                    </label>
                    <input
                        v-model="Form.Retype"
                        type="password"
                        placeholder="Re-enter password"
                        autocomplete="re-enter-password"
                        class="input-style"
                    />
                    <InputError v-if="$page.props.errors.Email" :message="$page.props.errors.Retype" />
                </div>

                <button
                    type="submit"
                    class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90"
                >
                    Sign Up
                </button>
            </div>
        </form>
    </div>
</template>

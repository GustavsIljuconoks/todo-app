<style scoped>
    .show {
        display: block;
    }

    .hide {
        display: none;
    }
</style>

<template>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            TODO APP
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Create and account
                </h1>

                <div v-if="error" :class="{ 'show': showError, 'hide': showError == false }" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert" >
                    <span class="font-medium">{{ error }}</span>
                </div>

                <form @submit.prevent="register" class="space-y-4 md:space-y-6">
                    <div class="form-group w-full">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your name</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            name="name"
                            id="name"
                            placeholder="janis"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            required
                        >
                    </div>

                    <div class="form-group w-full">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input
                            v-model="formData.email"
                            type="email"
                            name="email"
                            id="email"
                            placeholder="janis@gmail.com"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            required
                        >
                    </div>
                    <div class="form-group w-full">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input
                            v-model="formData.password"
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            required
                        >
                    </div>
                    <div class="form-group w-full">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                        <input
                            v-model="formData.password_confirmation"
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            placeholder="••••••••"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            required
                        >
                    </div>
                    <button
                        type="submit"
                        class="w-full text-center focus:outline-none text-white bg-purple-700 hover:bg-rounded-md focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    >
                        Create an account
                    </button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Already have an account?
                        <router-link :to="{ name: 'login'}" class="font-medium text-primary-600 hover:underline">
                            Login here
                        </router-link>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import axios from "axios";
    import { ref } from "vue";
    import { useRouter } from "vue-router";

    const router = useRouter();
    const error = ref(null);
    const showError = ref(true);

    interface FormData {
        name: string,
        email: string,
        password: string,
        password_confirmation: string
    }

    const formData = ref<FormData>({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
    });

    const register = async () => {
        try {
            if (formData.value.password !== formData.value.password_confirmation) {
                error.value = 'Passwords do not match';
                showError.value = true;

                setTimeout(() => {
                    showError.value = false;
                }, 2000);
            } else {
                const response = await axios.post('api/register/', formData.value);
                router.push({ name: 'login' });
            }
        } catch (e) {
            error.value = e.response.data.message || 'An error occurred.';

            showError.value = true;
            setTimeout(() => {
                showError.value = false;
            }, 2000);
        }
        formData.value = {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        };
    };
</script>

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
            TODO
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Sign in to your account
                </h1>

                <div v-if="error" :class="{ 'show': showError, 'hide': showError == false }" class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert" >
                    <span class="font-medium">{{ error }}</span>
                </div>

                <form @submit.prevent="login" class="space-y-4 md:space-y-6">
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
                    <button
                        type="submit"
                        class="w-full text-center focus:outline-none text-white bg-purple-700 hover:bg-rounded-md focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    >
                        Sign in
                    </button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Don’t have an account yet?
                        <router-link :to="{ name: 'register'}" class="font-medium text-primary-600 hover:underline">
                            Sign up
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
        email: string,
        password: string,
    }

    const formData = ref<FormData>({
        email: '',
        password: '',
    });

    const login = async () => {
        try {
            const response = await axios.post('api/login/', formData.value);
            localStorage.setItem('userToken', response.data.token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
            router.push({ name: 'home' });
        } catch (e) {
            error.value = e.response.data.message || 'An error occurred.';

            showError.value = true;
            setTimeout(() => {
                showError.value = false;
            }, 2000);
        }

        formData.value = {
            email: '',
            password: '',
        };
    };
</script>

<template>
    <header class="bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5 font-bold text-2xl">
                    Todo App
                </a>
            </div>
            <div class="flex flex-1 justify-end">
                <div v-if="userToken">
                    <button
                        type="button"
                        @click="userLogout"
                        class="text-sm font-semibold leading-6 text-gray-900"
                    >
                        Log out <span aria-hidden="true">&rarr;</span>
                    </button>
                </div>
                <div v-else>
                    <router-link :to="{ name: 'login'}" class="text-sm font-semibold leading-6 text-gray-900">
                        Log in <span aria-hidden="true">&rarr;</span>
                    </router-link>
                </div>
            </div>
        </nav>
    </header>

        <main class="mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
            <router-view></router-view>
        </main>
</template>
<script setup lang="ts">
    import axios from "axios";
    import {useRouter} from "vue-router";

    const router = useRouter();
    const userToken = localStorage.getItem('userToken')
    console.log(userToken)

    const userLogout = async () => {
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        try {
            const response = await axios.post('api/logout/').finally(() => {
                localStorage.removeItem('userToken');
                axios.defaults.headers.common['Authorization'] = 'Bearer';
                router.push({ name: 'home' });
            });
        } catch (error) {
            console.error('Logout error:', error);
        }
    };
</script>

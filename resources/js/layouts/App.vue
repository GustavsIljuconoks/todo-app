<template>
    <header class="bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5 font-bold text-2xl">
                    Todo App
                </a>
            </div>
            <div class="flex flex-1 justify-end">
                <router-link :to="{ name: 'login'}" class="text-sm font-semibold leading-6 text-gray-900">
                    Log in <span aria-hidden="true">&rarr;</span>
                </router-link>
            </div>
        </nav>
    </header>

        <main class="mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
            <router-view></router-view>
        </main>
</template>
<script setup>
</script>

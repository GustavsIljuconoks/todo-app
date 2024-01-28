<template>
    <div v-if="userToken" class="page-content font-medium">
        <div class="a">
            <form @submit.prevent="handleTaskUpdate" class="mt-4">
                <div class="w-full flex flex-row gap-3">
                    <div class="form-group m-3 w-full">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Todo Name</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        >
                    </div>
                    <div class="form-group m-3 w-full">
                        <label for="due_date">Todo due date</label>
                        <input
                            v-model="formData.due_date"
                            type="date"
                            id="due_date"
                            class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        >
                    </div>
                </div>

                <div class="form-group m-3 flex flex-col">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Todo Description</label>
                    <textarea
                        v-model="formData.description"
                        type="text"
                        id="description"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    ></textarea>
                </div>
                <div class="form-group m-3">
                    <button
                        type="submit"
                        class="text-end focus:outline-none text-white bg-purple-700 hover:bg-rounded-md focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    >Submit</button>
                </div>
            </form>
        </div>

        <div class="row mt-3">
            <div class="col-12 align-self-center shadow-lg p-4">
                <div v-for="task in tasks" :key="task.id">
                    <Task :task="task" @updateTasks="getTasks"/>
                </div>

                <div class="flex flex-row justify-between px-2 py-4">

                    <p>{{ countTasksActive }} items</p>

                    <div class="flex gap-4">
                        <a class="text-purple-700" href="#" @click="getTasks">Active</a>
                        <a href="#" @click="getCompletedTasks">Completed</a>
                        <a href="#" @click="getAll">All</a>
                    </div>

                    <div class="flex">
                        <a href="#">Clear completed</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="w-full h-[100vh] flex flex-col justify-center items-center">
        <p class="text-3xl font-bold">You are not authenticated!</p>
    </div>
</template>

<script setup lang="ts">
    import axios from "axios";
    import { onMounted, ref, computed } from "vue";
    import Task from "../components/Task.vue";

    let tasks = ref([]);
    const userToken = localStorage.getItem('userToken')

    interface FormData {
        name: string,
        description: string,
        due_date: string
    }

    const formData = ref<FormData>({
        name: '',
        description: '',
        due_date: ''
    });

    onMounted(async () => {
        await getTasks();
    });

    const countTasksActive = computed(() => {
        return (tasks.value.filter(task => task.completed === 0).length);
    });

    const getTasks = async () => {
        const userToken = localStorage.getItem('userToken');
        try {
            const response = await axios.post('http://127.0.0.1:8000/api/get-tasks', { userToken });
            tasks.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    };

    const getCompletedTasks = async () => {
        const userToken = localStorage.getItem('userToken');

        try {
            const response = await axios.post('http://127.0.0.1:8000/api/tasks-completed', { userToken });
            console.log(response.data);
            tasks.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    }

    const getAll = async () => {
        const userToken = localStorage.getItem('userToken');
        try {
            const response = await axios.post('http://127.0.0.1:8000/api/tasks-all', { userToken });
            tasks.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    }

    const handleTaskUpdate = async () => {
        try {
            const updatedFormData = {
                name: formData.value.name + ' (Updated)',
                description: formData.value.description,
                due_date: formData.value.due_date
            };


                const response = await axios.post('api/tasks/', {
                    name: formData.value.name,
                    description: formData.value.description,
                    due_date: formData.value.due_date,
                    userToken: localStorage.getItem('userToken')
                });
            await getTasks();

            // Reset form fields
            formData.value = {
                name: '',
                description: '',
                due_date: ''
            };

        } catch (error) {
            console.error('Error submitting form:', error);
        }
    };

</script>

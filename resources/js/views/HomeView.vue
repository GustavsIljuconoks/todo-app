<style scoped>
    .active-link {
        color: #7E22CEFF;
    }

    input[type="radio"] {
        accent-color: auto;
        display: block;
        width: 33px;
        height: 33px;
    }

    input.low {
        accent-color: #004080;
    }

    input.medium {
        accent-color: #f6aa0e;
    }

    input.urgent {
        accent-color: #ec6060;
    }
</style>

<template>
    <div v-if="userToken" class="page-content font-medium">
        <div class="m-3">
            <form @submit.prevent="handleTaskCreate" class="mt-4 flex flex-col gap-2">
                <div class="w-full flex flex-row gap-3">
                    <div class="form-group w-full">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Todo Name</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required
                        >
                    </div>
                    <div class="form-group w-full">
                        <label for="due_date">Todo due date</label>
                        <input
                            v-model="formData.due_date"
                            type="date"
                            id="due_date"
                            class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            required
                        >
                    </div>
                </div>

                <div class="form-group flex flex-col">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Todo Description</label>
                    <textarea
                        v-model="formData.description"
                        type="text"
                        id="description"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        required
                    ></textarea>
                </div>

                <div class="flex flex-col">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Todo priorities</label>

                    <div class="flex flex-row justify-between">
                        <label>
                            <input type="radio" name="priorities" class="low" value="3" v-model="formData.priorities_id"/>
                            Low
                        </label>

                        <label>
                            <input type="radio" name="priorities" class="medium" value="2" v-model="formData.priorities_id"/>
                            Medium
                        </label>

                        <label>
                            <input type="radio" name="priorities" class="urgent" value="1" v-model="formData.priorities_id"/>
                            Urgent
                        </label>
                    </div>
                </div>

                <div class="form-group m-3">
                    <button
                        type="submit"
                        class="text-end focus:outline-none text-white bg-purple-700 hover:bg-rounded-md focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    >Submit</button>
                </div>
            </form>
        </div>

        <div class="flex flex-col gap-20 justify-start sm:flex-row sm:justify-between">
            <search-input :tasks="tasks" @filterByName="updateFilteredByName"/>
            <sort-dropdown :tasks="tasks" @updateTasks="getTasks" @sort-data="updateSortedData"/>
        </div>

        <div class="row mt-3">
            <div class="col-12 align-self-center shadow-lg p-4">
                <div v-for="task in tasks" :key="task.task_id">
                    <Task :task="task" @updateTasks="getTasks"/>
                </div>

                <div class="flex flex-col md:flex-row justify-between px-2 py-4">

                    <p>{{ countTasksActive }} items</p>

                    <div class="flex gap-4 order-first md:order-none">
                        <a :class="{ 'active-link': activeLink === 'active'}" href="#" @click="getTasks">Active</a>
                        <a :class="{ 'active-link': activeLink === 'completed' }" href="#" @click="getCompletedTasks">Completed</a>
                        <a :class="{ 'active-link': activeLink === 'all' }" href="#" @click="getAll">All</a>
                    </div>

                    <div class="flex">
                        <a href="#" >Clear completed</a>
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
    import SortDropdown from "../components/SortDropdown.vue";
    import SearchInput from "../components/SearchInput.vue";
    import IFormData from "../globals";

    let tasks = ref([]);
    const userToken = localStorage.getItem('userToken')
    const activeLink = ref('');

    const formData = ref<IFormData>({
        name: '',
        description: '',
        due_date: '',
        priorities_id: ''
    });

    onMounted(async () => {
        await getTasks();
    });

    const countTasksActive = computed(() => {
        return (tasks.value.filter(task => task.completed === 0).length);
    });

    const getTasks = async () => {
        const userToken = localStorage.getItem('userToken');
        activeLink.value = 'active';

        try {
            const response = await axios.post('http://127.0.0.1:8000/api/get-tasks', { userToken });
            tasks.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    };

    const getCompletedTasks = async () => {
        const userToken = localStorage.getItem('userToken');
        activeLink.value = 'completed';

        try {
            const response = await axios.post('http://127.0.0.1:8000/api/tasks-completed', { userToken });
            tasks.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    }

    const getAll = async () => {
        const userToken = localStorage.getItem('userToken');
        activeLink.value = 'all';

        try {
            const response = await axios.post('http://127.0.0.1:8000/api/tasks-all', { userToken });
            tasks.value = response.data;
            console.log(tasks);
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    }

    const handleTaskCreate = async () => {
        try {
            console.log(formData.value.priorities_id)
            const response = await axios.post('api/tasks/', {
                name: formData.value.name,
                description: formData.value.description,
                due_date: formData.value.due_date,
                priorities_id: formData.value.priorities_id,
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


    const updateSortedData = (sortedData) => {
        tasks.value = sortedData
    }

    const updateFilteredByName = (filteredByName) => {
        tasks.value = filteredByName
    }
</script>

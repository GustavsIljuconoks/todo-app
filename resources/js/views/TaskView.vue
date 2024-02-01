<style scoped>
    .low {
        background-color: #004080;
    }

    .medium {
        background-color: #f6aa0e;
    }

    .urgent {
        background-color: #ec6060;
    }
</style>

<template>
    <div class="flex flex-col items-center justify-center gap-12 mt-5">
        <div class="">
            <h1 class="font-bold text-3xl">TODO DETAILS</h1>
        </div>

        <div class="block max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow flex flex-col gap-5">

            <div class="flex gap-10 justify-center">
                <h5 class="text-2xl font-bold tracking-tight">{{ task.name }}</h5>
                <h5 class="text-2xl font-bold tracking-tight">{{ task.due_date }}</h5>
            </div>

            <div class="flex flex-row justify-between items-center">
                <p class="font-normal text-xl text-gray-700">{{ task.description }}</p>

                <div class="flex flex-col justify-center">
                    <p class="font-bold">Priority</p>
                    <div :class="priority" class="w-5 h-5 rounded-full">
                    </div>
                </div>
            </div>


            <div class="flex flex-row justify-center gap-4">
                <button
                    @click="showForm = true"
                    class="text-end text-white bg-blue-500 hover:bg-rounded-md font-medium rounded-lg text-sm px-5 py-2.5"
                >
                    Edit
                </button>

                <form @submit.prevent="deleteTask(task.task_id)">
                    <button
                        type="submit"
                        class="text-end focus:outline-none text-white bg-red-500 hover:bg-rounded-md font-medium rounded-lg text-sm px-5 py-2.5"
                    >Delete</button>
                </form>
            </div>

            <div v-if="showForm" class="flex flex-col">
                <form @submit.prevent="updateTask(task.task_id)" class="m-3 flex flex-col gap-2">
                    <div class="w-full flex flex-row gap-3">
                        <div class="form-group w-full">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Todo Name</label>
                            <input
                                v-model="formData.name"
                                type="text"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            >
                        </div>
                        <div class="form-group w-full">
                            <label for="due_date">Todo due date</label>
                            <input
                                v-model="formData.due_date"
                                type="date"
                                id="due_date"
                                class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
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
        </div>
    </div>
</template>

<script setup lang="ts">

    import axios from "axios";
    import {computed, onMounted, ref} from "vue";
    import { useRouter } from 'vue-router'
    import IFormData from "../globals";
    const props = defineProps(['id']);

    const router = useRouter();
    let task = ref([]);
    const showForm = ref(false);

    const formData = ref<IFormData>({
        name: '',
        description: '',
        due_date: ''
    });

    const priority = computed(() => {
        return {
            urgent: task.priorities_id == 1,
            medium: task.priorities_id == 2,
            low: task.priorities_id == 3,
        };
    });

    const getTask = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/tasks/' + props.id);
            task.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    };

    onMounted(async () => {
        await getTask();
    });

    const deleteTask = async (id) => {
        try {
            const response = await axios.delete('http://127.0.0.1:8000/api/tasks/' + id);
            console.log(response.data);
            router.push({ name: 'home' });

        } catch (error) {
            console.error('Error deleting:', error);
        }
    };

    const updateTask = async (id) => {
        try {

            // Get those fields which are filled
            const requestData = {};
            for (const key in formData.value) {
                if (formData.value[key]) {
                    requestData[key] = formData.value[key];
                }
            }

            const response = await axios.put('http://127.0.0.1:8000/api/tasks/' + id, requestData);
            await getTask();

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

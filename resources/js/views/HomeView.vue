<template>
    <div class="page-content font-medium">
    <div class="a">

    <form @submit.prevent="handleTaskUpdate" class="mt-4">
        <div class="form-group m-3 flex flex-col">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Todo Name</label>
            <input
                v-model="formData.name"
                type="text"
                id="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            >
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
            <label for="due_date">Todo due date</label>
            <input
                v-model="formData.due_date"
                type="date"
                id="due_date"
                class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            >
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
            <v-app>
                <v-expansion-panels>
                    <v-expansion-panel
                        title="Title"
                        text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus! Eaque cupiditate minima"
                    >
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-app>
            <Task :task="task"/>
        </div>

        <div class="flex flex-row justify-between px-2 py-4">

            <p>{{ tasks.length }} items</p>

            <div class="flex gap-4">
                <a class="text-purple-700" href="#">All</a>
                <a href="#">Active</a>
                <a href="#">Completed</a>
            </div>

            <div class="flex">
                <a href="#">Clear completed</a>
            </div>
        </div>
    </div>
</div>
</div>
</template>

<script setup lang="ts">
    import axios from "axios";
    import { onMounted, ref } from "vue";
    import Task from "../components/Task.vue";

    let tasks = ref([]);

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

    const getTasks = async () => {
        try {
            const response = await axios.get('api/tasks');
            tasks.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    };

    onMounted(async () => {
        await getTasks();
    });


    const handleTaskUpdate = async () => {
        try {
            const response = await axios.post('api/tasks/',  formData.value);
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

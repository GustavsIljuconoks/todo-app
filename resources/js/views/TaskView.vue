<template>
    <div class="flex flex-col items-center justify-center gap-12 mt-5">
        <div class="">
            <h1 class="font-bold text-3xl">TODO DETAILS</h1>
        </div>

        <div class="block max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow flex flex-col gap-5">

            <div class="flex gap-10">
                <h5 class="text-2xl font-bold tracking-tight">{{ task.name }}</h5>
                <h5 class="text-2xl font-bold tracking-tight">{{ task.due_date }}</h5>
            </div>

            <p class="font-normal text-xl text-gray-700">{{ task.description }}</p>

            <div class="flex flex-row justify-center gap-4">
                <a
                    href="edit/"
                    class="text-end text-white bg-blue-500 hover:bg-rounded-md font-medium rounded-lg text-sm px-5 py-2.5"
                >
                    Edit
                </a>

                <form @submit.prevent="handleTaskDelete(task.task_id)">
                    <button
                        type="submit"
                        class="text-end focus:outline-none text-white bg-red-500 hover:bg-rounded-md font-medium rounded-lg text-sm px-5 py-2.5"
                    >Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

    import axios from "axios";
    import { onMounted, ref } from "vue";
    import { useRouter } from 'vue-router'
    const props = defineProps(['id']);

    const router = useRouter();
    let task = ref([]);

    const getTask = async () => {
        try {
            const response = await axios.get('http://127.0.0.1:8000/api/tasks/' + props.id);
            // console.log(response.data);
            task.value = response.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
        }
    };


    onMounted(async () => {
        await getTask();
    });

    const handleTaskDelete = async (id) => {
        try {
            const response = await axios.delete('http://127.0.0.1:8000/api/tasks/' + id);
            console.log(response.data);
            router.push({ name: 'home' });

        } catch (error) {
            console.error('Error deleting:', error);
        }
    };
</script>

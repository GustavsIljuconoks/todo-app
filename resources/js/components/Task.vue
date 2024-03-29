<style scoped>
    .done {
        text-decoration: line-through;
    }

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
    <Disclosure as="div" class="mt-2" v-slot="{ open }">
        <div class="w-full p-2 border-b-[1px] border-grayIsh">
            <div class="flex flex-row justify-between rounded-xl p-4 hover:bg-purple-200">
                <div class="flex flex-row gap-4">
                    <DisclosureButton type="submit">
                        <svg :class="open ? 'rotate-90 transform' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                        </svg>
                    </DisclosureButton>

                    <router-link :to="{ name: 'details', params: {id: task.task_id}, query: { task: task }}">
                        <p :class="taskCompleted">{{ task.name }}</p>
                    </router-link>
                </div>

                <div class="flex gap-4 items-center">

                    <div :class="priority" class="w-5 h-5 rounded-full">
                    </div>

                    <input type="checkbox" class="checkbox" @click="completeTask(task.task_id)" v-model="isChecked">

                    <form @submit.prevent="handleTaskDelete(task.task_id)" >
                        <button type="submit" class="delete">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <DisclosurePanel class="px-4 pb-2 pt-4 flex flex-col justify-between">
                <p>{{ task.description }}</p>
                <p>{{ task.due_date }}</p>
            </DisclosurePanel>
        </div>
    </Disclosure>
</template>

<script setup>
    import axios from "axios";
    import { ref, computed, reactive } from "vue";
    import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
    const props = defineProps(['task'])
    const emit = defineEmits(['updateTasks'])

    let isChecked = ref(false);
    const taskCompleted = computed(() => {
        return {
            'done': isChecked.value || props.task.completed == 1
        };
    });

    const priority = computed(() => {
        return {
            urgent: props.task.priorities_id == 1,
            medium: props.task.priorities_id == 2,
            low: props.task.priorities_id == 3,
        };
    });

    const handleTaskDelete = async (id) => {
        try {
            const response = await axios.delete('api/tasks/' + id);
            emit('updateTasks');
        } catch (error) {
            console.error('Error deleting:', error);
        }
    };

    const completeTask = async (id) => {
        try {
            const response = await axios.put('api/tasks/' + id, {
                completed: true
            });
        } catch (error) {
            console.error('Error checking:', error);
        }
    }
</script>

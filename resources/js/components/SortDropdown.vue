<template>
    <div class="relative text-right">
        <Menu as="div" class="inline-block text-left">
            <div>
                <MenuButton
                    class="inline-flex w-full justify-center rounded-md bg-purple-700 px-4 py-2 text-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                >
                    Options
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
                    </svg>
                </MenuButton>
            </div>

            <MenuItems
                class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
            >
                <div class="p-2" v-for="item in solutions" :key="item.name">
                    <MenuItem v-slot="{ active }">
                        <button
                            @click="item.action"
                            :class="[
                  active ? 'bg-purple-300 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md p-4 text-sm',
                ]"
                        >
                            {{ item.name }}
                        </button>
                    </MenuItem>
                </div>
            </MenuItems>
        </Menu>
    </div>
</template>

<script setup>
    import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
    const props = defineProps(['tasks'])
    const emit = defineEmits(['updateTasks'])


    const sortDataAscending = () => {
        const sortedData = [...props.tasks].sort((a, b) => {
            return new Date(a.due_date) - new Date(b.due_date);
        });

        sortedData.sort((a, b) => new Date(a.due_date) - new Date(b.due_date));
        emit('sort-data', sortedData);
    }

    const sortDataDescending = () => {
        const sortedData = [...props.tasks].sort((a, b) => {
            return new Date(b.due_date) - new Date(a.due_date);
        });

        emit('sort-data', sortedData);
    }


    const solutions = [
        { name: 'Sort ASC', action: sortDataAscending},
        { name: 'Sort DESC', action: sortDataDescending},
    ]
</script>

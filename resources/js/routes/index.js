import {createRouter, createWebHistory} from "vue-router";
import HomeView from "../views/HomeView.vue";
import TaskView from "../views/TaskView.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/details/:id?',
            name: 'details',
            component: TaskView,
            props: true
        },
    ]
})

export default router

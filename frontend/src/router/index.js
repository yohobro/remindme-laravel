import { createRouter, createWebHistory } from "vue-router";
import AppLayoutHome from '../layouts/AppLayoutHome.vue'
import AppLayoutDefault from '../layouts/AppLayoutDefault.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'AuthLogin',
            component: () => import('../components/Login.vue'),
            meta: {
                guest: true,
                layout: AppLayoutDefault,
            }
        },
        {
            path: '/reminders',
            name: 'ReminderIndex',
            component: () => import('../components/ReminderIndex.vue'),
            meta: {
                requiresAuth: true,
                layout: AppLayoutHome
            }
        },
        {
            path: '/reminders/add',
            name: 'ReminderAdd',
            component: () => import('../components/ReminderAdd.vue'),
            meta: {
                requiresAuth: true,
                layout: AppLayoutHome
            }
        },
        {
            path: '/reminders/edit/:id',
            name: 'ReminderEdit',
            component: () => import('../components/ReminderEdit.vue'),
            props: true,
            meta: {
                requiresAuth: true,
                layout: AppLayoutHome
            }
        },
        {
            path: '/logout',
            name: 'AuthLogout',
            beforeEnter (to, from, next) {
                localStorage.clear();
                next({ name: 'AuthLogin' })
            }
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('access_token') == null) {
            next({ name: 'AuthLogin' });
        } else {
            // let user = JSON.parse(localStorage.getItem('user'));
            next();
        }
    } else {
        next();
    }
});

export default router;
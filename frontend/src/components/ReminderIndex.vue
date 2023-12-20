<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import moment from "moment";

axios.defaults.baseURL = "http://localhost:8000/api/";
axios.defaults.withCredentials = true;
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('access_token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        Promise.reject(error);
    }
);

const reminders = ref([]);

const getReminders = async () => {
    const response = await axios.get("reminders/limit/5");
    reminders.value = response.data.data.reminders;
}

const destroy = async (id) => {
    if (!window.confirm("are you sure?")) {
        return;
    }
    await axios.delete("reminders/" + id);
    await getReminders();
}

onMounted(() => getReminders());

</script>

<template>
    <div class="mt-0">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Remind At
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Event At
                        </th>
                        <th scope="col" class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reminder in reminders" :key="reminder.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">
                            {{ reminder.title }}
                        </td>
                        <td class="px-6 py-4">
                            {{ reminder.description }}
                        </td>
                        <td class="px-6 py-4">
                            {{ moment(new Date(reminder.remind_at*1000)).format('YYYY-MM-DD hh:mm:ss') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ moment(new Date(reminder.event_at*1000)).format('YYYY-MM-DD hh:mm:ss') }}
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <RouterLink :to="{name: 'ReminderEdit', params: {id: reminder.id}}" class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded">Edit</RouterLink>
                            <button @click="destroy(reminder.id)" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>
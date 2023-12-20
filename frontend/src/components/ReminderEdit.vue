<script setup>
import { ref, onMounted } from 'vue';
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css';
import moment from "moment";
import router from '@/router';

axios.defaults.baseURL = "http://localhost:8000/api/";
axios.defaults.withCredentials = true;
axios.interceptors.request.use(
    (config) => {
        config.headers['Accept'] = 'application/json';
        config.headers['Content-Type'] = 'application/json';
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

let reminder = ref();
const errors = ref({});

const update = async (data) => {
    try {
        const formData = new FormData();
        let unixRemindAt = parseInt(new Date(reminder.value.remind_at).getTime() / 1000).toFixed(0);
        let unixEventAt = parseInt(new Date(reminder.value.event_at).getTime() / 1000).toFixed(0);
        formData.append('title', reminder.value.title);
        formData.append('description', reminder.value.description);
        formData.append('remind_at', unixRemindAt);
        formData.append('event_at', unixEventAt);
        var object = {};
        formData.forEach((value, key) => object[key] = value);
        var json = JSON.stringify(object);
        await axios.put("reminders/" + data, json);
        await router.push({name: "ReminderIndex"});
    } catch (error) {
        if (error.code == "ERR_BAD_REQUEST") {
            errors.value = error.response.data.errors;
        }
    }
}

const getReminder = async (id) => {
    const response = await axios.get("reminders/" + id);
    reminder.value = response.data.data;
    reminder.value.remind_at = new Date(response.data.data.remind_at * 1000);
    reminder.value.event_at = new Date(response.data.data.event_at * 1000);
}

const props = defineProps({
    id: {
        required: true,
        type: String
    }
});

onMounted(() => getReminder(props.id));

</script>
<template>
    <div class="mt-12">
        <form class="max-w-md mx-auto p-4 bg-white shadow-md rounded-md" @submit.prevent="update($route.params.id)" method="post" v-if="reminder">
            <div class="space-y-6">
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="text" id="name" v-model="reminder.title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <div v-if="errors.title">
                        <span class="text-sm text-red-400">{{ errors.title[0] }}</span>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" v-model="reminder.description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
                    <div v-if="errors.description">
                        <span class="text-sm text-red-400">{{ errors.description[0] }}</span>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="remind_at" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Remind At</label>
                    <!-- <input type="text" id="remind_at" v-model="formatRemindAt" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"> -->
                    <VueDatePicker v-model="reminder.remind_at" name="remind_at" id="remind_at"></VueDatePicker>
                    <div v-if="errors.remind_at">
                        <span class="text-sm text-red-400">{{ errors.remind_at[0] }}</span>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="event_at" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event At</label>
                    <VueDatePicker v-model="reminder.event_at" name="event_at" id="remind_at"></VueDatePicker>
                    <div v-if="errors.event_at">
                        <span class="text-sm text-red-400">{{ errors.event_at[0] }}</span>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 rounded text-white">Update</button>
                </div>
            </div>
        </form>
    </div>
</template>
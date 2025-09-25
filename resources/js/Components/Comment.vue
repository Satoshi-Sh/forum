<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" class="h-10 w-10 rounded-full"/>
        </div>
        <div class="flex-1">
            <div class="mt-1 prose prose-sm max-w-none" v-html="comment.html"/>
            <span class="first-letter:uppercase block pt-1 text-xs text-gray-600">By {{
                    comment.user.name
                }} {{ relativeDate(comment.created_at) }} ago | <span class="text-pink-500">{{ comment.likes_count }} likes</span></span>

            <div class="mt-3 flex justify-end space-x-3 empty:hidden">
                <form @submit.prevent="$emit('edit', comment.id)" v-if="comment.can?.update">
                    <button class="font-mono hover:font-semibold text-xs">Edit</button>
                </form>
                <form @submit.prevent="$emit('delete', comment.id)" v-if="comment.can?.delete">
                    <button class="font-mono hover:font-semibold text-red-700 text-xs">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import {relativeDate} from "@/utils/date.js";

const props = defineProps(['comment']);
const emit = defineEmits(['delete', 'edit']);

</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="mt-1 text-sm text-gray-600">{{ formattedDate }} by {{ post.user.name }}</span>
            <article class="m-6">
                <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
            </article>
            <div class="mt-12">
                <h2 class="font-bold text-lg">Comments</h2>
                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment :comment="comment"/>
                    </li>
                </ul>
                <Pagination :meta="comments.meta"/>
            </div>
        </Container>

    </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import {computed} from "vue";
import {relativeDate} from "@/utils/date.js"
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import Comment from "@/Components/Comment.vue";

const props = defineProps(['post', 'comments'])
const formattedDate = computed(() => relativeDate(props.post.created_at));
</script>

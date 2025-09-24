<template>
    <AppLayout>
        <Container>
            <div>
                <Link :href="route('posts.index')" v-if="selectedTopic"
                      class="text-indigo-500 hover:text-indigo-600 block mb-2 ">
                    Back to all posts
                </Link>
                <page-heading v-text="selectedTopic ? selectedTopic.name: 'All Posts'"></page-heading>
                <p v-if="selectedTopic" class="mt-1 text-sm text-gray-600">
                    {{ selectedTopic.description }}
                </p>
            </div>
            <ul class="divide-y mt-4">
                <li v-for="post in posts.data" :key="post.id"
                    class="flex justify-between items-baseline flex-col md:flex-row">
                    <Link :href="post.routes.show" class="block group px-2 py-4">
                        <span class="font-bold text-lg group-hover:text-indigo-500">{{ post.title }}</span>
                        <span class="block mt-1 text-sm text-gray-600">{{ relativeDate(post.created_at) }} by {{
                                post.user.name
                            }}</span>
                    </Link>
                    <Link :href="route('posts.index',{ topic: post.topic.slug })"
                          class=" mb-2 rounded-full py-0.5 px-2 border border-pink-500 text-pink-500 hover:bg-indigo-500 hover:text-indigo-600">
                        {{ post.topic.name }}
                    </Link>
                </li>
            </ul>

            <Pagination :meta="posts.meta" class="mt-2" :only="['posts']"/>
        </Container>
    </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import {Link} from "@inertiajs/vue3";
import {relativeDate} from "@/utils/date.js"
import PageHeading from "@/Components/PageHeading.vue";

defineProps(['posts', 'selectedTopic']);


</script>

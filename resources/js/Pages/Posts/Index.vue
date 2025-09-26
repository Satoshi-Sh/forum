<template>
    <AppLayout>
        <Container>
            <div>
                <page-heading v-text="selectedTopic ? selectedTopic.name: 'All Posts'"></page-heading>
                <p v-if="selectedTopic" class="mt-3 text-sm text-gray-600">
                    {{ selectedTopic.description }}
                </p>
                <menu class="mt-4 flex space-x-1 overflow-x-auto pt-1 pb-2">
                    <li>
                        <Pill :href="route('posts.index', {query: searchForm.query})" :filled="selectedTopic===null">All
                            Posts
                        </Pill>
                    </li>
                    <li v-for="topic in topics" :key="topic.id">
                        <Pill
                            :href="route('posts.index',{ topic: topic.slug,query: searchForm.query })"
                            :filled="selectedTopic?.id===topic.id"
                        >{{ topic.name }}
                        </Pill>
                    </li>
                </menu>
                <form @submit.prevent="search" class="mt-4">
                    <div>
                        <InputLabel for="query">Search</InputLabel>
                        <div class="flex mt-1 space-x-2">
                            <TextInput v-model="searchForm.query" class="w-full" id="query"></TextInput>
                            <SecondaryButton type="submit">Search</SecondaryButton>
                            <DangerButton v-if="searchForm.query" @click="clearSearch" type="reset">Clear</DangerButton>
                        </div>

                    </div>
                </form>
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
                    <Pill :href="route('posts.index',{ topic: post.topic.slug })">{{ post.topic.name }}</Pill>
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
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {relativeDate} from "@/utils/date.js"
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps(['posts', 'query', 'topics', 'selectedTopic']);

const searchForm = useForm({
    query: props.query,
    page: 1,
})

const page = usePage();
const search = () => {
    searchForm.get(page.url);
}

const clearSearch = () => {
    searchForm.query = '';
    search();
}

</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{ post.title }}</h1>
            <span class="mt-1 text-sm text-gray-600">{{ formattedData }} by {{ post.user.name }}</span>
            <article class="m-6">
                <pre class="whitespace-pre-wrap font-sans">{{ post.body }}</pre>
            </article>
        </Container>

    </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import {computed} from "vue";
import {formatDistance, parseISO} from "date-fns";
import Container from "@/Components/Container.vue";

const props = defineProps(['post'])
const formattedData = computed(() => formatDistance(parseISO(props.post.created_at), new Date(), {addSuffix: true}));
</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <PageHeading>{{ post.title }}</PageHeading>
            <span class="mt-1 text-sm text-gray-600">{{ formattedDate }} by {{ post.user.name }}</span>
            <article class="m-6 prose prose-sm max-w-none" v-html="post.html"/>
            <div class="mt-12">
                <h2 class="font-bold text-lg">Comments</h2>
                <form v-if="$page.props.auth.user"
                      @submit.prevent="() => commentIdBeingEdited ? updateComment() : addComment()" class="mt-4">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkdownEditor ref="commentTextAreaRef" id="body" v-model="commentForm.body"
                                        placeholder="Speak your mind spock" editorClass="min-h-[160px]"/>
                        <InputError :message="commentForm.errors.body" class="mt-3"/>
                    </div>
                    <PrimaryButton
                        type="submit"
                        :disabled="commentForm.processing" class="mt-3"
                        v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'"
                    />
                    <SecondaryButton class="ml-2" v-if="commentIdBeingEdited" @click="cancelEditComment">Cancel
                    </SecondaryButton>

                </form>
                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                        <Comment @edit="editComment" @delete="deleteComment" :comment="comment"/>
                    </li>
                </ul>
                <Pagination :meta="comments.meta" :only="['comments']"/>
            </div>
        </Container>

    </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import {computed, ref} from "vue";
import {relativeDate} from "@/utils/date.js"
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/utils/Composables/useConfirm.js"
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import PageHeading from "@/Components/PageHeading.vue";

const props = defineProps(['post', 'comments'])
const formattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: '',
})
const addComment = () => commentForm.post(route('posts.comments.store', props.post.id), {
    preserveScroll: true,
    onSuccess: () => commentForm.reset(),
})

const updateComment = async () => {
    if (!await confirmation("Are you sure to want to update the comment")) {
        commentTextAreaRef.value?.focus();
        return;
    }

    commentForm.put(route('comments.update', {
            comment: commentIdBeingEdited.value,
            body: commentForm.body,
            page: props.comments.meta.current_page
        }),
        {
            preserveScroll: true,
            onSuccess: cancelEditComment
        })
}

const commentTextAreaRef = ref(null);

const commentIdBeingEdited = ref(null);

const commentBeingEdit = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value))

const {confirmation} = useConfirm();

const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdit.value?.body;
    commentTextAreaRef.value.focus();
};

const cancelEditComment = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
}


const deleteComment = async (commentId) => {

    if (!await confirmation("Are you sure to want to delete the comment")) {
        return;
    }
    router.delete(route('comments.destroy', {
            comment: commentId,
            page: props.comments.meta.current_page
        }),
        {preserveScroll: true}
    );
}


</script>

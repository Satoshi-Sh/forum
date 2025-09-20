<template>
    <div
        v-if="editor"
        class="bg-white rounded-md border-0 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        <menu class="flex divide-x border-b">
            <li>
                <button @click="()=> editor.chain().focus().toggleBold().run()" type="button" title="Bold"
                        class="px-3 py-2 rounded-tl-md"
                        :class="editor.isActive('bold') ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-bold"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleItalic().run()" type="button" title="Italic"
                        class="px-3 py-2"
                        :class="editor.isActive('italic') ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-italic"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleStrike().run()" type="button" title="Strikethrough"
                        class="px-3 py-2"
                        :class="editor.isActive('strike') ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-strikethrough"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleBlockquote().run()" type="button" title="Blockquote"
                        class="px-3 py-2"
                        :class="editor.isActive('blockquote') ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-double-quotes-l"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleBulletList().run()" type="button" title="Bullet List"
                        class="px-3 py-2"
                        :class="editor.isActive('bulletList') ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-list-unordered"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleOrderedList().run()" type="button"
                        title="Ordered List"
                        class="px-3 py-2"
                        :class="editor.isActive('orderedList') ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-list-ordered"/>
                </button>
            </li>
            <li>
                <button @click="promptUserForHref" type="button"
                        title="Add link"
                        class="px-3 py-2"
                        :class="editor.isActive('link') ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-link"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleHeading({level:2}).run()" type="button"
                        title="Heading 1"
                        class="px-3 py-2"
                        :class="editor.isActive('heading', {level:2}) ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-h-1"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleHeading({level:3}).run()" type="button"
                        title="Heading 2"
                        class="px-3 py-2"
                        :class="editor.isActive('heading', {level:3}) ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-h-2"/>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleHeading({level:4}).run()" type="button"
                        title="Heading 3"
                        class="px-3 py-2"
                        :class="editor.isActive('heading', {level:4}) ? 'bg-indigo-500 text-white':
                'hover:bg-gray-200'"
                >
                    <i class="ri-h-3"/>
                </button>
            </li>
        </menu>
        <EditorContent :editor="editor"></EditorContent>
    </div>
</template>


<script setup>

import {EditorContent, useEditor} from "@tiptap/vue-3";
import {StarterKit} from "@tiptap/starter-kit";
import {Link} from "@tiptap/extension-link"
import {watch} from "vue";
import {Markdown} from "tiptap-markdown";
import 'remixicon/fonts/remixicon.css'

const props = defineProps({
    modelValue: '',
})

const emit = defineEmits(["update:modelValue"])

const editor = useEditor({
    extensions: [
        StarterKit.configure(
            {
                heading: {
                    levels: [2, 3, 4],
                },
                code: false,
                codeBlock: false,
            }),
        Link,
        Markdown
    ],
    editorProps: {
        attributes: {
            class: ' min-h-[512px] prose prose-sm max-width-none py-1.5 px-3',
        },
    },
    onUpdate: () => emit('update:modelValue', editor.value?.storage.markdown.getMarkdown()),
});

watch(() => props.modelValue, (value) => {
    if (value === editor.value?.storage.markdown.getMarkdown()) {
        return;
    }
    editor.value?.commands.setContent(value);
}, {immediate: true})


const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        return editor.value?.chain().unsetLink().run()
    }

    const href = prompt("Where do you want to link to?");

    if (!href) {
        return editor.value?.chain().focus().run();
    }
    return editor.value?.chain().focus().setLink({href}).run();
}
</script>

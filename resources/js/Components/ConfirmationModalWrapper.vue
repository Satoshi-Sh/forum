<template>
    <ConfirmationModal :show="state.show">
        <template #title>
            {{ state.title }}
        </template>
        <template #content>
            {{ state.message }}
        </template>
        <template #footer>
            <SecondaryButton ref="cancelButtonRef" @click="cancel">Cancel</SecondaryButton>
            <PrimaryButton @click="confirm" class="ml-2"> Confirm</PrimaryButton>
        </template>
    </ConfirmationModal>
</template>
<script setup lang="ts">
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/utils/Composables/useConfirm.js"
import {nextTick, ref, watchEffect} from "vue";

const {state, confirm, cancel} = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async () => {
    if (state.show) {
        await nextTick();
        cancelButtonRef.value?.$el.focus();
    }
})
</script>

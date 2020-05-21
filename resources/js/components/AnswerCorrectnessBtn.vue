<template>
    <button
        class="rounded-sm shadow-sm text-white font-bold focus:outline-none text-2xl"
        :class="[
            height ? height : 'h-70',
            width ? width : 'w-full',
            color ? color : 'bg-blue-300'
        ]"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "AnswerCorrectnessBtn",
    props: {
        height: {
            type: String
        },
        width: {
            type: String
        },
        color: {
            type: String
        }
    }
};
</script>

<style></style>

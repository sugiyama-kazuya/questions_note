<template>
    <button
        class="rounded-sm shadow-sm text-white font-bold focus:outline-none"
        :class="[
            height ? height : 'h-70',
            width ? width : 'w-full',
            color ? color : 'bg-blue-400'
        ]"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "DefaultBtn",
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

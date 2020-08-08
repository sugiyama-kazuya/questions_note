<template>
    <button
        :class="[color ? color : 'bg-red-400', width ? width : 'w-full']"
        class="text-white font-bold py-2 px-4 rounded focus:outline-none"
    >
        {{ text }}
    </button>
</template>

<script>
export default {
    name: "CenterModalBtn",
    props: {
        text: {
            type: String,
            required: true
        },
        color: {
            type: String
        },
        width: {
            type: String
        }
    }
};
</script>

<style></style>

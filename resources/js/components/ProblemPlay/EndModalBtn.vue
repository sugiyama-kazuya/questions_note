<template>
    <button
        class="text-white font-bold py-2 px-4 rounded focus:outline-none"
        :class="color ? color : 'bg-red-400'"
    >
        {{ text }}
    </button>
</template>

<script>
export default {
    name: "EndModalBtn",
    props: {
        text: {
            type: String,
            required: true
        },
        color: {
            type: String
        }
    }
};
</script>

<style></style>

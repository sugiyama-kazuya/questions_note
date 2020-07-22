<template>
    <div class="flex justify-center items-center w-100 mb-3">
        <div
            class="flex items-center w-4/5 rounded-md shadow-md border bg-white"
            :class="padding ? padding : ''"
        >
            <div class="flex justify-start items-center w-1/2">
                <slot name="left-contents"></slot>
            </div>
            <div class="flex justify-end items-center w-1/2">
                <slot name="right-contents"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "BaseRecord",
    props: {
        padding: {
            type: String
        }
    }
};
</script>

<style></style>

<template>
    <div class="flex justify-center items-center w-100 mb-3">
        <div
            class="flex justify-between items-center w-4/5 rounded-md shadow-md border bg-white"
            :class="padding ? padding : ''"
        >
            <div class="flex items-center">
                <slot name="left-contents"></slot>
            </div>
            <slot name="right-contents"></slot>
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

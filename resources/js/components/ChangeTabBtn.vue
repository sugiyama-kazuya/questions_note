<template>
    <div :class="width ? width : 'w-4/5'" class="inline-flex st-border">
        <button
            @click="$emit('left-click')"
            :class="{ 'is-tab-active': isLeftActive }"
            class="w-1/2 bg-white text-indigo-500 py-2 px-4 focus:outline-none"
        >
            <slot name="leftBtnText" />
        </button>
        <button
            @click="$emit('right-click')"
            :class="{ 'is-tab-active': isRightActive }"
            class="w-1/2 bg-white py-2 px-4 text-indigo-500 focus:outline-none"
        >
            <slot name="rightBtnText" />
        </button>
    </div>
</template>

<script>
export default {
    name: "ChangeTabBtn",

    props: {
        isLeftActive: {
            type: Boolean
        },
        isRightActive: {
            type: Boolean
        },
        width: {
            type: String
        }
    }
};
</script>

<style scoped>
.is-tab-active {
    background-color: #667eea !important;
    color: #fff !important;
}
.st-border {
    border: 1px solid #667eea;
}
</style>

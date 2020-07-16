<template>
    <div class="inline-flex st-border" :class="width ? width : 'w-4/5'">
        <button
            @click="$emit('left-click')"
            class="w-1/2 bg-white text-indigo-500 py-2 px-4 focus:outline-none"
            :class="{ 'is-tab-active': isLeftActive }"
        >
            <slot name="leftBtnText" />
        </button>
        <button
            @click="$emit('right-click')"
            class="w-1/2 bg-white py-2 px-4 text-indigo-500 focus:outline-none"
            :class="{ 'is-tab-active': isRightActive }"
        >
            <slot name="rightBtnText" />
        </button>
    </div>
</template>

<script>
export default {
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

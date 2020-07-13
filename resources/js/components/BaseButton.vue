<template>
    <button
        @click="$emit('click-btn')"
        :class="[
            height ? height : '',
            width ? width : '',
            color ? color : 'bg-blue-400',
            text ? text : 'text-white',
            borderColor ? 'border-blue' : ''
        ]"
        :type="type ? type : 'button'"
        class="p-2 rounded-sm shadow-sm font-bold focus:outline-none outline-none"
    >
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "BaseButton",
    props: {
        height: {
            type: String
        },
        width: {
            type: String
        },
        color: {
            type: String
        },
        text: {
            type: String
        },
        type: {
            type: String
        },
        borderColor: {
            type: Boolean
        }
    }
};
</script>

<style scoped>
.border-blue {
    border: 1px solid #63b3ed;
}
</style>

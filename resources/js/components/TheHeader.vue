<template>
    <div class="h-5rem">
        <header :class="bgColor ? bgColor : 'bg-indigo-400'" class="flex h-100">
            <div class="w-1/3 flex justify-start items-center pl-3">
                <slot name="leftSide"></slot>
            </div>
            <div
                class="w-1/3 text-center text-white flex items-center justify-center"
            >
                <slot name="titleName"></slot>
            </div>
            <div class="w-1/3 flex justify-end items-center pr-3">
                <slot name="rightSide"></slot>
            </div>
        </header>
        <slot name="createBtn"></slot>
    </div>
</template>

<script>
export default {
    name: "TheHeader",
    props: {
        bgColor: {
            type: String
        }
    }
};
</script>

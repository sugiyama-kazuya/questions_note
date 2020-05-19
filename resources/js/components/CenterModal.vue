<template>
    <div class="modal-mask relative">
        <div class="bg-white rounded w-2/3 m-0 m-auto shadow modal-view">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "CenterModal"
};
</script>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9999;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-view {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>

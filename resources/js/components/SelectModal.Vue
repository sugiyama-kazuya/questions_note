<template>
    <div class="absolute inset-x-0 bottom-0 z-10 bg-gray-100 h-30">
        <div
            class="flex justify-between items-center p-3 bg-blue-500 st-select_title"
        >
            <div class="w-1/3"></div>
            <h5 class="text-white text-center mb-0 w-1/3">
                <slot name="title"></slot>
            </h5>
            <div class="pr-3 text-white w-1/3 text-right">
                <font-awesome-icon
                    @click="closeSelectModal"
                    icon="times"
                    class="text-2xl"
                />
            </div>
        </div>
        <div
            class="flex flex-col items-center text-center overflow-y-scroll st-select_body"
        >
            <slot name="add-list"></slot>
            <li
                v-for="item in items"
                :key="item.id"
                @click="select(item.name)"
                class="list-none w-full h-2.5rem leading-10 hover:bg-gray-300"
            >
                {{ item.name }}
            </li>
        </div>
    </div>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faTimes } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faTimes);

export default {
    name: "SelectModal",
    components: {
        FontAwesomeIcon
    },
    props: {
        items: {
            type: Array,
            required: true
        }
    },
    methods: {
        select(item) {
            this.$emit("selected", item);
            this.closeSelectModal();
        },
        closeSelectModal() {
            this.$emit("close", false);
        }
    }
};
</script>

<style>
.st-select_title {
    height: 15%;
}

.st-select_body {
    height: 85%;
}
</style>

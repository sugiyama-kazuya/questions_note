<template>
    <div class="h-20 bg-white mx-3 rounded-sm shadow-sm px-2 flex flex-column ">
        <div class="flex flex-column">
            <div class="text-right p-2">カテゴリ</div>
            <div class="px-2 pb-4 pt-2">元気とは？</div>
        </div>
        <div class="border-t p-1 flex items-center justify-between text-gray-600">
            <div class="flex items-center"><font-awesome-icon icon="user-circle" class="text-2xl mr-2"></font-awesome-icon>たけし</div>
            <div>○ 3</div>
            <div>2020/05/18</div>
        </div>
    </div>

</template>

<script>
    import { library } from "@fortawesome/fontawesome-svg-core";
    import {
        faUserCircle,
    } from "@fortawesome/free-solid-svg-icons";
    import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
    library.add(faUserCircle);

    export default {
        name: "ProblemCard",
        components: {
            FontAwesomeIcon
        }
    }
</script>

<style scoped>

</style>

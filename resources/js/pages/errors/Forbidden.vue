<template>
    <div
        class="flex flex-col justify-center items-center h-screen w-screen p-4"
    >
        <div>お探しのページは表示できませんでした。</div>
        <BaseButton @click-btn="goIndex()" class="m-4 p-3"
            ><template>問題集の一覧へ</template></BaseButton
        >
    </div>
</template>

<script>
import BaseButton from "../../components/BaseButton";
export default {
    name: "forbidden",
    components: { BaseButton },
    methods: {
        goIndex() {
            this.$router.push("/exercise-books");
        }
    }
};
</script>

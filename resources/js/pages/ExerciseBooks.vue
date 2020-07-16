<template>
    <div class="relative h-100">
        <div class="h-90 bg-gray-200">
            <TheHeader class="h-8">
                <template v-slot:titleName>
                    <h5>HOME</h5>
                </template>
            </TheHeader>

            <div class="flex justify-center items-center h-10 px-3 round-sm">
                <ChangeTabBtn
                    @left-click="newArrivalsOrder"
                    @right-click="popularOrder"
                    :isLeftActive="displayTab.isNewActive"
                    :isRightActive="displayTab.isPopularActive"
                >
                    <template v-slot:leftBtnText>新着</template>
                    <template v-slot:rightBtnText>人気順</template>
                </ChangeTabBtn>
            </div>

            <main class="scroll-y h-82 py-2">
                <ExerciseBookCard
                    v-for="cardData in ExerciseBookCardData"
                    :key="cardData.id"
                    :cardData="cardData"
                    class="mb-4"
                ></ExerciseBookCard>
            </main>
        </div>
        <TheFooter />
        <TheLoading if="isLoading" :loading="isLoading" />
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import ExerciseBookCard from "../components/ExerciseBookCard";
import TheLoading from "../components/TheLoading";
import ChangeTabBtn from "../components/ChangeTabBtn";

import { OK, INTERNAL_SERVER_ERROR } from "../util";

export default {
    name: "ExerciseBooks",
    components: {
        TheHeader,
        TheFooter,
        ExerciseBookCard,
        TheLoading,
        ChangeTabBtn
    },
    data() {
        return {
            ExerciseBookCardData: {},
            isLoading: false,
            displayTab: {
                isNewActive: false,
                isPopularActive: false
            }
        };
    },
    mounted() {
        this.newArrivalsOrder();
    },
    methods: {
        async newArrivalsOrder() {
            this.isLoading = true;
            this.displayTab.isPopularActive = false;
            this.displayTab.isNewActive = true;

            this.isLoading = true;
            const response = await axios
                .get("api/exercise-books")
                .catch(error => error.response || error);

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
                return;
            }

            if (response.status === OK) {
                this.ExerciseBookCardData = response.data.exercise_books;
                this.displayTab.isNewActive = true;
                this.isLoading = false;
            }
        },
        async popularOrder() {
            this.displayTab.isNewActive = false;
            this.displayTab.isPopularActive = true;
            this.isLoading = true;
            const response = await axios
                .get("/api/favorites/asc")
                .catch(error => error.response || error);

            this.ExerciseBookCardData = response.data.exercise_books;
            this.isLoading = false;
        }
    }
};
</script>

<style scoped>
.scroll-y {
    overflow-y: scroll;
    transform: translateZ(0);
    box-sizing: border-box;
}
</style>

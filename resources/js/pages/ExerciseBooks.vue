<template>
    <div class="relative min-h-screen flex flex-col">
        <TheHeader class="h-5rem">
            <template v-slot:titleName>
                <h5>INDEX</h5>
            </template>
        </TheHeader>
        <div class="bg-gray-200 text-gray-600 flex-1">
            <div
                class="flex flex-col items-center justify-center bg-gray-200 p-3 round-sm sticky top-0 z-50"
            >
                <BaseSearchBox
                    v-model="searchBoxKeyword"
                    @search="filterExerciseBooks()"
                    :placeholder="exerciseBooks.placeholder"
                    :bg-color="'bg-white'"
                    class="mb-3"
                />

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

            <main class="relative">
                <NoSearchResults v-if="noSearchResults" />
                <ExerciseBookCard
                    v-for="exerciseBook in exerciseBooks.data"
                    :key="exerciseBook.id"
                    :cardData="exerciseBook"
                    class="mb-4"
                ></ExerciseBookCard>
            </main>
        </div>
        <TheLoading
            :loading="isLoading"
            :background-color="'#edf2f7'"
            class="h-screen"
        />
        <TheFooter />
    </div>
</template>

<script>
import Common from "../commonMixin";
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import ExerciseBookCard from "../components/ExerciseBookCard";
import TheLoading from "../components/TheLoading";
import ChangeTabBtn from "../components/ChangeTabBtn";
import BaseSearchBox from "../components/BaseSearchBox";
import NoSearchResults from "../components/NoSearchResults";

import { OK, INTERNAL_SERVER_ERROR } from "../util";

export default {
    name: "ExerciseBooks",
    components: {
        TheHeader,
        TheFooter,
        ExerciseBookCard,
        TheLoading,
        ChangeTabBtn,
        BaseSearchBox,
        NoSearchResults
    },

    mixins: [Common],

    data() {
        return {
            exerciseBooks: {
                placeholder: "問題集の名前を入力",
                data: []
            },
            isLoading: false,
            displayTab: {
                isNewActive: false,
                isPopularActive: false
            },
            searchBoxKeyword: "",
            noSearchResults: false
        };
    },

    created() {
        this.newArrivalsOrder();
    },

    methods: {
        async newArrivalsOrder() {
            this.scrollTop();
            this.isLoading = true;
            await this.getNewArrivalsOrderExerciseBooks();
            this.displayTab.isPopularActive = false;
            this.displayTab.isNewActive = true;
            this.searchBoxReset();
            this.isLoading = false;
        },

        async getNewArrivalsOrderExerciseBooks() {
            const response = await axios
                .get("/api/exercise-books")
                .catch(error => error.response || error);

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
                return;
            }

            if (response.status === OK) {
                this.exerciseBooks.data = response.data.exercise_books;
                return;
            }
        },

        async popularOrder() {
            this.scrollTop();
            this.isLoading = true;
            this.displayTab.isNewActive = false;
            this.displayTab.isPopularActive = true;
            this.searchBoxReset();
            await this.getPopularOrderExerciseBooks();
            this.isLoading = false;
        },

        async getPopularOrderExerciseBooks() {
            const response = await axios
                .get("/api/favorites/asc")
                .catch(error => error.response || error);

            this.exerciseBooks.data = response.data.exercise_books;
        },

        async filterExerciseBooks() {
            this.scrollTop();
            this.isLoading = true;
            if (this.displayTab.isNewActive) {
                await this.getNewArrivalsOrderExerciseBooks();
            }
            if (this.displayTab.isPopularActive) {
                await this.getPopularOrderExerciseBooks();
            }

            const obj = this;
            const currentExerciseBooks = obj.exerciseBooks.data;
            const filterExerciseBooks = currentExerciseBooks.filter(function(
                exerciseBook
            ) {
                return exerciseBook.name.includes(obj.searchBoxKeyword);
            });
            if (filterExerciseBooks.length === 0) {
                this.noSearchResults = true;
            } else {
                this.noSearchResults = false;
            }
            this.exerciseBooks.data = filterExerciseBooks;
            this.isLoading = false;
        },

        searchBoxReset() {
            this.searchBoxKeyword = "";
            this.noSearchResults = false;
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

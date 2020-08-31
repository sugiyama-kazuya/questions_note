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
                    :is-left-active="
                        displayTab.isNewActive || displayTab.isNewArrivalSearch
                    "
                    :is-right-active="
                        displayTab.isPopularActive || displayTab.isPopularSearch
                    "
                >
                    <template v-slot:leftBtnText>新着</template>
                    <template v-slot:rightBtnText>人気順</template>
                </ChangeTabBtn>
            </div>

            <main class="relative">
                <template
                    v-if="
                        displayTab.isNewActive || displayTab.isNewArrivalSearch
                    "
                >
                    <ExerciseBookCard
                        v-for="exerciseBook in exerciseBooks.data"
                        :key="exerciseBook.id"
                        :card-data="exerciseBook"
                        class="mb-4"
                    />
                </template>
                <template
                    v-if="
                        displayTab.isPopularActive || displayTab.isPopularSearch
                    "
                >
                    <ExerciseBookCard
                        v-for="exerciseBook in exerciseBooks.data"
                        :key="exerciseBook.id"
                        :card-data="exerciseBook"
                        class="mb-4"
                    />
                </template>
                <infinite-loading
                    @infinite="infiniteHandler"
                    :identifier="infiniteId"
                    :spinner="'spiral'"
                    class="pb-4"
                >
                    <template v-slot:no-more
                        ><span class="text-sm text-gray-500"
                            >これ以上問題はありません</span
                        ></template
                    >
                    <template v-slot:no-results
                        ><span class="text-sm text-gray-600"
                            >検索結果に該当する問題集はありません</span
                        ></template
                    ></infinite-loading
                >
            </main>
        </div>
        <TheFooter />
        <TouchNotPossible v-if="isOperating" />
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import ExerciseBookCard from "../components/ExerciseBookCard";
import ChangeTabBtn from "../components/ChangeTabBtn";
import BaseSearchBox from "../components/BaseSearchBox";
import NoSearchResults from "../components/NoSearchResults";
import TouchNotPossible from "../components/TouchNotPossible";
import InfiniteLoading from "vue-infinite-loading";
import { OK } from "../util";
import Common from "../commonMixin";

export default {
    name: "ExerciseBooks",
    components: {
        TheHeader,
        TheFooter,
        ExerciseBookCard,
        ChangeTabBtn,
        BaseSearchBox,
        NoSearchResults,
        InfiniteLoading,
        TouchNotPossible
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
                isNewArrivalSearch: false,
                isPopularActive: false,
                isPopularSearch: false
            },
            searchBoxKeyword: "",
            page: 1,
            infiniteId: new Date(),
            url: "",
            isOperating: false
        };
    },

    mounted() {
        this.displayTab.isPopularActive = false;
        this.displayTab.isNewActive = true;
    },

    methods: {
        newArrivalsOrder() {
            this.$_scrollTop();
            this.displayTab.isNewActive = true;
            this.displayTab.isPopularActive = false;
            this.displayTab.isPopularSearch = false;
            this.changeType();
            this.searchBoxReset();
        },

        popularOrder() {
            this.$_scrollTop();
            this.displayTab.isPopularActive = true;
            this.displayTab.isNewActive = false;
            this.displayTab.isNewArrivalSearch = false;
            this.changeType();
            this.searchBoxReset();
        },

        async getNewArrivalsOrderExerciseBooks($_state) {
            if (this.displayTab.isNewActive) {
                this.url = `/api/exercise-books?page=${this.page}`;
            }
            if (this.displayTab.isNewArrivalSearch) {
                this.url = `/api/exercise-books?search=${this.searchBoxKeyword}&page=${this.page}`;
            }
            const response = await axios
                .get(this.url)
                .catch(error => error.response || error);

            if (response.status === OK) {
                if (response.data.exercise_books.length) {
                    this.page += 1;
                    this.exerciseBooks.data = this.exerciseBooks.data.concat(
                        response.data.exercise_books
                    );
                    $_state.loaded();
                    return;
                } else {
                    $_state.complete();
                    return;
                }
            }

            this.$_internalServerError(response.status);
        },

        async getPopularOrderExerciseBooks($_state) {
            if (this.displayTab.isPopularActive) {
                // 人気順API
                this.url = `/api/favorites/asc?page=${this.page}`;
            }
            if (this.displayTab.isPopularSearch) {
                // 人気順検索API
                this.url = `/api/favorites/asc?search=${this.searchBoxKeyword}&page=${this.page}`;
            }
            const response = await axios
                .get(this.url)
                .catch(error => error.response || error);

            if (response.status === OK) {
                if (response.data.exercise_books.length) {
                    this.page++;
                    this.exerciseBooks.data = this.exerciseBooks.data.concat(
                        response.data.exercise_books
                    );
                    $_state.loaded();
                    return;
                } else {
                    $_state.complete();
                    return;
                }
            }

            this.$_internalServerError(response.status);
        },

        async filterExerciseBooks() {
            this.isOperating = true;
            this.$_scrollTop();
            if (
                this.displayTab.isNewActive ||
                this.displayTab.isNewArrivalSearch
            ) {
                this.displayTab.isNewActive = false;
                this.displayTab.isNewArrivalSearch = true;
                this.changeType();
                this.isOperating = false;
                return;
            }
            if (
                this.displayTab.isPopularActive ||
                this.displayTab.isPopularSearch
            ) {
                this.displayTab.isPopularActive = false;
                this.displayTab.isPopularSearch = true;
                this.changeType();
                this.isOperating = false;
                return;
            }
        },

        searchBoxReset() {
            this.searchBoxKeyword = "";
        },

        async infiniteHandler($state) {
            if (
                this.displayTab.isNewActive ||
                this.displayTab.isNewArrivalSearch
            ) {
                this.isOperating = true;
                // 新着順検索
                await this.getNewArrivalsOrderExerciseBooks($state);
                this.isOperating = false;

                return;
            }

            if (
                this.displayTab.isPopularActive ||
                this.displayTab.isPopularSearch
            ) {
                this.isOperating = true;
                // 人気順検索
                await this.getPopularOrderExerciseBooks($state);
                this.isOperating = false;
                return;
            }
        },

        changeType() {
            this.page = 1;
            this.exerciseBooks.data = [];
            this.infiniteId += 1;
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

<template>
    <div class="relative h-100">
        <div class="h-90">
            <TheHeader class="h-8 bg-gray-200">
                <template v-slot:titleName>
                    <h5 class="m-0">List</h5>
                </template>
            </TheHeader>
            <div class="flex flex-col h-92 text-gray-600">
                <div class="flex border-b h-5">
                    <div
                        @click="exerciseBookShow()"
                        class="w-1/2 flex justify-center items-center p-2"
                        :class="exerciseBooks.isShow ? 'active-tab' : ''"
                    >
                        問題集
                    </div>
                    <div
                        @click="userShow()"
                        class="w-1/2 flex justify-center items-center p-2"
                        :class="user.isShow ? 'active-tab' : ''"
                    >
                        ユーザー
                    </div>
                </div>
                <main class="w-100 h-95">
                    <div class="relative h-100 w-100 overflow-y-scroll">
                        <!-- 問題集一覧 -->
                        <transition name="main">
                            <div
                                v-if="exerciseBooks.isShow"
                                class="flex flex-col items-center w-screen h-100"
                            >
                                <BaseSearchBox
                                    :placeholder="exerciseBooks.placeholder"
                                    class="mt-4"
                                ></BaseSearchBox>

                                <div
                                    v-if="
                                        exerciseBooks.isShow &&
                                            !exerciseBooks.data.length
                                    "
                                    class="w-4/5"
                                >
                                    作成された問題集はございません。<br />下記のcreateボタンより問題を作成してください。
                                </div>

                                <BaseRecord
                                    v-for="exerciseBook in exerciseBooks.data"
                                    :key="exerciseBook.id"
                                    :padding="'p-3'"
                                >
                                    <template slot="left-contents">
                                        <span>{{ exerciseBook.name }}</span>
                                    </template>
                                    <template slot="right-contents">
                                        <FontAwesomeIcon
                                            @click="
                                                openProblemsListModal(
                                                    exerciseBook.id
                                                )
                                            "
                                            icon="edit"
                                            class="text-2xl text-gray-600"
                                        ></FontAwesomeIcon>
                                    </template>
                                </BaseRecord>
                            </div>
                        </transition>
                        <!-- ユーザ一覧 -->
                        <transition name="sav">
                            <div
                                v-if="user.isShow"
                                class="flex flex-col items-center h-100 w-100"
                            >
                                <BaseSearchBox
                                    :placeholder="user.placeholder"
                                    class="mt-4"
                                ></BaseSearchBox>
                                <ChangeTabBtn
                                    @left-click="followSelected()"
                                    @right-click="followerSelected()"
                                    :isLeftActive="user.isFollowTab"
                                    :isRightActive="user.isFollowerTab"
                                    :width="'w-4/5'"
                                    class="my-4"
                                >
                                    <template v-slot:leftBtnText
                                        >フォロー</template
                                    >
                                    <template v-slot:rightBtnText
                                        >フォロワー</template
                                    >
                                </ChangeTabBtn>
                                <BaseRecord :padding="'p-2'">
                                    <template slot="left-contents">
                                        <font-awesome-icon
                                            icon="user-circle"
                                            class="text-3xl mr-3"
                                        />
                                        <span>kazuya</span>
                                    </template>
                                    <template slot="right-contents">
                                        <BaseBtn :color="'bg-blue-300'">
                                            <template>フォロー中</template>
                                        </BaseBtn>
                                    </template>
                                </BaseRecord>
                            </div>
                        </transition>
                        <TheLoading
                            if="loading.isLoading"
                            :loading="loading.isLoading"
                        />
                    </div>
                </main>
            </div>
        </div>
        <THeFooter />

        <!-- 問題一覧モーダル -->
        <transition name="center-modal">
            <CenterModal
                v-if="isProblemsListModal"
                :backColor="true"
                :width="'w-4/5'"
                :height="'h-60'"
            >
                <div class="flex items-center h-10 bg-blue-300 text-white">
                    <div class="flex justify-between items-center px-2 w-5/6">
                        <span class="px-2">問題一覧</span>
                        <BaseBtn
                            @click-btn="goDeletedValidation()"
                            :color="'bg-red-400'"
                            >削除</BaseBtn
                        >
                    </div>
                    <div class="flex justify-center items-center w-1/6">
                        <FontAwesomeIcon
                            @click="closeProblemsListModal()"
                            icon="times"
                            class="text-3xl"
                        ></FontAwesomeIcon>
                    </div>
                </div>
                <div class="h-90 py-3 overflow-y-scroll">
                    <BaseRecord
                        v-for="problem in targetProblems"
                        :key="problem.id"
                        :padding="'p-3'"
                    >
                        <template slot="left-contents">
                            <span>{{ problem.content }}</span>
                        </template>
                        <template slot="right-contents">
                            <BaseBtn @click-btn="goProblemEdit(problem.id)">
                                <template>編集</template>
                            </BaseBtn>
                        </template>
                    </BaseRecord>
                </div>
            </CenterModal>
        </transition>

        <!-- 問題集削除確認モーダル -->
        <transition name="center-modal">
            <CenterModal v-if="isExerciseBooksDeletedModal">
                <div class="p-3 bg-gray-100">
                    <span
                        >この問題集を削除します。問題集の中の問題は全て削除されますがよろしいですか？</span
                    >
                    <div class="flex justify-end">
                        <BaseBtn @click-btn="closeDeletedValidation()">
                            <template>戻る</template>
                        </BaseBtn>
                        <BaseBtn
                            @click-btn="deleteExerciseBooks()"
                            :color="'bg-red-400'"
                        >
                            <template>削除</template>
                        </BaseBtn>
                    </div>
                </div>
            </CenterModal>
        </transition>
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import THeFooter from "../components/TheFooter";
import TheLoading from "../components/TheLoading";
import CenterModal from "../components/CenterModal";
import CenterModalBtn from "../components/CenterModalBtn";
import ChangeTabBtn from "../components/ChangeTabBtn";
import BaseSearchBox from "../components/BaseSearchBox";
import BaseBtn from "../components/BaseButton";
import BaseRecord from "../components/BaseRecord";

import { library } from "@fortawesome/fontawesome-svg-core";
import { faEdit, faTimes } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faEdit, faTimes);

import { OK, INTERNAL_SERVER_ERROR } from "../util";

export default {
    name: "Search",
    components: {
        FontAwesomeIcon,
        TheHeader,
        THeFooter,
        TheLoading,
        CenterModal,
        CenterModalBtn,
        BaseSearchBox,
        BaseBtn,
        BaseRecord,
        ChangeTabBtn
    },

    data() {
        return {
            exerciseBooks: {
                isShow: true,
                placeholder: "問題集の名前を入力",
                data: [],
                selecetdId: 0
            },
            targetProblems: [],
            user: {
                isShow: false,
                placeholder: "ユーザー名を入力",
                isFollowTab: true,
                isFollowerTab: false,
                follows: {},
                followers: {}
            },
            isProblemsListModal: false,
            loading: {
                isLoading: false,
                fullPage: false
            },
            isExerciseBooksDeletedModal: false
        };
    },

    computed: {},

    mounted() {
        this.getOwnExerciseBooksAndProblems();
    },

    methods: {
        exerciseBookShow() {
            this.user.isShow = false;
            this.exerciseBooks.isShow = true;
        },
        userShow() {
            this.exerciseBooks.isShow = false;
            this.user.isShow = true;
        },
        openProblemsListModal(exerciseBooksId) {
            this.exerciseBooks.selecetdId = exerciseBooksId;
            this.setProblem(exerciseBooksId);
            this.isProblemsListModal = true;
        },
        closeProblemsListModal() {
            this.isProblemsListModal = false;
        },
        goProblemEdit(problemId) {
            this.$router.push({
                name: "ProblemEdit",
                params: { id: problemId }
            });
        },
        followSelected() {
            this.user.isFollowTab = true;
            this.user.isFollowerTab = false;
        },
        followerSelected() {
            this.user.isFollowTab = false;
            this.user.isFollowerTab = true;
        },
        goDeletedValidation() {
            this.isExerciseBooksDeletedModal = true;
        },
        closeDeletedValidation() {
            this.isExerciseBooksDeletedModal = false;
        },
        async deleteExerciseBooks() {
            const response = await axios
                .delete(`/api/exercise-books/${this.exerciseBooks.selecetdId}`)
                .catch(error => response.error || error);

            if (response.status === OK) {
                console.log("削除しました");
                this.getOwnExerciseBooksAndProblems();
                this.isExerciseBooksDeletedModal = false;
                this.isProblemsListModal = false;
                return;
            }
        },
        async getOwnExerciseBooksAndProblems() {
            this.loading.isLoading = true;

            const response = await axios
                .get("/api/own/exercise-books/problems")
                .catch(error => response.error || error);

            if (response.status === OK) {
                this.exerciseBooks.data = response.data;
                this.loading.isLoading = false;
                return;
            }
        },
        setProblem(exerciseBooksId) {
            const targetExerciseBook = this.exerciseBooks.data.filter(function(
                exerciseBook
            ) {
                return Number(exerciseBook.id) === Number(exerciseBooksId);
            });
            this.targetProblems = targetExerciseBook[0].problem;
        }
    }
};
</script>

<style scoped>
.active-tab {
    border-bottom: solid 2px #667eea;
    color: #667eea;
    transition: border-bottom 0.2s linear;
}

.main-enter-active,
.main-leave-active,
.sav-enter-active,
.sav-leave-active {
    transition: 0.15s linear;
    position: absolute;
}

.main-enter,
.main-leave-to {
    transform: translateX(-100%);
}
.main-enter-to,
.sav-enter-to {
    transform: translateX(0);
}
.main-leave,
.sav-leave {
    transform: translateX(0);
}
.sav-enter,
.sav-leave-to {
    transform: translateX(100%);
}

.center-modal-enter-active,
.center-modal-leave-active {
    transition: opacity 0.2s;
}

.center-modal-enter,
.center-modal-leave-to {
    opacity: 0;
}
</style>

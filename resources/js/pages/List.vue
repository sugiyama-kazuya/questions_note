<template>
    <div class="relative min-h-screen flex flex-col">
        <TheHeader>
            <template v-slot:titleName>
                <h5 class="m-0">List</h5>
            </template>
        </TheHeader>

        <div class="flex flex-col text-gray-600 flex-1">
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
            <main class="h-100 w-100 ">
                <div class="relative h-100 w-100">
                    <!-- 問題集一覧 -->
                    <transition name="exercisebook">
                        <div
                            v-if="exerciseBooks.isShow"
                            class="flex flex-col items-center w-screen h-100"
                        >
                            <div class="sticky top-0 z-50 bg-white">
                                <BaseSearchBox
                                    @search="filterExerciseBooks"
                                    v-model="searchBoxKeyword"
                                    :placeholder="exerciseBooks.placeholder"
                                    class="py-4"
                                />
                            </div>
                            <div v-if="exerciseBooks.emptyFlg" class="w-4/5">
                                作成された問題集はございません。<br />下記のcreateボタンより問題を作成してください。
                            </div>
                            <NoSearchResults v-if="noSearchResults" />
                            <div class="scroll-y w-screen">
                                <BaseRecord
                                    v-for="exerciseBook in exerciseBooks.data"
                                    :key="exerciseBook.id"
                                    :padding="'p-3'"
                                >
                                    <template slot="left-contents">
                                        <span>{{
                                            exerciseBookLengthAdjustment(
                                                exerciseBook.name
                                            )
                                        }}</span>
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
                        </div>
                    </transition>
                    <!-- ユーザ一覧 -->
                    <transition name="user">
                        <div
                            v-if="user.isShow"
                            class="flex flex-col items-center h-100 w-100"
                        >
                            <div
                                class="flex items-center justify-center py-4 w-screen"
                            >
                                <ChangeTabBtn
                                    @left-click="followSelected()"
                                    @right-click="followerSelected()"
                                    :isLeftActive="user.isFollowTab"
                                    :isRightActive="user.isFollowerTab"
                                    :width="'w-4/5'"
                                >
                                    <template v-slot:leftBtnText
                                        >フォロー</template
                                    >
                                    <template v-slot:rightBtnText
                                        >フォロワー</template
                                    >
                                </ChangeTabBtn>
                            </div>
                            <template v-if="user.isFollowerTab">
                                <BaseRecord
                                    v-for="user in user.followers"
                                    :key="user.id"
                                    :padding="'p-2'"
                                >
                                    <template slot="left-contents">
                                        <font-awesome-icon
                                            icon="user-circle"
                                            class="text-3xl mr-3"
                                        />
                                        <span @click="goProfile(user.id)">{{
                                            user.name
                                        }}</span>
                                    </template>
                                </BaseRecord>
                                <div v-if="user.followersEmptyFlg" class="my-4">
                                    <p>
                                        まだフォローされていません。
                                    </p>
                                </div>
                            </template>

                            <template v-if="user.isFollowTab">
                                <div class="scroll-y w-screen h-92">
                                    <BaseRecord
                                        v-for="user in user.follows"
                                        :key="user.id"
                                        :padding="'p-2'"
                                    >
                                        <template slot="left-contents">
                                            <font-awesome-icon
                                                icon="user-circle"
                                                class="text-3xl mr-3"
                                            />
                                            <span @click="goProfile(user.id)">{{
                                                user.name
                                            }}</span>
                                        </template>
                                        <template slot="right-contents">
                                            <BaseBtn
                                                @click-btn="isFollow(user)"
                                                :color="
                                                    followBtnBgColor(
                                                        user.is_followed_by
                                                    )
                                                "
                                                :text="
                                                    followBtnTextColor(
                                                        user.is_followed_by
                                                    )
                                                "
                                                :border-color="
                                                    followBtnBorderColor(
                                                        user.is_followed_by
                                                    )
                                                "
                                            >
                                                <template>{{
                                                    followBtnText(
                                                        user.is_followed_by
                                                    )
                                                }}</template>
                                            </BaseBtn>
                                        </template>
                                    </BaseRecord>
                                </div>
                                <div v-if="user.followsEmptyFlg" class="my-4">
                                    <p>
                                        まだフォローしていません。
                                    </p>
                                </div>
                            </template>
                        </div>
                    </transition>
                </div>
            </main>
        </div>
        <TheLoading
            v-if="loading.isLoading"
            :loading="loading.isLoading"
            class="h-screen"
        />
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
                    <div class="flex justify-between items-center p-2 w-5/6">
                        <span class="px-2">問題一覧</span>
                        <BaseBtn
                            @click-btn="openDeletedValidation()"
                            :color="'bg-red-400'"
                            >問題集の削除</BaseBtn
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
                    <div v-if="isTargetProblems">
                        <BaseRecord
                            v-for="problem in targetProblems"
                            :key="problem.id"
                            :padding="'p-3'"
                        >
                            <template slot="left-contents">
                                <span>{{
                                    problemLengthAdjustment(problem.content)
                                }}</span>
                            </template>
                            <template slot="right-contents">
                                <BaseBtn
                                    @click-btn="goProblemEdit(problem.id)"
                                    class="mr-2"
                                >
                                    <template>編集</template>
                                </BaseBtn>
                                <BaseBtn
                                    @click-btn="
                                        openProblemDeletedValidation(problem.id)
                                    "
                                    :color="'bg-red-400'"
                                >
                                    <template>削除</template>
                                </BaseBtn>
                            </template>
                        </BaseRecord>
                    </div>
                    <div
                        v-if="!isTargetProblems"
                        class="text-center text-gray-600 mt-4"
                    >
                        <p>作成された問題はございません。</p>
                    </div>
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
                            class="ml-2"
                        >
                            <template>削除</template>
                        </BaseBtn>
                    </div>
                </div>
            </CenterModal>
        </transition>

        <!-- 問題削除確認モーダル -->
        <transition name="center-modal">
            <CenterModal v-if="isProblemDeletedModal">
                <div class="p-3 bg-gray-100">
                    <div class="py-2">
                        この問題を削除します。よろしいですか？
                    </div>
                    <div class="flex justify-end p-2">
                        <BaseBtn @click-btn="closeProblemDeletedValidation()">
                            <template>戻る</template>
                        </BaseBtn>
                        <BaseBtn
                            @click-btn="deleteProblem()"
                            :color="'bg-red-400'"
                            class="ml-2"
                        >
                            <template>削除</template>
                        </BaseBtn>
                    </div>
                </div>
            </CenterModal>
        </transition>

        <!-- フラッシュメッセージ -->
        <transition name="center-modal">
            <CenterModal v-if="isFlashMsg">
                <div class="p-2 bg-gray-800 text-white rounded-md">
                    {{ flashMsg.text }}
                </div>
            </CenterModal>
        </transition>
    </div>
</template>

<script>
import Common from "../commonMixin";
import TheHeader from "../components/TheHeader";
import THeFooter from "../components/TheFooter";
import TheLoading from "../components/TheLoading";
import CenterModal from "../components/CenterModal";
import CenterModalBtn from "../components/CenterModalBtn";
import ChangeTabBtn from "../components/ChangeTabBtn";
import BaseSearchBox from "../components/BaseSearchBox";
import BaseBtn from "../components/BaseButton";
import BaseRecord from "../components/BaseRecord";
import NoSearchResults from "../components/NoSearchResults";

import { library } from "@fortawesome/fontawesome-svg-core";
import { faEdit, faTimes } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faEdit, faTimes);

import { OK, INTERNAL_SERVER_ERROR } from "../util";

export default {
    name: "List",
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
        ChangeTabBtn,
        NoSearchResults
    },

    mixins: [Common],

    data() {
        return {
            exerciseBooks: {
                isShow: true,
                placeholder: "問題集の名前を入力",
                data: [],
                selecetdId: 0,
                emptyFlg: false
            },
            targetProblems: [],
            problemSelectedId: 0,
            user: {
                isShow: false,
                placeholder: "ユーザー名を入力",
                isFollowTab: true,
                isFollowerTab: false,
                follows: [],
                followsEmptyFlg: false,
                followers: [],
                followersEmptyFlg: false,
                isFollowedBy: true
            },
            isProblemsListModal: false,
            loading: {
                isLoading: false,
                fullPage: false,
                opacity: 0
            },
            isExerciseBooksDeletedModal: false,
            isProblemDeletedModal: false,
            searchBoxKeyword: "",
            noSearchResults: false,
            flashMsg: {
                text: "問題集を削除しました",
                speed: 2000
            }
        };
    },

    computed: {
        loginUserId() {
            return this.$store.state.auth.user.id;
        },

        isFlashMsg() {
            return this.$store.state.flashMessage.visible;
        },

        isTargetProblems() {
            return this.targetProblems.length ? true : false;
        },

        problemLengthAdjustment: function() {
            return function(name) {
                return name.length > 8 ? name.substring(0, 8) + "..." : name;
            };
        },

        exerciseBookLengthAdjustment: function() {
            return function(name) {
                return name.length >= 12 ? name.substring(0, 12) + "..." : name;
            };
        }
    },

    async created() {
        this.scrollTop();
        this.loading.isLoading = true;
        await this.getOwnExerciseBooksAndProblems();
        await this.followSelected();
        this.loading.isLoading = false;
    },

    methods: {
        async filterExerciseBooks() {
            this.scrollTop();
            this.loading.isLoading = true;
            await this.getOwnExerciseBooksAndProblems();
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
            this.loading.isLoading = false;
        },

        searchBoxReset() {
            this.searchBoxKeyword = "";
            this.noSearchResults = false;
        },

        async exerciseBookShow() {
            this.scrollTop();
            this.user.isShow = false;
            this.exerciseBooks.isShow = true;
            this.loading.isLoading = true;
            await this.getOwnExerciseBooksAndProblems();
            this.loading.isLoading = false;
        },

        async userShow() {
            this.exerciseBooks.isShow = false;
            this.user.isShow = true;
            this.searchBoxReset();
            this.loading.isLoading = true;
            await this.followSelected();
            this.loading.isLoading = false;
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

        async followSelected() {
            this.scrollTop();
            this.loading.isLoading = true;
            this.user.isFollowTab = true;
            this.user.isFollowerTab = false;

            const response = await axios
                .get(`/api/users/${this.loginUserId}/follows`)
                .catch(error => error.response || error);

            if (response.status === OK) {
                if (response.data.length) {
                    this.user.followsEmptyFlg = false;
                    this.user.follows = response.data;
                } else {
                    this.user.follows = [];
                    this.user.followsEmptyFlg = true;
                }
                this.loading.isLoading = false;
                return;
            }
        },

        async followerSelected() {
            this.scrollTop();
            this.loading.isLoading = true;
            this.user.isFollowTab = false;
            this.user.isFollowerTab = true;

            const response = await axios
                .get(`/api/users/${this.loginUserId}/followers`)
                .catch(error => error.response || error);

            if (response.status === OK) {
                if (response.data.length) {
                    this.user.followersEmptyFlg = false;
                    this.user.followers = response.data;
                } else {
                    this.user.followers = [];
                    this.user.followersEmptyFlg = true;
                }
                this.loading.isLoading = false;
                return;
            }
        },

        async isFollow(user) {
            const url = `/api/users/${user.id}/follow`;
            if (user.is_followed_by === true) {
                user.is_followed_by = false;
                const response = await axios
                    .delete(url)
                    .catch(error => error.response || error);

                if (response.status === INTERNAL_SERVER_ERROR) {
                    this.$router.push("/500");
                    return;
                }
            } else {
                user.is_followed_by = true;
                const response = await axios
                    .put(url)
                    .catch(error => error.response || error);

                if (response.status === INTERNAL_SERVER_ERROR) {
                    this.$router.push("/500");
                    return;
                }
            }
        },

        openDeletedValidation() {
            this.isExerciseBooksDeletedModal = true;
        },

        closeDeletedValidation() {
            this.isExerciseBooksDeletedModal = false;
        },

        openProblemDeletedValidation(problemId) {
            this.isProblemDeletedModal = true;
            this.problemSelectedId = problemId;
        },

        closeProblemDeletedValidation() {
            this.isProblemDeletedModal = false;
            this.problemSelectedId = 0;
        },

        async deleteExerciseBooks() {
            const response = await axios
                .delete(`/api/exercise-books/${this.exerciseBooks.selecetdId}`)
                .catch(error => response.error || error);

            if (response.status === OK) {
                this.loading.isLoading = true;
                await this.getOwnExerciseBooksAndProblems();
                this.loading.isLoading = false;
                this.isExerciseBooksDeletedModal = false;
                this.isProblemsListModal = false;
                await this.$store.dispatch("flashMessage/showFlashMsg", true);
                await this.$store.dispatch(
                    "flashMessage/hideFlashMsg",
                    this.flashMsg.speed
                );
                return;
            }

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
                return;
            }
        },

        async deleteProblem() {
            const response = await axios
                .delete(`/api/problems/${this.problemSelectedId}`)
                .catch(error => response.error);

            if (response.status === OK) {
                this.closeProblemDeletedValidation();
                this.loading.isLoading = true;
                await this.getOwnExerciseBooksAndProblems();
                await this.setProblem(this.exerciseBooks.selecetdId);
                this.loading.isLoading = false;
                return;
            }

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
                return;
            }
        },

        async getOwnExerciseBooksAndProblems() {
            const response = await axios
                .get("/api/own/exercise-books/problems")
                .catch(error => response.error || error);

            if (response.status === OK) {
                if (response.data.length) {
                    this.exerciseBooks.emptyFlg = false;
                    this.exerciseBooks.data = response.data;
                    return;
                } else {
                    this.exerciseBooks.emptyFlg = true;
                    return;
                }
            }
        },

        setProblem(exerciseBooksId) {
            const targetExerciseBook = this.exerciseBooks.data.filter(function(
                exerciseBook
            ) {
                return Number(exerciseBook.id) === Number(exerciseBooksId);
            });
            this.targetProblems = targetExerciseBook[0].problem;
        },

        goProfile(userId) {
            this.$router.push(`/users/${userId}`);
        },

        followBtnBgColor(isFollow) {
            return isFollow ? "bg-blue-300" : "bg-white";
        },

        followBtnTextColor(isFollow) {
            return isFollow ? "text-white" : "text-blue-300";
        },
        followBtnBorderColor(isFollow) {
            return isFollow ? false : true;
        },
        followBtnText(isFollow) {
            return isFollow ? "フォロー中" : "フォローする";
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

.exercisebook-enter-active,
.exercisebook-leave-active,
.user-enter-active,
.user-leave-active {
    transition: 0.15s linear;
    position: absolute;
}

.exercisebook-enter,
.exercisebook-leave-to {
    transform: translateX(-100%);
}
.exercisebook-enter-to,
.user-enter-to {
    transform: translateX(0);
}
.exercisebook-leave,
.user-leave {
    transform: translateX(0);
}
.user-enter,
.user-leave-to {
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

.scroll-y {
    overflow-y: scroll;
    transform: translateZ(0);
    box-sizing: border-box;
}
</style>

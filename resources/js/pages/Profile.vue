<template>
    <div class="relative min-h-screen flex flex-col bg-gray-200">
        <TheHeader>
            <template v-slot:titleName>
                <h5>Mypage</h5>
            </template>
        </TheHeader>
        <main class="relative h-100 flex-1">
            <div class="flex bg-white shadow-xl w-screen h-12rem py-3 px-4 ">
                <div
                    class="flex flex-col items-center justify-center w-1/2 h-100 "
                >
                    <div class="text-center pb-2">
                        <span>{{ user.name }}</span>
                    </div>
                    <div class="flex justify-center w-100">
                        <div class="w-50 relative img-circle">
                            <FontAwesomeIcon
                                v-if="isIconProfileImg"
                                icon="user-circle"
                                class="m-0 h-100 w-100 absolute inset-0 img-profile mr-2"
                            />

                            <img
                                v-if="user.profile_img"
                                class="m-0 h-100 bg-cover absolute inset-0 img-profile"
                                :src="user.profile_img"
                                alt="プロフィール画像"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="w-1/2 h-100 flex flex-wrap content-between pr-3 py-2 text-right"
                >
                    <div class="w-full flex justify-end">
                        <BaseButton
                            v-if="!isLoginUser"
                            @click-btn="isFollow"
                            :text="followBtnTextColor"
                            :color="followBtnBgColor"
                            :height="'h-3rem'"
                            :width="'w-2/3'"
                            :border-color="true"
                        >
                            <template>{{ followBtnText }}</template>
                        </BaseButton>
                        <BaseButton
                            v-if="isLoginUser"
                            @click-btn="goEditScreen"
                            :text="editBtn.btnTextColor"
                            :color="editBtn.btnBgColor"
                            :height="'h-3rem'"
                            :width="'w-1/3'"
                            :border-color="true"
                        >
                            <template>{{ editBtn.btnText }}</template>
                        </BaseButton>
                    </div>
                    <div class="flex flex-col w-100 justify-end">
                        <span class="mb-2 text-right"
                            >フォロー {{ followingsCount }}</span
                        >
                        <span class="text-right"
                            >フォロワー {{ followersCount }}</span
                        >
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col justify-center items-center sticky top-0 bg-gray-200 p-4 round-sm"
            >
                <BaseSearchBox
                    v-model="searchBoxKeyword"
                    @search="filterExerciseBooks"
                    :placeholder="exerciseBooks.placeholder"
                    :bg-color="'bg-white'"
                    class="mb-3"
                />

                <ChangeTabBtn
                    v-if="isLoginUser"
                    @left-click="ownExerciseBooks()"
                    @right-click="favoriteOrder()"
                    :is-left-active="
                        displayTab.isOwnExerciseBooks ||
                            displayTab.isOwnExerciseBooksSearch
                    "
                    :is-right-active="
                        displayTab.isFavoriteOrder ||
                            displayTab.isFavoriteOrderSearch
                    "
                >
                    <template v-slot:leftBtnText>問題</template>
                    <template v-slot:rightBtnText>お気に入り</template>
                </ChangeTabBtn>
                <div v-else class="w-1/3">
                    <div class="bg-indigo-500 py-2 px-4 text-white text-center">
                        問題
                    </div>
                </div>
            </div>

            <p v-if="exerciseBooks.emptyFlg" class="flex justify-center">
                まだ問題集は作成されていません。
            </p>

            <ExerciseBookCard
                v-for="exerciseBook in exerciseBooks.data"
                :key="exerciseBook.id"
                :card-data="exerciseBook"
                class="mb-4"
            >
            </ExerciseBookCard>

            <InfiniteLoading
                :identifier="infiniteId"
                @infinite="infiniteHandler"
                :spinner="'spiral'"
                class="pb-4"
                ><template v-slot:no-more
                    ><span class="text-sm text-gray-500"
                        >これ以上問題はありません</span
                    ></template
                >
                <template v-slot:no-results
                    ><span class="text-sm text-gray-600"
                        >検索結果に該当する問題集はありません</span
                    ></template
                ></InfiniteLoading
            >
        </main>
        <!-- ローディング -->
        <TheLoading
            v-if="loading.isLoading"
            :loading="loading.isLoading"
            class="h-screen"
        />
        <!-- フラッシュメッセージ -->
        <transition name="center-modal">
            <CenterModal v-if="isFlashMsg">
                <div class="p-2 bg-gray-800 text-white rounded-md">
                    {{ flashMsg.text }}
                </div>
            </CenterModal>
        </transition>

        <TheFooter />
        <TouchNotPossible v-if="isOperating" />
    </div>
</template>

<script>
import Common from "../commonMixin";
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import BaseButton from "../components/BaseButton";
import ExerciseBookCard from "../components/ExerciseBookCard";
import TheLoading from "../components/TheLoading";
import ChangeTabBtn from "../components/ChangeTabBtn";
import BaseSearchBox from "../components/BaseSearchBox";
import CenterModal from "../components/CenterModal";
import NoSearchResults from "../components/NoSearchResults";
import TouchNotPossible from "../components/TouchNotPossible";
import {
    INTERNAL_SERVER_ERROR,
    OK,
    UNPROCESSABLE_ENTITY,
    NotFound
} from "../util";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faUserCircle } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faUserCircle);
import InfiniteLoading from "vue-infinite-loading";

export default {
    name: "Profile",

    components: {
        TheHeader,
        TheFooter,
        BaseButton,
        ExerciseBookCard,
        TheLoading,
        ChangeTabBtn,
        BaseSearchBox,
        CenterModal,
        FontAwesomeIcon,
        NoSearchResults,
        InfiniteLoading,
        TouchNotPossible
    },

    mixins: [Common],

    data() {
        return {
            exerciseBooks: {
                placeholder: "問題集を入力",
                data: [],
                emptyFlg: false
            },
            user: [],
            isIconProfileImg: false,
            displayTab: {
                isOwnExerciseBooks: true,
                isOwnExerciseBooksSearch: false,
                isFavoriteOrder: false,
                isFavoriteOrderSearch: false
            },
            editBtn: {
                btnText: "編集",
                btnTextColor: "text-blue-400",
                btnBgColor: "bg-white"
            },
            loading: {
                isLoading: false,
                opacity: 0
            },
            isFollowedBy: false,
            followingsCount: "",
            followersCount: "",
            flashMsg: {
                text: "登録が完了しました。",
                speed: 2000
            },
            searchBoxKeyword: "",
            noSearchResults: false,
            page: 1,
            infiniteId: new Date(),
            url: "",
            isOperating: false
        };
    },

    computed: {
        followBtnBgColor() {
            return this.isFollowedBy ? "bg-blue-400" : "bg-white";
        },

        followBtnTextColor() {
            return this.isFollowedBy ? "text-white" : "text-blue-400";
        },

        followBtnText() {
            return this.isFollowedBy ? "フォロー中" : "フォローする";
        },

        isLoginUser() {
            if (this.$_authCheck) {
                const paramsUserId = parseInt(this.$route.params.id);
                const loginUserId = parseInt(this.$store.state.auth.user.id);
                return loginUserId === paramsUserId ? true : false;
            } else {
                return false;
            }
        },

        isFlashMsg() {
            return this.$store.state.flashMessage.visible;
        },

        // ユーザー情報に画像が登録されていない場合、デフォルトの画像を表示する為のフラグの操作
        isProfileImgEmpty() {
            return this.user.profile_img
                ? (this.isIconProfileImg = false)
                : (this.isIconProfileImg = true);
        },

        // 表示する問題集がない場合に表示するテキストのフラグ操作
        isExerciseBooksEmpty() {
            this.exerciseBooks.data.length === 0
                ? (this.exerciseBooks.emptyFlg = true)
                : (this.exerciseBooks.emptyFlg = false);
        }
    },

    watch: {
        // フォロー、アンフォローの度に自身のフォロー数を取得する
        async isFollowedBy() {
            const url = `/api/users/${this.$route.params.id}/followers/count`;
            const response = await axios
                .get(url)
                .catch(error => error.response || error);

            if (response.status === OK) {
                this.followersCount = response.data.follower_count;
                return;
            }

            this.$_internalServerError(response.status);
        },
        $route() {
            this.getUser();
        }
    },

    async mounted() {
        this.$_scrollTop();
        await this.getUser();

        if (this.isFlashMsg) {
            this.$store.dispatch(
                "flashMessage/hideFlashMsg",
                this.flashMsg.speed
            );
        }
    },

    methods: {
        async getUser() {
            const url = `/api/users/${this.$route.params.id}`;
            const response = await axios
                .get(url)
                .catch(error => error.response || error);

            if (response.status === OK) {
                this.user = response.data.user;
                this.isProfileImgEmpty;
                this.followersCount = response.data.user.followers_count;
                this.followingsCount = response.data.user.followings_count;
                this.isFollowedBy = response.data.user.is_followed_by;
            }
            this.$_notFoundError(response.status);
            this.$_internalServerError(response.status);
        },

        ownExerciseBooks() {
            this.displayTab.isOwnExerciseBooks = true;
            this.displayTab.isFavoriteOrder = false;
            this.displayTab.isFavoriteOrderSearch = false;
            this.$_scrollTop();
            this.changeType();
            this.searchBoxReset();
        },

        favoriteOrder() {
            this.displayTab.isOwnExerciseBooks = false;
            this.displayTab.isOwnExerciseBooksSearch = false;
            this.displayTab.isFavoriteOrder = true;
            this.$_scrollTop();
            this.changeType();
            this.searchBoxReset();
        },

        async getOwnExercizeBooks($_state) {
            if (this.displayTab.isOwnExerciseBooks) {
                console.log("自身の問題集検索なし");
                this.url = `/api/exercise-books/${this.$route.params.id}?page=${this.page}`;
            }
            if (this.displayTab.isOwnExerciseBooksSearch) {
                console.log("自身の問題集検索あり");
                this.url = `/api/exercise-books/${this.$route.params.id}?search=${this.searchBoxKeyword}&page=${this.page}`;
            }

            const response = await axios
                .get(this.url)
                .catch(error => error.response || error);

            if (response.status === OK) {
                console.log("API取得に成功");
                console.log(response);
                if (response.data.exercise_books.length) {
                    this.page++;
                    this.exerciseBooks.data = this.exerciseBooks.data.concat(
                        response.data.exercise_books
                    );
                    $_state.loaded();
                    return;
                } else {
                    console.log("取得数0");
                    $_state.complete();
                }
            }

            this.$_internalServerError(response.status);
        },

        async getFavoriteOrderExerciseBooks($_state) {
            if (this.displayTab.isFavoriteOrder) {
                console.log("お気に入り検索なし");
                this.url = `/api/favorites/asc/${this.$route.params.id}?page=${this.page}`;
            }
            if (this.displayTab.isFavoriteOrderSearch) {
                console.log("お気に入り検索あり");
                this.url = `/api/favorites/asc/${this.$route.params.id}?search=${this.searchBoxKeyword}&page=${this.page}`;
            }

            const response = await axios
                .get(this.url)
                .catch(error => error.response);

            if (response.status === OK) {
                console.log("API取得に成功");
                console.log(response);
                if (response.data.exercise_books.length) {
                    this.page++;
                    this.exerciseBooks.data = this.exerciseBooks.data.concat(
                        response.data.exercise_books
                    );
                    $_state.loaded();
                    return;
                } else {
                    console.log("取得数0");
                    $_state.complete();
                }
            }

            this.$_internalServerError(response.status);
        },

        async isFollow() {
            if (this.$_authCheck) {
                const url = `/api/users/${this.$route.params.id}/follow`;
                if (this.isFollowedBy) {
                    this.isFollowedBy = false;
                    const response = await axios
                        .delete(url)
                        .catch(error => error.response || error);

                    this.$_internalServerError(response.status);
                } else {
                    this.isFollowedBy = true;
                    const response = await axios
                        .put(url)
                        .catch(error => error.response || error);

                    this.$_internalServerError(response.status);
                }
            } else {
                this.$store.dispatch("auth/openPromptToRegisterOrLoginModal");
            }
        },

        goEditScreen() {
            this.$router.push(`/users/${this.$route.params.id}/edit`);
        },

        filterExerciseBooks() {
            this.$_scrollTop();
            if (
                this.displayTab.isOwnExerciseBooks ||
                this.displayTab.isOwnExerciseBooksSearch
            ) {
                console.log("自身の問題集検索");
                this.displayTab.isOwnExerciseBooksSearch = true;
                this.displayTab.isOwnExerciseBooks = false;
                this.changeType();
                return;
            }
            if (
                this.displayTab.isFavoriteOrder ||
                this.displayTab.isFavoriteOrderSearch
            ) {
                console.log("自身がお気に入り登録した問題集");
                this.displayTab.isFavoriteOrderSearch = true;
                this.displayTab.isFavoriteOrder = false;
                this.changeType();
                return;
            }
        },

        searchBoxReset() {
            this.searchBoxKeyword = "";
        },

        async infiniteHandler($state) {
            if (
                this.displayTab.isOwnExerciseBooks ||
                this.displayTab.isOwnExerciseBooksSearch
            ) {
                this.isOperating = true;
                console.log("自身の問題集処理");
                await this.getOwnExercizeBooks($state);
                this.isOperating = false;

                return;
            }

            if (
                this.displayTab.isFavoriteOrder ||
                this.displayTab.isFavoriteOrderSearch
            ) {
                this.isOperating = true;
                console.log("お気に入り処理");
                await this.getFavoriteOrderExerciseBooks($state);
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
.img-circle:before {
    display: block;
    content: "";
    padding-bottom: 100%;
}

.img-profile {
    border-radius: 50%;
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

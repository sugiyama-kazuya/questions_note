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
                            <font-awesome-icon
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
                    :isLeftActive="displayTab.isOwnExerciseBooks"
                    :isRightActive="displayTab.isFavoriteOrder"
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
                :cardData="exerciseBook"
                class="mb-4"
            >
            </ExerciseBookCard>

            <NoSearchResults v-if="noSearchResults" />
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
import { INTERNAL_SERVER_ERROR, OK } from "../util";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faUserCircle } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faUserCircle);

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
        NoSearchResults
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
                isFavoriteOrder: false
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
            noSearchResults: false
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
            const paramsUserId = parseInt(this.$route.params.id);
            const loginUserId = parseInt(this.$store.state.auth.user.id);
            return loginUserId === paramsUserId ? true : false;
        },
        isFlashMsg() {
            return this.$store.state.flashMessage.visible;
        },

        isProfileImgEmpty() {
            return this.user.profile_img
                ? (this.isIconProfileImg = false)
                : (this.isIconProfileImg = true);
        },

        isExerciseBooksEmpty() {
            this.exerciseBooks.data.length === 0
                ? (this.exerciseBooks.emptyFlg = true)
                : (this.exerciseBooks.emptyFlg = false);
        }
    },

    watch: {
        async isFollowedBy() {
            const url = `/api/users/${this.$route.params.id}/followers/count`;
            const response = await axios
                .get(url)
                .catch(error => error.response || error);

            this.followersCount = response.data.follower_count;
        },
        async $route() {
            this.loading.isLoading = true;
            await this.getOwnExercizeBooks();
            await this.getUser();
            this.loading.isLoading = false;
        }
    },

    async created() {
        this.scrollTop();
        this.loading.isLoading = true;
        await this.getUser();
        await this.getOwnExercizeBooks();
        this.loading.isLoading = false;

        // フラッシュメッセージがある場合
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

            this.user = response.data.user;
            this.isProfileImgEmpty;
            this.followersCount = response.data.user.followers_count;
            this.followingsCount = response.data.user.followings_count;
            this.isFollowedBy = response.data.user.is_followed_by;
        },

        async getOwnExercizeBooks() {
            const response = await axios
                .get(`/api/exercise-books/${this.$route.params.id}`)
                .catch(error => error.response || error);

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
            }

            if (response.status === OK) {
                this.exerciseBooks.data = response.data.exercise_books;
                this.isExerciseBooksEmpty;
                return;
            }
        },

        async ownExerciseBooks() {
            this.displayTab.isOwnExerciseBooks = true;
            this.displayTab.isFavoriteOrder = false;
            this.scrollTop();
            this.loading.isLoading = true;
            this.searchBoxReset();
            await this.getOwnExercizeBooks();
            this.loading.isLoading = false;
        },

        async getFavoriteOrderExerciseBooks() {
            const response = await axios
                .get(`/api/favorites/asc/${this.$route.params.id}`)
                .catch(error => error.resoponse);

            if (response.status === OK) {
                this.exerciseBooks.data = response.data.exercise_books;
            }
        },

        async favoriteOrder() {
            this.displayTab.isOwnExerciseBooks = false;
            this.displayTab.isFavoriteOrder = true;
            this.scrollTop();
            this.loading.isLoading = true;
            this.searchBoxReset();
            await this.getFavoriteOrderExerciseBooks();
            this.loading.isLoading = false;
        },

        async isFollow() {
            const url = `/api/users/${this.$route.params.id}/follow`;
            if (this.isFollowedBy) {
                this.isFollowedBy = false;
                const response = await axios
                    .delete(url)
                    .catch(error => error.response || error);

                if (response.status === INTERNAL_SERVER_ERROR) {
                    this.$router.push("/500");
                    return;
                }
            } else {
                this.isFollowedBy = true;
                const response = await axios
                    .put(url)
                    .catch(error => error.response || error);

                if (response.status === INTERNAL_SERVER_ERROR) {
                    this.$router.push("/500");
                    return;
                }
            }
        },
        goEditScreen() {
            this.$router.push(`/users/${this.$route.params.id}/edit`);
        },

        isEmpty(data) {
            return !Object.keys(data).length;
        },

        async filterExerciseBooks() {
            this.scrollTop();
            this.loading.isLoading = true;
            if (this.displayTab.isOwnExerciseBooks) {
                await this.getOwnExercizeBooks();
            }
            if (this.displayTab.isFavoriteOrder) {
                await this.getFavoriteOrderExerciseBooks();
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
            this.loading.isLoading = false;
        },

        searchBoxReset() {
            this.searchBoxKeyword = "";
            this.noSearchResults = false;
        }
    }
};
</script>

<style scoped lang="scss">
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

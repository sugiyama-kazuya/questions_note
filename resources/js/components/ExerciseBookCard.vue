<template>
    <div class="bg-white mx-3 rounded-sm shadow-sm px-2 flex flex-column">
        <div @click="goProblem(cardData.id)" class="flex flex-column">
            <div class="px-2 py-4 pt-2">{{ cardData.name }}</div>
        </div>
        <div
            class="border-t p-3 flex items-center justify-between text-gray-600"
        >
            <div class="flex items-center w-1/3">
                <router-link
                    tag="div"
                    :to="{
                        name: 'profile',
                        params: {
                            id: cardData.user.id
                        }
                    }"
                    class="flex w-100"
                >
                    <FontAwesomeIcon
                        v-if="!cardData.profile_img"
                        icon="user-circle"
                        class="text-3xl mr-2"
                    />
                    <img
                        v-if="cardData.user.profile_img"
                        class="h-2rem w-2rem mr-2 bg-cover img-profile"
                        :src="cardData.profile_img"
                    />
                    <span class="flex items-center">{{
                        cardData.user.name
                    }}</span>
                </router-link>
            </div>
            <div class="flex w-1/3">
                <FontAwesomeIcon
                    @click="isLiked(cardData.id)"
                    icon="star"
                    class="text-2xl mr-2"
                    :class="{ 'is-like': isLikedBy }"
                />
                <span>{{ count }}</span>
            </div>
            <div v-if="cardData.problem_update_at" class="w-1/3 text-right">
                <span class="mr-2">更新日</span
                >{{ formatDate(cardData.problem_update_at) }}
            </div>
            <div v-if="!cardData.problem_update_at" class="w-1/3 text-right">
                <span>問題がありません</span>
            </div>
        </div>
    </div>
</template>

<script>
import { OK } from "../util";
import Common from "../commonMixin";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faUserCircle, faStar } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faUserCircle, faStar);

export default {
    name: "ExerciseBookCard",
    props: {
        cardData: {
            type: Object,
            required: true
        }
    },

    components: {
        FontAwesomeIcon
    },

    mixins: [Common],

    data() {
        return {
            isLikedBy: false,
            count: ""
        };
    },

    computed: {
        formatDate() {
            return function(currentDate) {
                if (currentDate) {
                    const date = new Date(currentDate);
                    return (
                        date.getFullYear() +
                        "/" +
                        `${date.getMonth() + 1}` +
                        "/" +
                        date.getDate()
                    );
                } else {
                    return currentDate;
                }
            };
        }
    },

    watch: {
        async isLikedBy(newIsLikedBy, oldIsLikedBy) {
            const countFavorites = await this.countFavorites(this.cardData.id);
            countFavorites ? (this.count = countFavorites) : (this.count = 0);
        }
    },

    mounted() {
        this.count = this.cardData.favorite_count;
        this.isLikedBy = this.cardData.is_liked_by;
    },

    methods: {
        goProblem(exerciseBooksId) {
            this.$router.push({
                name: "ExerciseBookPlay",
                params: { id: exerciseBooksId }
            });
        },

        async isLiked(problemId) {
            // ユーザーが未ログインの時は、ログイン処理を促すモーダルを表示
            if (this.$store.state.auth.user) {
                // 既にいいねしていた場合
                if (this.isLikedBy) {
                    const response = await axios
                        .delete("/api/unfavorites", {
                            data: { id: problemId }
                        })
                        .catch(error => error.response || error);

                    this.$_internalServerError(response.status);

                    this.isLikedBy = false;
                } else {
                    // まだしていない場合
                    const response = await axios
                        .put("/api/favorites", { id: problemId })
                        .catch(error => error.response || error);

                    this.$_internalServerError(response.status);

                    this.isLikedBy = true;
                }
            } else {
                this.$store.dispatch("auth/openPromptToRegisterOrLoginModal");
            }
        },

        async countFavorites(id) {
            const url = `/api/${id}/favorites/counts`;
            const response = await axios
                .get(url)
                .catch(error => error.response);

            if (response.status === OK) {
                return response.data.count;
            }
            this.$_internalServerError(response.status);
        }
    }
};
</script>

<style scoped>
.is-like {
    color: red;
}

.img-profile {
    border-radius: 50%;
}
</style>

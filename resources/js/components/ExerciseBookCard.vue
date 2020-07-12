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
                    <font-awesome-icon
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
                <font-awesome-icon
                    @click="isLiked(cardData.id)"
                    icon="star"
                    class="text-2xl mr-2"
                    :class="{ 'is-like': isLikedBy }"
                />
                <span>{{ count }}</span>
            </div>
            <div class="w-1/3 text-right">{{ cardData.updated_at }}</div>
        </div>
    </div>
</template>

<script>
import { INTERNAL_SERVER_ERROR } from "../util";
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

    data() {
        return {
            isLikedBy: false,
            count: ""
        };
    },

    watch: {
        async isLikedBy(newIsLikedBy, oldIsLikedBy) {
            const countFavorites = await this.countFavorites(this.cardData.id);
            countFavorites ? (this.count = countFavorites) : (this.count = 0);
        }
    },

    async mounted() {
        this.count = this.cardData.favolite_count;
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
            // 既にいいねしていた場合
            if (this.isLikedBy) {
                const response = await axios
                    .delete("/api/unlike", { data: { id: problemId } })
                    .catch(error => error.response);

                if (response.status === INTERNAL_SERVER_ERROR) {
                    this.$router.push("/500");
                    return;
                }

                this.isLikedBy = false;
            } else {
                // まだしていない場合
                const response = await axios
                    .put("/api/like", { id: problemId })
                    .catch(error => error.response || error);

                if (response.status === INTERNAL_SERVER_ERROR) {
                    this.$router.push("/500");
                    return;
                }

                this.isLikedBy = true;
            }
        },

        async countFavorites(id) {
            const url = `/api/exercise-books/${id}/favorites/counts`;
            const response = await axios
                .get(url)
                .catch(error => error.response);

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
                return;
            }

            return response.data.count;
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

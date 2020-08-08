<template>
    <div class="relative h-100">
        <div class="h-90">
            <TheHeader class="h-8 bg-gray-200">
                <template v-slot:titleName>
                    <h5 class="text-2xl m-0">編集</h5>
                </template>
                <template v-slot:leftSide>
                    <FontAwesomeIcon
                        @click="endConfimationModal = true"
                        icon="times"
                        class="text-3xl text-white"
                    />
                </template>
                <template v-slot:rightSide>
                    <button
                        class="text-white focus:outline-none"
                        type="submit"
                        form="profile-edit-form"
                    >
                        変更
                    </button>
                </template>
            </TheHeader>

            <main class="h-92 overflow-y-scroll bg-gray-100 relative">
                <div class="flex justify-center items-center h-100">
                    <div class="w-full h-100">
                        <div v-if="uploadImg" class="flex justify-center mt-4">
                            <img
                                class="profile-img h-12rem w-12rem bg-cover"
                                :src="uploadImg"
                            />
                        </div>
                        <form
                            id="profile-edit-form"
                            @submit.prevent="saveProfile()"
                            class="px-8 pt-6 pb-8 mb-4"
                        >
                            <transition name="validateError">
                                <div v-if="saveProfileErrorMsg" class="mb-6">
                                    <p
                                        v-for="msg in saveProfileErrorMsg.name"
                                        :key="msg"
                                        class="text-red-500 text-xs italic"
                                    >
                                        {{ msg }}
                                    </p>
                                </div>
                            </transition>

                            <div class="mb-4">
                                <label
                                    class="block text-gray-700 text-sm font-bold mb-2"
                                    for="username"
                                    >Username</label
                                >
                                <input
                                    v-model="profile.name"
                                    class="shadow-sm appearance-none border rounded w-2/3 py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    id="name"
                                    type="text"
                                    placeholder="Username"
                                />
                                <transition name="validateError">
                                    <div v-if="validationErrorMsg.name">
                                        <p
                                            v-for="msg in validationErrorMsg.name"
                                            :key="msg"
                                            class="text-red-500 text-xs italic"
                                        >
                                            {{ msg }}
                                        </p>
                                    </div>
                                </transition>
                            </div>
                            <div class="mb-6">
                                <label
                                    class="block text-gray-700 text-sm font-bold mb-2"
                                    for="email"
                                    >email</label
                                >
                                <input
                                    v-model="profile.email"
                                    class="shadow-sm appearance-none border rounded w-2/3 py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                                    id="email"
                                    type="email"
                                    placeholder="example@com"
                                />
                                <transition name="validateError">
                                    <div v-if="validationErrorMsg.email">
                                        <p
                                            v-for="msg in validationErrorMsg.email"
                                            :key="msg"
                                            class="text-red-500 text-xs italic"
                                        >
                                            {{ msg }}
                                        </p>
                                    </div>
                                </transition>
                            </div>
                            <div class="mb-6">
                                <label
                                    class="block text-gray-700 text-sm font-bold mb-2"
                                    for="email"
                                    >プロフィール画像</label
                                >
                                <label
                                    class="p-2 border rounded-lg text-gray-500 text-sm shadow-sm"
                                >
                                    画像を選択
                                    <input
                                        ref="profileImg"
                                        id="profileImg"
                                        @change="onFileChange($event)"
                                        type="file"
                                        class="hidden"
                                    />
                                </label>
                            </div>
                            <div
                                class="flex items-center justify-between"
                            ></div>
                        </form>
                    </div>
                </div>
                <TheLoading if="isLoading" :loading="isLoading" />
            </main>
        </div>
        <TheFooter />
        <transition name="center-modal">
            <CenterModal v-if="endConfimationModal" :back-color="false">
                <div class="py-5 px-3">
                    <div class="p-4 text-center text-lg mb-2">
                        <span
                            >変更した内容は保存されませんが終了でよろしいでしょうか？</span
                        >
                    </div>
                    <div class="flex flex-col justify-center p-2">
                        <CenterModalBtn
                            @click.native="goOwnProfile"
                            :text="'プロフィールへ'"
                            :color="'bg-blue-400'"
                            class="mb-3"
                        />
                        <CenterModalBtn
                            @click.native="endConfimationModal = false"
                            :text="'編集に戻る'"
                        />
                    </div>
                </div>
            </CenterModal>
        </transition>
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import CenterModal from "../components/CenterModal";
import CenterModalBtn from "../components/CenterModalBtn";
import TheLoading from "../components/TheLoading";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faTimes } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faTimes);
import { OK, UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR } from "../util";
import Common from "../commonMixin";

export default {
    name: "profileEdit",
    components: {
        TheHeader,
        TheFooter,
        FontAwesomeIcon,
        CenterModal,
        CenterModalBtn,
        TheLoading
    },

    mixins: [Common],

    data() {
        return {
            endConfimationModal: false,
            saveProfileErrorMsg: "",
            profile: {
                name: "",
                email: "",
                file: ""
            },
            uploadImg: "",
            validationErrorMsg: {
                name: "",
                email: "",
                file: ""
            },
            isLoading: false,
            userId: this.$route.params.id
        };
    },

    computed: {
        urlParam() {
            return this.$route.params.id;
        }
    },

    async created() {
        this.isLoading = true;
        await this.getUser();
        this.isLoading = false;
    },

    methods: {
        async getUser() {
            const response = await axios
                .get(`/api/users/${this.$route.params.id}/edit`)
                .catch(error => error.firstDisplay || error);

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$router.push("/500");
                return;
            }

            if (response.status === OK) {
                this.uploadImg = response.data.user.profile_img;
                this.profile.name = response.data.user.name;
                this.profile.email = response.data.user.email;
                return;
            }
        },

        goOwnProfile() {
            this.$router.push(`/users/${this.userId}`);
        },

        async saveProfile() {
            this.isLoading = true;
            const formData = await new FormData();
            formData.append("name", this.profile.name);
            formData.append("email", this.profile.email);
            formData.append("file", this.profile.file);
            const url = `/api/users/${this.urlParam}`;

            const response = await axios
                .post(url, formData, {
                    headers: {
                        "X-HTTP-Method-Override": "PUT"
                    }
                })
                .catch(error => error.response || error);

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.validationErrorMsg.name = response.data.errors.name;
                this.validationErrorMsg.email = response.data.errors.email;
                this.validationErrorMsg.file = response.data.errors.file;
                this.isLoading = false;
                return;
            }

            if (response.status === OK) {
                this.$store.dispatch("flashMessage/showFlashMsg");
                this.$router.push(`/users/${this.userId}`);
                return;
            }

            this.$_internalServerError(response.status);
        },

        onFileChange(event) {
            const file = event.target.files[0];
            this.profile.file = file;
            this.createImg(file);
        },

        createImg(file) {
            const obj = this;
            const profileFileReader = new FileReader();
            profileFileReader.onload = e => {
                obj.uploadImg = e.target.result;
            };
            //base64形式に変換、img要素のsrcの値として機能する
            profileFileReader.readAsDataURL(file);
        }
    }
};
</script>

<style scoped>
.profile-img {
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

.validateError-enter-active,
.validateError-leave-active {
    transition: opacity 0.5s;
}

.validateError-enter,
.validateError-leave-to {
    opacity: 0;
}
</style>

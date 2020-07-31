<template>
    <footer
        class="flex h-6rem py-3 w-100 bg-indigo-400 sticky bottom-0 text-white"
    >
        <div class="w-1/4 flex flex-col items-center justify-center">
            <router-link
                to="/exercise-books"
                tag="div"
                class="flex flex-col items-center justify-center"
                active-class="text-yellow-300"
                exact
            >
                <font-awesome-icon icon="book-open" class="text-3xl" />Index
            </router-link>
        </div>
        <div class="w-1/4 flex flex-col items-center justify-center">
            <template v-if="loginUserId">
                <router-link
                    to="/problems/create"
                    tag="div"
                    class="flex flex-col items-center justify-center"
                    active-class="text-yellow-300"
                    exact
                >
                    <font-awesome-icon icon="plus" class="text-3xl" />Create
                </router-link>
            </template>
            <template v-if="!loginUserId">
                <font-awesome-icon
                    @click="openPromptToRegisterOrLoginModal()"
                    icon="plus"
                    class="text-3xl"
                />Create
            </template>
        </div>
        <div class="w-1/4 flex flex-col items-center justify-center">
            <template v-if="loginUserId">
                <router-link
                    :to="{ name: 'profile', params: { id: loginUserId } }"
                    tag="div"
                    class="flex flex-col items-center justify-center"
                    active-class="text-yellow-300"
                    exact
                >
                    <font-awesome-icon icon="home" class="text-3xl" />Home
                </router-link>
            </template>
            <template v-if="!loginUserId">
                <font-awesome-icon
                    @click="openPromptToRegisterOrLoginModal()"
                    icon="home"
                    class="text-3xl"
                />Home
            </template>
        </div>
        <div class="w-1/4 flex flex-col items-center justify-center">
            <template v-if="loginUserId">
                <router-link
                    to="/list"
                    tag="div"
                    class="flex flex-col items-center justify-center"
                    active-class="text-yellow-300"
                    exact
                >
                    <font-awesome-icon icon="list-ul" class="text-3xl" />List
                </router-link>
            </template>
            <template v-if="!loginUserId">
                <font-awesome-icon
                    @click="openPromptToRegisterOrLoginModal()"
                    icon="list-ul"
                    class="text-3xl"
                />List
            </template>
        </div>
        <transition name="center-modal">
            <CenterModal v-if="isPromptToRegisterOrLoginModal">
                <template>
                    <div class="flex flex-col p-3">
                        <div class="text-right">
                            <font-awesome-icon
                                @click="closePromptToRegisterOrLoginModal()"
                                icon="times-circle"
                                class="text-4xl text-blue-500"
                            />
                        </div>
                        <div class="py-4 px-2 text-center">
                            <p class="text-gray-600">
                                登録ユーザー限定の機能です。新規登録もしくはログインしてください。
                            </p>
                        </div>
                        <div class="text-right">
                            <BaseButton
                                :color="goRegisterBtn.color"
                                class="mr-2"
                                >新規登録</BaseButton
                            >
                            <BaseButton>ログイン</BaseButton>
                        </div>
                    </div>
                </template>
            </CenterModal>
        </transition>
    </footer>
</template>

<script>
import CenterModal from "../components/CenterModal";
import BaseButton from "../components/BaseButton";
import CancelButton from "../components/CancelButton";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faHome,
    faPlus,
    faBookOpen,
    faListUl,
    faTimesCircle
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faHome, faPlus, faBookOpen, faListUl, faTimesCircle);

export default {
    name: "TheFooter",

    components: {
        FontAwesomeIcon,
        CenterModal,
        BaseButton,
        CancelButton
    },

    data() {
        return {
            goRegisterBtn: {
                text: "新規登録へ",
                color: "bg-red-400"
            },
            goLoginBtn: {
                text: "ログインへ"
            }
        };
    },

    computed: {
        loginUserId() {
            return this.$store.state.auth.user
                ? this.$store.state.auth.user.id
                : false;
        },

        isPromptToRegisterOrLoginModal() {
            return this.$store.state.auth.isPromptToRegisterOrLoginModal;
        }
    },

    methods: {
        goRegister() {
            this.$router.push("/register");
        },

        goLogin() {
            this.$router.push("/login");
        },

        openPromptToRegisterOrLoginModal() {
            this.$store.dispatch("auth/openPromptToRegisterOrLoginModal");
        },

        closePromptToRegisterOrLoginModal() {
            this.$store.dispatch("auth/closePromptToRegisterOrLoginModal");
        }
    }
};
</script>

<style scoped>
.center-modal-enter-active,
.center-modal-leave-active {
    transition: opacity 0.1s;
}

.center-modal-enter,
.center-modal-leave-to {
    opacity: 0;
}
</style>

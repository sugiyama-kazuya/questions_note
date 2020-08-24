<template>
    <div class="h-100 relative">
        <TheHeader class="h-5rem w-screen text-white font-mono fixed">
            <template v-slot:leftSide>
                <router-link
                    to="/openings"
                    class="text-white mr-3 m-0 text-lg"
                    tag="p"
                    >Question's Note</router-link
                >
            </template>
            <template v-slot:rightSide>
                <router-link
                    to="/login"
                    class="text-white mr-3 focus-visible:underline"
                    >login</router-link
                >
                <router-link
                    to="/register"
                    class="text-white focus:outline-none focus-visible:underline"
                    >register</router-link
                >
            </template>
        </TheHeader>
        <div class="flex justify-center items-center h-100 bg-gray-100">
            <div class="w-full max-w-xs">
                <form
                    @submit.prevent="login()"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h5
                        class="text-center border-b-2 border-purple-500 mb-6 pb-2 text-base"
                    >
                        ログイン
                    </h5>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="username"
                            >username</label
                        >
                        <input
                            v-model="loginForm.name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="username"
                            type="text"
                            placeholder="例：Taro"
                        />
                        <transition name="validateError">
                            <div v-if="loginErrorMessages" class="mb-6">
                                <p
                                    v-for="msg in loginErrorMessages.name"
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
                            for="password"
                            >Password</label
                        >
                        <input
                            v-model="loginForm.password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            id="password"
                            type="password"
                            placeholder="***********"
                        />
                        <transition name="validateError">
                            <div v-if="loginErrorMessages" class="mb-6">
                                <p
                                    v-for="msg in loginErrorMessages.password"
                                    :key="msg"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ msg }}
                                </p>
                            </div>
                        </transition>
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:outline-none"
                            type="submit"
                        >
                            Sign In
                        </button>
                        <router-link
                            class="inline-block align-baseline font-bold text-xs text-blue-500 hover:text-blue-800"
                            to="/password/reset"
                            >パスワードを忘れた方</router-link
                        >
                    </div>
                </form>
                <p class="text-center text-gray-500 text-xs">
                    &copy;2020 Question's Note. All rights reserved.
                </p>
            </div>
        </div>
        <!-- ローディング -->
        <transition name="center-modal">
            <TheLoading
                v-if="isLoading"
                :loading="isLoading"
                class="h-screen"
            />
        </transition>

        <!-- パスワード変更後のフラッシュメッセージ -->
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
import TheHeader from "../components/TheHeader";
import CenterModal from "../components/CenterModal";
import TheLoading from "../components/TheLoading";

export default {
    name: "App",

    components: {
        TheHeader,
        CenterModal,
        TheLoading
    },

    data: () => ({
        loginForm: {
            name: "",
            password: ""
        },
        isError: false,
        isLoading: false,
        flashMsg: {
            text: "パスワードを変更しました。",
            speed: 1500
        }
    }),

    computed: {
        apiStatus() {
            return this.$store.state.auth.apiStatus;
        },
        loginErrorMessages() {
            return this.$store.state.auth.loginErrorMessages;
        },
        // フラッシュメッセージの有無
        isFlashMsg() {
            return this.$store.state.flashMessage.visible;
        }
    },

    async mounted() {
        await this.clearLoginErrorMasseage();
        if (this.isFlashMsg) {
            await this.$store.dispatch(
                "flashMessage/hideFlashMsg",
                this.flashMsg.speed
            );
        }
    },

    methods: {
        async login() {
            await this.clearLoginErrorMasseage();
            await this.$store.dispatch("auth/login", this.loginForm);
            if (this.apiStatus) {
                this.$router.push("/exercise-books");
            }
        },

        clearLoginErrorMasseage() {
            this.$store.commit("auth/setLoginErrorMessages", null);
        }
    }
};
</script>

<style scoped>
.validateError-enter-active,
.validateError-leave-active {
    transition: opacity 0.5s;
}

.validateError-enter,
.validateError-leave-to {
    opacity: 0;
}

.center-modal-enter-active,
.center-modal-leave-active {
    transition: opacity 0.3s;
}

.center-modal-enter,
.center-modal-leave-to {
    opacity: 0;
}
</style>

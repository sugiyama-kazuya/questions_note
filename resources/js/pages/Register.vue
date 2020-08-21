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
                <router-link to="/login" class="text-white mr-3"
                    >login</router-link
                >
                <router-link to="/register" class="text-white"
                    >register</router-link
                >
            </template>
        </TheHeader>

        <div class="flex justify-center items-center h-100 bg-gray-100">
            <div class="w-full max-w-xs">
                <form
                    @submit.prevent="register()"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h5
                        class="text-center border-b-2 border-purple-500 mb-6 pb-2 text-base"
                    >
                        ユーザー登録
                    </h5>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="username"
                        >
                            Username
                        </label>
                        <input
                            v-model="registerForm.name"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="username"
                            type="text"
                            placeholder="例：Taro"
                        />
                        <transition name="validateError">
                            <div v-if="registerErrorMessages">
                                <p
                                    v-for="msg in registerErrorMessages.name"
                                    :key="msg"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ msg }}
                                </p>
                            </div>
                        </transition>
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="email"
                        >
                            email
                        </label>
                        <input
                            v-model="registerForm.email"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            id="email"
                            type="email"
                            placeholder="example@com"
                        />
                        <transition name="validateError">
                            <div v-if="registerErrorMessages">
                                <p
                                    v-for="msg in registerErrorMessages.email"
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
                        >
                            Password
                        </label>
                        <input
                            v-model="registerForm.password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="password"
                            type="password"
                            placeholder="***********"
                        />
                        <transition name="validateError">
                            <div v-if="registerErrorMessages">
                                <p
                                    v-for="msg in registerErrorMessages.password"
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
                        >
                            再入力
                        </label>
                        <input
                            v-model="registerForm.password_confirmation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="password_confirmation"
                            type="password"
                            placeholder="***********"
                        />
                        <transition name="validateError"> </transition>
                    </div>

                    <div class="flex justify-between">
                        <button
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:outline-none"
                            type="submit"
                        >
                            Sign
                        </button>
                    </div>
                </form>
                <p class="text-center text-gray-500 text-xs">
                    &copy;2020 Question's Note. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";

export default {
    name: "Register",

    components: {
        TheHeader
    },

    data() {
        return {
            registerForm: {
                name: "",
                email: "",
                password: "",
                password_confirmation: ""
            }
        };
    },

    computed: {
        registerErrorMessages() {
            return this.$store.state.auth.registerErrorMessages;
        },

        apiStatus() {
            return this.$store.state.auth.apiStatus;
        }
    },

    mounted() {
        this.clearRegisterErrorMessages();
    },

    methods: {
        async register() {
            await this.clearRegisterErrorMessages();
            await this.$store.dispatch("auth/register", this.registerForm);
            if (this.apiStatus) {
                this.$router.push("/exercise-books");
            }
        },

        clearRegisterErrorMessages() {
            this.$store.commit("auth/setRegisterErrorMessages", null);
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
</style>

<template>
    <div class="flex flex-col min-h-screen">
        <TheHeader class="h-5rem w-screen text-white font-mono">
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
        <div class="flex-1 flex items-center justify-center">
            <div class="w-full max-w-xs">
                <form
                    @submit.prevent="passwordChenge()"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h5
                        class="text-center border-b-2 border-purple-500 mb-6 pb-2 text-base"
                    >
                        パスワードのリセット
                    </h5>

                    <transition name="validateError">
                        <div v-if="validationErrorMsg" class="mb-6">
                            <p
                                v-for="msg in validationErrorMsg.password"
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
                            >新しいパスワード</label
                        >
                        <input
                            v-model="form.password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="password"
                            type="password"
                            placeholder="8文字以上で入力してください"
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="username"
                            >新しいパスワード再入力</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="password_confirmation"
                            type="password"
                            placeholder="***********"
                        />
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:outline-none"
                            type="submit"
                        >
                            変更
                        </button>
                    </div>
                </form>
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
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheLoading from "../components/TheLoading";

import { OK, UNPROCESSABLE_ENTITY } from "../util";
import Common from "../commonMixin";

export default {
    name: "PasswordResetForm",
    components: {
        TheHeader,
        TheLoading
    },

    mixins: [Common],

    data() {
        return {
            form: {
                email: this.$route.params.email,
                token: this.$route.params.token,
                password: "",
                password_confirmation: ""
            },
            validationErrorMsg: "",
            isLoading: false
        };
    },

    methods: {
        async passwordChenge() {
            this.isLoading = true;
            const response = await axios
                .post("/api/password/reset", this.form)
                .catch(error => error.response || error);

            if (response.status === OK) {
                this.$router.push("/login");
                await this.$store.dispatch("flashMessage/showFlashMsg");
                this.isLoading = false;
                return;
            }

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.validationErrorMsg = response.data.errors;
                this.isLoading = false;
                return;
            }

            this.$_internalServerError(response.status);
            this.isLoading = false;
        }
    }
};
</script>

<style scoped>
.center-modal-enter-active,
.center-modal-leave-active {
    transition: opacity 0.3s;
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

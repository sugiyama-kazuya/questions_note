<template>
    <div class="flex justify-center items-center h-100 bg-gray-100">
        <div class="w-full max-w-xs">
            <ValidationObserver v-slot="{ handleSubmit }">
                <form
                    @submit.prevent="handleSubmit(register)"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
                >
                    <h5
                        class="text-center border-b-2 border-purple-500 mb-6 pb-2"
                    >
                        ユーザー登録
                    </h5>

                    <transition name="validateError">
                        <div v-if="registerErrorMessages" class="mb-6">
                            <div v-if="registerErrorMessages.name">
                                <p
                                    v-for="msg in registerErrorMessages.name"
                                    :key="msg"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ msg }}
                                </p>
                            </div>
                            <div v-if="registerErrorMessages.email">
                                <p
                                    v-for="msg in registerErrorMessages.email"
                                    :key="msg"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ msg }}
                                </p>
                            </div>

                            <div v-if="registerErrorMessages.password">
                                <p
                                    v-for="msg in registerErrorMessages.password"
                                    :key="msg"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ msg }}
                                </p>
                            </div>
                        </div>
                    </transition>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="username"
                        >
                            Username
                        </label>
                        <ValidationProvider
                            name="name"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <input
                                v-model="registerForm.name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="username"
                                type="text"
                                placeholder="Username"
                            />
                            <transition name="validateError">
                                <p
                                    v-if="errors[0]"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ errors[0] }}
                                </p>
                            </transition>
                        </ValidationProvider>
                    </div>
                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="email"
                        >
                            email
                        </label>
                        <ValidationProvider
                            name="email"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <input
                                v-model="registerForm.email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                                id="email"
                                type="email"
                                placeholder="example@com"
                            />
                            <transition name="validateError">
                                <p
                                    v-if="errors[0]"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ errors[0] }}
                                </p>
                            </transition>
                        </ValidationProvider>
                    </div>
                    <div class="mb-6">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="password"
                        >
                            Password
                        </label>
                        <ValidationProvider
                            name="password"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <input
                                v-model="registerForm.password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="password"
                                type="password"
                                placeholder="***********"
                            />
                            <transition name="validateError">
                                <p
                                    v-if="errors[0]"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ errors[0] }}
                                </p>
                            </transition>
                        </ValidationProvider>
                    </div>

                    <div class="mb-6">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                            for="password"
                        >
                            再入力
                        </label>
                        <ValidationProvider
                            name="password_confirm"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <input
                                v-model="registerForm.password_confirmation"
                                class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="password_confirmation"
                                type="password"
                                placeholder="***********"
                            />
                            <transition name="validateError">
                                <p
                                    v-if="errors[0]"
                                    class="text-red-500 text-xs italic"
                                >
                                    {{ errors[0] }}
                                </p>
                            </transition>
                        </ValidationProvider>
                    </div>

                    <div class="flex justify-between">
                        <button
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit"
                        >
                            Sign
                        </button>
                    </div>
                </form>
            </ValidationObserver>
            <p class="text-center text-gray-500 text-xs">
                &copy;2020 Acme Corp. All rights reserved.
            </p>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required, max, min, email, confirmed } from "vee-validate/dist/rules";

extend("required", {
    ...required,
    message: "{_field_}は必須です"
});
export default {
    name: "Register",
    components: {
        ValidationProvider,
        ValidationObserver
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

    created() {
        this.clearRegisterErrorMessages();
    },

    methods: {
        async register() {
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

<template>
    <div class="flex justify-center items-center h-full bg-gray-100">

        <div class="w-full max-w-xs">

            <ValidationObserver v-slot="{ handleSubmit }">
                <form @submit.prevent="handleSubmit(login)" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <h1 class="text-center border-b-2 border-purple-500 mb-6 pb-2">ログイン</h1>

                    <transition name="validateError">
                        <div v-if="loginErrorMessages" class="mb-6">
                            <p v-for="msg in loginErrorMessages.name" :key="msg" class="text-red-500 text-xs italic">
                                {{msg}}</p>
                        </div>
                    </transition>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            Username
                        </label>
                        <ValidationProvider name="name" rules="required" v-slot="{errors}">
                            <input
                                v-model="loginForm.name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="username" type="text" placeholder="Username"
                            >
                            <transition name="validateError">
                                <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                            </transition>
                        </ValidationProvider>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Password
                        </label>
                        <ValidationProvider name="password" rules="required" v-slot="{ errors }">
                            <input
                                v-model="loginForm.password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                                id="password" type="password" placeholder="***********"
                            >
                            <transition name="validateError">
                                <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                            </transition>
                        </ValidationProvider>
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit"
                        >
                            Sign In
                        </button>
                        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800"
                           href="#">
                            Forgot Password?
                        </a>
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
    import {ValidationProvider, ValidationObserver, extend} from 'vee-validate';
    import {required} from 'vee-validate/dist/rules';

    extend('required', {
        ...required,
        message: '{_field_}を入力してください'
    });

    export default {
        name: 'App',
        components: {
            ValidationProvider,
            ValidationObserver
        },
        data: () => ({
            loginForm: {
                name: '',
                password: '',

            },
            isError: false
        }),
        computed: {
            apiStatus() {
                return this.$store.state.auth.apiStatus
            },
            loginErrorMessages() {
                return this.$store.state.auth.loginErrorMessages
            }
        },
        methods: {
            async login() {
                await this.$store.dispatch('auth/login', this.loginForm)

                // apistatusが成功の場合のみ遷移する
                if (this.apiStatus) {
                    console.log('true')
                }
            },
            clearLoginErrorMasseage() {
                this.$store.commit('auth/setLoginErrorMessages', null)
            }
        },
        created() {
            this.clearLoginErrorMasseage()
        }
    };
</script>

<style scoped>
    .validateError-enter-active,
    .validateError-leave-active {
        transition: opacity .5s;
    }

    .validateError-enter,
    .validateError-leave-to {
        opacity: 0;
    }

</style>

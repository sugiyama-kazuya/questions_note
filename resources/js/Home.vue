<template>
    <div class="h-full font-sans">
        <router-view/>
    </div>
</template>

<script>
    import {INTERNAL_SERVER_ERROR} from "./util";

    export default {
        name: "Home",
        computed: {
            errorCode() {
                return this.$store.state.error.code
            }
        },
        watch: {
            errorCode: {
                handler(val) {
                    if (val === INTERNAL_SERVER_ERROR) {
                        this.$router.push('/500')
                    }
                },
                immediate: true
            },
            $route() {
                this.$store.commit('error/setCode', null)
            }
        }


    }
</script>

<style scoped>

</style>

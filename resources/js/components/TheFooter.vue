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
            <router-link
                to="/problems/create"
                tag="div"
                class="flex flex-col items-center justify-center"
                active-class="text-yellow-300"
                exact
            >
                <font-awesome-icon icon="plus" class="text-3xl" />Create
            </router-link>
        </div>
        <div class="w-1/4 flex flex-col items-center justify-center">
            <router-link
                :to="{ name: 'profile', params: { id: loginUserId } }"
                tag="div"
                class="flex flex-col items-center justify-center"
                active-class="text-yellow-300"
                exact
            >
                <font-awesome-icon icon="home" class="text-3xl" />Home
            </router-link>
        </div>
        <div class="w-1/4 flex flex-col items-center justify-center">
            <router-link
                to="/list"
                tag="div"
                class="flex flex-col items-center justify-center"
                active-class="text-yellow-300"
                exact
            >
                <font-awesome-icon icon="list-ul" class="text-3xl" />List
            </router-link>
        </div>
    </footer>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faHome,
    faPlus,
    faBookOpen,
    faListUl
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faHome, faPlus, faBookOpen, faListUl);

export default {
    name: "TheFooter",
    components: {
        FontAwesomeIcon
    },
    computed: {
        loginUserId() {
            return this.$store.state.auth.user.id;
        }
    }
};
</script>

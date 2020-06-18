import Vue from "vue";
import VueRouter from "vue-router";
import Register from "./pages/Register";
import Login from "./pages/Login";
import store from "./store";
import SystemError from "./pages/errors/System";
import ProblemIndex from "./pages/ProblemIndex";
import ProblemCreate from "./pages/ProblemCreate";
import Problem from "./pages/Problem";
import ProblemEdit from "./pages/ProblemEdit";
import Profile from "./pages/Profile";
import ProfileEdit from "./pages/ProfileEdit";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/problems",
            name: "ProblemIndex",
            component: ProblemIndex
        },
        {
            path: "/problems/:id(\\d+)",
            name: "problem",
            component: Problem
        },
        {
            path: "/problems/create",
            name: "ProblemCreate",
            component: ProblemCreate
        },
        {
            path: "/problems/:problemId/edit",
            name: "ProblemEdit",
            component: ProblemEdit
        },
        {
            path: "/profile/:userId",
            name: "profile",
            component: Profile
        },
        {
            path: "/profile/:userId/edit",
            name: "profileEdit",
            component: ProfileEdit
        },
        {
            path: "/register",
            name: "register",
            component: Register,
            beforeEnter(to, from, next) {
                if (store.getters["auth/check"]) {
                    next("/");
                } else {
                    next();
                }
            }
        },
        {
            path: "/login",
            name: "login",
            component: Login,
            // 定義されたルートにアクセスされてページコンポーネントが切り替わる直前に呼び出される関数
            beforeEnter(to, from, next) {
                if (store.getters["auth/check"]) {
                    next("/");
                } else {
                    next();
                }
            }
        },
        {
            path: "/500",
            component: SystemError
        }
    ]
});

import Vue from "vue";
import VueRouter from "vue-router";
import Register from "./pages/Register";
import Login from "./pages/Login";
import store from "./store";
import SystemError from "./pages/errors/System";
import Index from "./pages/Index";
import Create from "./pages/Create";
import Problem from "./pages/Problem";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/home",
            name: "home",
            component: Index
        },
        {
            path: "/create",
            name: "create",
            component: Create
        },
        {
            path: "/problem",
            name: "problem",
            component: Problem
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

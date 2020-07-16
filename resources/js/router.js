import Vue from "vue";
import VueRouter from "vue-router";
import Register from "./pages/Register";
import Login from "./pages/Login";
import store from "./store";
import SystemError from "./pages/errors/System";
import ExerciseBooks from "./pages/ExerciseBooks";
import ProblemCreate from "./pages/ProblemCreate";
import ExerciseBookPlay from "./pages/ExerciseBookPlay";
import ProblemEdit from "./pages/ProblemEdit";
import Profile from "./pages/Profile";
import ProfileEdit from "./pages/ProfileEdit";
import List from "./pages/List";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            /* ログインページ */
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
            /* ユーザー登録ページ */
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
            /* 問題集一覧ページ */
            path: "/exercise-books",
            name: "ExerciseBooks",
            component: ExerciseBooks
        },
        {
            /* 問題をプレイするページ */
            path: "/exercise-books/:id(\\d+)",
            name: "ExerciseBookPlay",
            component: ExerciseBookPlay
        },
        {
            /* 問題を作成ページ */
            path: "/problems/create",
            name: "ProblemCreate",
            component: ProblemCreate
        },
        {
            /* 問題を編集ページ */
            path: "/problems/:id/edit",
            name: "ProblemEdit",
            component: ProblemEdit
        },
        {
            /* プロフィールページ */
            path: "/users/:id",
            name: "profile",
            component: Profile
        },
        {
            /* プロフィール編集ページ */
            path: "/users/:id/edit",
            name: "profileEdit",
            component: ProfileEdit
        },
        {
            /* 検索ページ */
            path: "/list",
            name: "List",
            component: List
        },
        {
            /* エラーページ */
            path: "/500",
            component: SystemError
        }
    ]
});

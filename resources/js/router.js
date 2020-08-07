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
import notFound from "./pages/errors/NotFound";

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
                if (store.getters["auth/loginCheck"]) {
                    next("/exercise-books");
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
                if (store.getters["auth/loginCheck"]) {
                    next("/exercise-books");
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
            component: ProblemCreate,
            beforeEnter(to, from, next) {
                if (store.getters["auth/loginCheck"]) {
                    next();
                } else {
                    next("/login");
                }
            }
        },
        {
            /* 問題を編集ページ */
            path: "/problems/:id/edit",
            name: "ProblemEdit",
            component: ProblemEdit,
            beforeEnter(to, from, next) {
                if (store.getters["auth/loginCheck"]) {
                    next();
                } else {
                    next("/login");
                }
            }
        },
        {
            /* プロフィールページ */
            path: "/users/:id",
            name: "profile",
            component: Profile,
            beforeEnter(to, from, next) {
                if (store.getters["auth/loginCheck"]) {
                    next();
                } else {
                    next("/login");
                }
            }
        },
        {
            /* プロフィール編集ページ */
            path: "/users/:id/edit",
            name: "profileEdit",
            component: ProfileEdit,
            beforeEnter(to, from, next) {
                if (store.getters["auth/loginCheck"]) {
                    if (
                        Number(to.params.id) ===
                        Number(store.state.auth.user.id)
                    ) {
                        next();
                    } else {
                        next(`/users/${store.state.auth.user.id}/edit`);
                    }
                } else {
                    next("/login");
                }
            }
        },
        {
            /* 検索ページ */
            path: "/list",
            name: "List",
            component: List,
            beforeEnter(to, from, next) {
                if (store.getters["auth/loginCheck"]) {
                    next();
                } else {
                    next("/login");
                }
            }
        },
        {
            /* エラーページ */
            path: "/500",
            component: SystemError
        },

        {
            name: "/notFound",
            path: "*",
            component: notFound,
            meta: { title: "お探しのページは見つかりませんでした" }
        }
    ]
});

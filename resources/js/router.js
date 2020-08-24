import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
import Register from "./pages/Register";
import Login from "./pages/Login";
import ExerciseBooks from "./pages/ExerciseBooks";
import ExerciseBookPlay from "./pages/ExerciseBookPlay";
import ProblemCreate from "./pages/ProblemCreate";
import ProblemEdit from "./pages/ProblemEdit";
import Profile from "./pages/Profile";
import ProfileEdit from "./pages/ProfileEdit";
import List from "./pages/List";
import SystemError from "./pages/errors/System";
import NotFound from "./pages/errors/NotFound";
import Forbidden from "./pages/errors/Forbidden";
import Opening from "./pages/Opening";
import PasswordReset from "./pages/PasswordReset";
import PasswordResetForm from "./pages/PasswordResetForm";
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
                    store.dispatch("auth/openPromptToRegisterOrLoginModal");
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
            /* 500エラーページ */
            path: "/500",
            component: SystemError
        },

        {
            // 404エラーページ
            name: "notFound",
            path: "*",
            component: NotFound,
            meta: { title: "お探しのページは見つかりませんでした" }
        },

        {
            // 403エラーページ
            name: "Forbidden",
            path: "/403",
            component: Forbidden,
            meta: { title: "お探しのページは表示できません" }
        },

        {
            name: "Opening",
            path: "/openings",
            component: Opening,
            beforeEnter(to, from, next) {
                if (store.getters["auth/loginCheck"]) {
                    next("/exercise-books");
                } else {
                    next();
                }
            }
        },

        {
            name: "PasswordReset",
            path: "/password/reset",
            component: PasswordReset
        },

        {
            name: "PasswordResetForm",
            path: "/password/reset/form/:token/:email",
            component: PasswordResetForm
        }
    ]
});

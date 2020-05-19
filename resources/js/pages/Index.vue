<template>
    <div class="relative h-100">
        <div class="h-90 bg-gray-200">
            <Header class="h-8">
                <template v-slot:titleName>
                    <h5>HOME</h5>
                </template>
            </Header>

            <div class="flex justify-center items-center h-10 px-3 round-sm">
                <div class="inline-flex w-4/5 st-border h-60">
                    <button
                        @click="newArrivalsOrder"
                        class="w-1/2 bg-white text-indigo-500 py-2 px-4 focus:outline-none"
                        :class="{ 'is-tab-active': displayTab.isNewActive }"
                    >
                        新着
                    </button>
                    <button
                        @click="popularOrder"
                        class="w-1/2 bg-white py-2 px-4 text-indigo-500 focus:outline-none"
                        :class="{ 'is-tab-active': displayTab.isPopularActive }"
                    >
                        人気順
                    </button>
                </div>
            </div>

            <main class="overflow-y-scroll h-82 py-2">
                <Loading if="isLoading" :loading="isLoading" />
                <ProblemCard
                    v-for="cardData in problemCardData"
                    :key="cardData.id"
                    :cardData="cardData"
                    class="mb-4"
                ></ProblemCard>
            </main>
        </div>
        <Footer />
    </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import ProblemCard from "../components/ProblemCard";
import Loading from "../components/Loading";

export default {
    name: "Index",
    components: {
        Header,
        Footer,
        ProblemCard,
        Loading
    },
    data() {
        return {
            problemCardData: {},
            isLoading: false,
            displayTab: {
                isNewActive: false,
                isPopularActive: false
            }
        };
    },
    async mounted() {
        this.isLoading = true;
        await this.$store.dispatch("listProblem/getProblmeCard");
        this.isLoading = false;
        this.problemCardData = await this.$store.state.listProblem
            .problemCardData;
        this.displayTab.isNewActive = true;
    },
    methods: {
        async newArrivalsOrder() {
            this.displayTab.isPopularActive = false;
            this.displayTab.isNewActive = true;

            this.isLoading = true;
            await this.$store.dispatch("listProblem/getProblmeCard");
            this.isLoading = false;

            this.problemCardData = await this.$store.state.listProblem
                .problemCardData;
        },
        async popularOrder() {
            this.displayTab.isNewActive = false;
            this.displayTab.isPopularActive = true;
        }
    }
};
</script>

<style scoped>
.st-border {
    border: 1px solid #667eea;
}

.is-tab-active {
    background-color: #667eea !important;
    color: #fff !important;
}
</style>

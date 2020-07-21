<template>
    <div class="flex justify-center w-screen text-gray-600">
        <form class="flex w-4/5 rounded-full">
            <input
                :value="value"
                @input="$emit('input', $event.target.value)"
                type="text"
                class="w-5/6 py-2 px-3
            rounded-l-full focus:outline-none"
                :class="bgColor ? bgColor : 'bg-gray-200'"
                :placeholder="placeholder ? placeholder : ''"
            />
            <button
                @click.prevent="$emit('search')"
                class="w-1/6 bg-blue-300 rounded-r-full focus:outline-none outline-none"
            >
                <FontAwesomeIcon icon="search" class="text-1xl" />
            </button>
        </form>
    </div>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faSearch } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faSearch);

export default {
    name: "BaseSearchBox",
    components: {
        FontAwesomeIcon
    },
    props: {
        value: {
            type: String
        },
        placeholder: {
            type: String,
            required: true
        },
        bgColor: {
            type: String
        }
    }
};
</script>

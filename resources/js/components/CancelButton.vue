<template>
    <span @click="$emit('click-btn')" class="absolute top-0 right-0 mr-2 mt-2">
        <FontAwesomeIcon icon="times-circle" class="text-4xl text-blue-500" />
    </span>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faTimesCircle } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faTimesCircle);

export default {
    name: "CancelButton",
    components: {
        FontAwesomeIcon
    }
};
</script>

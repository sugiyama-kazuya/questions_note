<template>
    <div>
        <p
            v-for="message in errorMessages"
            :key="message"
            class="text-red-500 text-xs italic"
        >
            {{ message }}
        </p>
    </div>
</template>

<script>
export default {
    name: "FormValidationErrorMessage",
    props: {
        errorMessages: {
            type: Array,
            required: true
        }
    }
};
</script>

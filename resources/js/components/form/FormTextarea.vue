<template>
    <textarea
        @input="$emit('input', $event.target.value)"
        :rows="rows ? rows : 6"
        :cols="cols ? cols : 40"
        :placeholder="placeholder"
        :value="value"
        class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
    ></textarea>
</template>

<script>
export default {
    name: "FormTextarea",
    props: {
        value: {
            type: String
        },
        placeholder: {
            type: String
        },
        rows: {
            type: Number
        },
        cols: {
            type: Number
        }
    }
};
</script>

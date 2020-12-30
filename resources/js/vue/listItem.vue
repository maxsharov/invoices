<template>
    <div class="item">
        {{ item.name }}
        <button class="">Send Email</button>
    </div>
</template>

<script>
export default {
    props: ["item"]
};
</script>

<style scoped>
button {
    border: 1px solid black;
}
</style>

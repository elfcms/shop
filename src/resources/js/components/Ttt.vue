<template>
    <div>
        <button @click="addProduct">+</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            product: 1,
        }
    },
    methods: {
        addProduct() {
            this.product++;
            this.$emit('addnum',this.product);
        }
    }
}
</script>

<style scoped>

</style>

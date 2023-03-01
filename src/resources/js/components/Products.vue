<template>
    <div v-for="product in products" :key="product.id">
        {{ product.title }}
    </div>
</template>
<script>
export default {
    props: {
        products: Array
    }
}
</script>

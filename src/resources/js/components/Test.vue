<template>
    <div>
        <span>{{ productNum }}</span>
        <!-- <ttt @addnum="addProduct">+</ttt> -->
    </div>
</template>
<script>
//import Ttt from './Ttt.vue';
export default {
    /* components: {
        Ttt
    }, */
    props: {
        product: Number
    },
    data() {
        return {
            productNum: this.product,
        }
    },
    methods: {
        addProduct(num) {
            //this.productNum++;
            this.productNum = num;
        }
    },
    /* mounted() {
        this.product = props.product;
    } */
}
</script>

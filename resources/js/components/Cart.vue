<template>
    <div>

        <button type="button" class="btn" data-toggle="modal" data-target="#modal">
            <i class="fa fa-shopping-cart"
               style="font-size:20px;color:grey;"></i>
        </button>

        <div class="modal" id="modal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Your Cart</h5>
                    </div>

                    <div class="modal-body">
                        <div v-bind:class="cart" v-for="appointment in cart">
                            Appointment with {{appointment.doctor}} on {{appointment.date}}
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Remove</button>
                            <br/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="/api/checkout" class="btn btn-info" role="button">Checkout</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        name: 'Cart',
        data () {
            return {
                cart: [],
            }
        },
        mounted() {
            this.getCart();
        },
        methods: {
            getCart(){
                axios({ method: "GET", "url": "/api/cart" })
                    .then(result => {
                        console.log(result)
                        this.cart = result.data.data;
                    }, error => {
                        console.error(error);
                    })
                    .catch(error => {
                    console.log(error.response)
                })
            },
        }
    }
</script>

<style scoped>
    h1, h2 {
        font-weight: normal;
    }
    ul {
        list-style-type: none;
        padding: 0;
    }
    li {
        display: inline-block;
        margin: 0 10px;
    }
    a {
        color: #42b983;
    }
    textarea {
        width: 600px;
        height: 200px;
    }
</style>

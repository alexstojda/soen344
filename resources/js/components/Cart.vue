<template>
    <div>
        <button type="button" class="btn" data-toggle="modal" data-target="#modal">
            <i class="fa fa-shopping-cart"
               style="font-size:20px;color:grey;"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Your Cart</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-bind:class="cart" v-for="appointment in cart" :key="appointment.id">
                            Appointment with {{appointment.doctor}} on {{appointment.date}}
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Remove</button>
                            <br/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="/api/checkout" method="get">
                            <button type="submit" class="btn btn-success">Checkout</button>
                        </form>
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

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
                        <table>
                            <thead>
                            <tr>
                                <th scope="col">Patient</th>
                                <th scope="col">Doctor</th>
                                <th scope="col">Date and Time</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="appointment in cart" :key="appointment.id">
                                    <td>{{ appointment.patient["name"] }}</td>
                                    <td>{{ appointment.doctor["name"] }}</td>
                                    <td>{{ dateFormatter(appointment.start) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-secondary" v-on:click="removeFromCart(appointment)">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="empty">
                            Empty Cart
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" @click="getCart">Refresh</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a class="btn btn-success" href="/checkout" type="button">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import moment from "moment";
    export default {
        name: 'Cart',
        data () {
            return {
                cart: [],
                empty: false,
            }
        },
        props: {
            userId: Number,
        },
        mounted() {
            this.getCart();
        },
        methods: {
            getCart(){
                axios({ method: "GET", "url": "/cart/" + this.userId })
                    .then(result => {
                        console.log(result)
                        this.cart = result.data.data;
                        if(this.cart.length == 0)
                        {
                            this.empty = true;
                        }
                        else
                        {
                            this.empty = false;
                        }
                        console.log(this.cart);
                    }, error => {
                        console.error(error);
                    })
                    .catch(error => {
                    console.log(error.response)
                })
            },
            dateTimeFormatter: function(date) {
                return moment(date).format('YYYY-MM-DD HH:mm:ss');
            },
            dateFormatter: function(date) {
                return moment(date).format('MMMM Do YYYY, HH:mm:ss');
            },
            removeFromCart: function(appointment) {
                console.log(appointment);
                axios.delete('/api/appointment/' + appointment.id).then(response => {
                    if(response.status === 200) {
                        console.log("Removed appointment from the cart");
                        if(this.cart.length == 0)
                        {
                            this.empty = true;
                        }
                        else
                        {
                            this.empty = false;
                        }
                    } else {
                        console.log(appointment);
                        console.log("Removing appointment from cart failed: " + response.status)
                    }
                })
            }
        }
    }
</script>

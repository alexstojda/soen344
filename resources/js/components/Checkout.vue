<template>
    <div>
        <div class="container padded">
            <h1 class="mt-5">Checkout</h1>
            <br>
            <table width="50%" class="table">
                <thead>
                <tr>
                    <th scope="col">Appointment Time</th>
                    <th scope="col">Doctor</th>
                    <th scope="col">Location</th>
                </tr>
                </thead>
                <tr v-for="cartItem in cart" v-bind:cart-line="cartItem">
                    <td>{{ dateFormatter(cartItem.start) }}</td>
                    <td>{{ cartItem.doctor['name'] }}</td>
                    <td>Room #{{ cartItem.room['id'] }}</td>
                </tr>
            </table>
            <div v-if="empty">
                Empty Cart
            </div>
            <br/>
            <br/>
            <div class="container">
                <div class="form-group row">
                    <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Credit Card</label>
                    <div class="col-sm-9 col-md-7 col-lg-8">
                        <input type="text" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">Expiry Date</label>
                    <div class="col-sm-9 col-md-7 col-lg-8">
                        <input type="text" class="form-control" placeholder="MM/YYYY">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 offset-md-1 col-md-3 col-lg-2 col-form-label">CVV</label>
                    <div class="col-sm-9 col-md-7 col-lg-8">
                        <input type="text" class="form-control" placeholder="XXX">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-10 offset-lg-1 col-md-10 offset-md-1 text-right">
                        <button v-on:click="checkoutCart()" type="button" class="btn btn-success btn-lg">Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import moment from "moment";

    export default {
        name: 'Checkout',
        data() {
            return {
                cart: [],
                empty: false,
            }
        },
        props: {
            userId: Number,
        },
        mounted() {
            this.getCart()
        },
        methods: {
            getCart() {
                axios.get('/api/appointment?patient_id=' +
                    this.userId + '&status=cart'
                ).then(result => {
                    this.cart = result.data.data;
                    this.empty = this.cart.length === 0;
                }, error => {
                    console.error(error)
                })
                .catch(error => {
                    console.log(error.response)
                })
            },
            checkoutCart() {
                for (let i = 0; i < this.cart.length; i++) {
                    let doBreak = false;
                    axios.put('/api/appointment/' + this.cart[i].id, {
                        paid: true,
                    })
                        .then(response => {
                            console.log(response);
                            if (response.status === 200) {
                                delete this.cart[i];
                            } else {
                                doBreak = true;
                                console.log("Add appointments failed: Response code " + response.status);
                            }
                        }).catch(error => {
                        console.log(error.response)
                    });
                    if (doBreak) break;
                }
                this.cart = [];
                // window.location = '/home';
            },
            dateFormatter: function (date) {
                return moment(date).format("YYYY-MM-DD");
            }
        }
    }
</script>

<style scoped>
</style>

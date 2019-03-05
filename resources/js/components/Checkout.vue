<template>
    <div>
        <div class="container padded">
            <h1 class="mt-5">Checkout</h1>
            <br>
            <br>
            <table width="50%">
                <tr>
                    <th>
                        Appointment Time
                    </th>
                    <th>
                        Doctor
                    </th>
                    <th>
                        Location
                    </th>
                </tr>
                <cart-line v-for="cartItem in cart" v-bind:cart-line="cartItem" :key="cartItem.id"></cart-line>
            </table>
            <br/>
            <br/>
                <div class="input">
                    Credit Card:
                    <input placeholder="XXXX-XXXX-XXXX-XXXX">
                </div>
            <br/>
                <div class="input">
                    CVV:
                    <input placeholder="XXX">
                </div>
            <br/>
                <div class="input">
                    MM/YYYY
                    <input placeholder="MM/YYYY">
                </div>
            <br>
                <div class="btn btn-primary">
                    <button v-on:click="checkoutCart()">Checkout</button>
                </div>
        </div>
    </div>
</template>

<script>
    import * as axios from 'axios'
    export default {
        name: 'Checkout',
        data () {
            return {
                cart: []
            }
        },
        mounted () {
            this.getCart()
        },
        methods: {
            getCart () {
                axios('/api/cart').then(result => {
                    this.cart = result.data.data
                }, error => {
                    console.error(error)
                })
            },
            checkoutCart () {
                axios('/api/processAppointments',{
                   cart: this.cart
            }).then(response => {
        if(response.status == 200) {
            console.log("Added appointment")
        } else {
            console.log("Add appointment failed: Response code " + response.status)
        }
    })
            }
        }
    }
</script>

<style scoped>
</style>

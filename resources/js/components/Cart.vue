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
                                <th scope="col">Doctor ID</th>
                                <th scope="col">Date and Time</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="appointment in cart" :key="appointment.id">
                                    <td>{{ appointment.doctor_id }}</td>
                                    <td>{{ dateFormatter(appointment.start) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-secondary" v-on:click="removeFromCart(appointment)">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a class="nav-link btn btn-success" href="/checkout"  type="button">Checkout</a>
                            <!--v-on:click="getCheckout()"-->
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
            }
        },
        // props: {
        //     userType: String,
        // },
        mounted() {
            this.getCart();
        },
        methods: {
            getCart(){
                axios({ method: "GET", "url": "/api/cart" })
                    .then(result => {
                        console.log(result)
                        this.cart = result.data.data;
                        console.log(this.cart);
                    }, error => {
                        console.error(error);
                    })
                    .catch(error => {
                    console.log(error.response)
                })
            },
            getCheckout(){
                axios({method: "GET", "url": "/checkout"})
                    .then(result => {
                        console.log(result)
                        this.cart = result.data.data;
                        console.log(this.cart);
                    }, error => {
                        console.error(error);
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
                // if(this.userType.equals("web")) {
                //     axios({method: "GET", "url": "/checkout"})
                //         .then(result => {
                //             console.log(result)
                //             this.cart = result.data.data;
                //             console.log(this.cart);
                //         }, error => {
                //             console.error(error);
                //         })
                //         .catch(error => {
                //             console.log(error.response)
                //         })
                // }
                // else if(this.userType.equals("doctor"))
                // {
                //     axios({method: "GET", "url": "/doctor/checkout"})
                //         .then(result => {
                //             console.log(result)
                //             this.cart = result.data.data;
                //             console.log(this.cart);
                //         }, error => {
                //             console.error(error);
                //         })
                //         .catch(error => {
                //             console.log(error.response)
                //         })
                // }
                // else if(this.userType.equals("nurse"))
                // {
                //     axios({method: "GET", "url": "/nurse/checkout"})
                //         .then(result => {
                //             console.log(result)
                //             this.cart = result.data.data;
                //             console.log(this.cart);
                //         }, error => {
                //             console.error(error);
                //         })
                //         .catch(error => {
                //             console.log(error.response)
                //         })
                // }
            },
            dateTimeFormatter: function(date) {
                return moment(date).format('YYYY-MM-DD HH:mm:ss');
            },
            dateFormatter: function(date) {
                return moment(date).format('MMMM Do YYYY, HH:mm:ss');
            },
            removeFromCart: function(appointment) {
                console.log(appointment);
                axios.post('/api/removeFromCart', {
                    patient_id: appointment.patient_id,
                    doctor_id: appointment.doctor_id,
                    start: this.dateTimeFormatter(appointment.start)
                }).then(response => {
                    if(response.status === 200) {
                        console.log("Removed appointment from the cart")
                    } else {
                        console.log(appointment);
                        console.log("Removing appointment from cart failed: " + response.status)
                    }
                })
            }
        }
    }
</script>

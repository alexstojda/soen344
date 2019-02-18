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
              <div v-for="appointment in cart">
                 Appointment with {{appointment.doctor}} on {{appointment.date}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Remove</button>
              <br/>
              </div>
          </div>
          <div class="modal-footer">
            <form action="addToCart" method="post" v-on:submit="onSubmit">
              <input type="hidden" value="Joe" name="patient" v-model="addItemForm.patient">
              <input type="hidden" value="Dr.Pepper" name="doctor" v-model="addItemForm.doctor">
              <input type="hidden" value="Today" name="date" v-model="addItemForm.date">
              <button type="submit" class="btn btn-secondary">Add Item</button>
            </form>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Checkout</button>
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
                addItemForm: {
                  patient: '',
                  doctor: '',
                  date: '',
                },
            }
        },
        mounted() {
            this.getCart();
         },
         methods: {
          getCart(){
            const path = 'http://127.0.0.1:5000/getCart';
            axios({ method: "GET", "url": path }).then(result => {
              console.log(result)
              this.cart = result.data;
            }, error => {
              console.error(error);
            });
          },
          addItem(cart)
           {
             const path = 'http://127.0.0.1:5000/addToCart';
             axios.post(path, cart)
               .then(() => {
                 this.getCart();
               })
               .catch((error) => {
                 // eslint-disable-next-line
                 console.log(error);
                 this.getCart();
               });
           },
           onSubmit(evt) {
             evt.preventDefault();
             const cartItem = {
               title: this.addItemForm.patient,
               author: this.addItemForm.doctor,
               date: this.addItemForm.date, // property shorthand
             };
             this.addItem(cartItem);
             this.initForm();
           },
            initForm() {
              this.addItemForm.patient = '';
              this.addItemForm.doctor = '';
              this.addItemForm.date = '';
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

<template>
     <div class="register">
        <h2>Register</h2>
         <form action='registerUser' method='POST'>
                <input type='text' name='code' id='code' placeholder='code'/>
                <input type='password' name='password' id='password' placeholder='password'/>
                <input type="hidden" name="person" value="patient">
                <input type='submit' name='register'/>
         </form>
    </div>
</template>

<script>

  import axios from "axios";

    export default {
        name: 'Register',
        data () {
          return {
            code: '',
            password: '',
            submitted: false
          }
        },
        mounted() {
            axios({ method: "POST", "url": "http://127.0.0.1:5000/registerUser" }).then(result => {
                console.log(result)
                this.appointments = result.data;
            }, error => {
                console.error(error);
            });
         }
    }
</script>

<style scoped>

</style>

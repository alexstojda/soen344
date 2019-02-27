<template>
    <div class="log-in">
        <h2>Login</h2>
         <form action='login' method='POST'>
                <input type='text' name='code' id='code' placeholder='code'/>
                <input type='password' name='password' id='password' placeholder='password'/>
                <input type="hidden" value="doctor">
                <input type='submit' name='submit'/>
         </form>
      <form action='getUsers' method='POST'>
                <input type='submit' name='submit'/>
         </form>
    </div>
</template>

<script>
     import axios from "axios";

    export default {
        name: 'Login',
        data () {
          return {
            code: '',
            password: '',
            submitted: false
          }
        },
        mounted() {
            axios({ method: "POST", "url": "http://127.0.0.1:5000/getUsers" }).then(result => {
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

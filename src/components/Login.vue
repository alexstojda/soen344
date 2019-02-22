<template>
    <div class="log-in">
        <h2>Login</h2>
         <form action='login' method='POST'>
                <input type='text' name='code' id='code' placeholder='code'/>
                <input type='password' name='password' id='password' placeholder='password'/>
                <input type="hidden" value="doctor">
                <input type='submit' name='submit'/>
         </form>
    </div>
</template>

<script>
    // import axios from "axios";

    export default {
        name: 'Login',
        data () {
          //   return {
          // code: '',
          // password:'',
          // submitted: false
        }
    }

</script>
<style scoped>

</style>

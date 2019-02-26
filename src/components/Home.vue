<template>
  <div>
    <div>
      <navbar></navbar>
    </div>
    <div>
      <p>Home page</p>
      <br/>
      <!--<helloworld></helloworld>-->
      <login></login>
      <register></register>
      <router-link to="/notHome">Navigate to Page2</router-link>
    </div>
  </div>
</template>

<script>
    import Navbar from './Navbar.vue';
    import HelloWorld from './HelloWorld.vue';
    import Login from './Login.vue';
    import Register from './Register.vue'
    export default {
        components: {
            'navbar': Navbar,
            'helloworld': HelloWorld,
            'login': Login,
            'register': Register
        }
    };
</script>

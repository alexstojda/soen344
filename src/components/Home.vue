<template>
  <div>
    <div>
      <navbar></navbar>
    </div>
    <div>
      <p>Home page</p>
      <br/>
      <helloworld></helloworld>
      <login></login>
    </div>
  </div>
</template>

<script>
    import Navbar from './Navbar.vue';
    import HelloWorld from './HelloWorld.vue';
    import Login from './Login.vue';
    export default {
        components: {
            'navbar': Navbar,
            'helloworld': HelloWorld,
            'login': Login
        }
    };
</script>

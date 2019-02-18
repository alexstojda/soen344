<template>
  <div>
    <div>
      <navbar></navbar>
    </div>
    <div>
      <p>Home page</p>
      <br/>
      <helloworld><</helloworld>
    </div>
  </div>
</template>

<script>
    import Navbar from './Navbar.vue';
    import HelloWorld from './HelloWorld.vue';
    export default {
        components: {
            'navbar': Navbar,
            'helloworld': HelloWorld
        }
    };
</script>

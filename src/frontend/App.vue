<template>
  <div>
  <Contact_part1/>
  <Contact_part2/>
  </div>
</template>

<script>
import Contact_part1 from "frontend/components/parts/Contact_part1.vue";
import Contact_part2 from "frontend/components/parts/Contact_part2.vue";

export default {
  name: 'App',
  components: {
    Contact_part1,
    Contact_part2
  }

}
</script>
<style>

</style>

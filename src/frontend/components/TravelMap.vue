<template>
  <GoogleMapLoader
      :mapConfig="mapConfig"
      :apiKey="mapKey"
  >
    // insert your google maps api key to render styled map
    <template slot-scope="{ google, map }">
    </template>
  </GoogleMapLoader>
</template>

<script>
import GoogleMapLoader from "./GoogleMapLoader.vue";
import Params_Object from "frontend/components/parts/params";
export default {
  name: 'TravelMap',

  components: {
    GoogleMapLoader,
  },

  computed: {
    mapConfig() {
      return {
        center: this.mapCenter
      };
    },
    mapKey() {
      return Params_Object.apikey
    }
  }
};
</script>

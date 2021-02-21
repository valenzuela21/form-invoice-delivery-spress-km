<template>
    <div class="container-delivery" >
        <img class="image-logo-thank" :src="logo" alt="image-domimerk" />
      <div class="context-thank" >
        <h3 class="txt-thank">Gracias por confiar tu paquete con nosotros muy pronto un
            mensajero de <span style="color:#087b0f"> Dommimerk </span> recogera su paquete.
        </h3>
        <section class="buttons">
            <b-button class="btn-submit" @click="goBackForm" expanded>Realizar Pedido</b-button>
        </section>
      </div>
    </div>
</template>

<script>
    import Params_Object from "frontend/components/parts/params";

    export default {
        name: "Thank",
        methods:{
            goBackForm(){
                location.reload();
            }
        },
        computed:{
            logo:() => {
                return Params_Object.logo;
            }
        }
    }
</script>

<style scoped>

</style>
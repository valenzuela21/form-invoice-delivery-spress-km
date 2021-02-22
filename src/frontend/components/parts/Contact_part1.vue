<template>
    <div v-show="!success" id="app" class="form-delivery container">
        <section class="container-delivery">
            <div class="row">
                <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                  <div class="subtitle-yellow"> Información de Origen</div>
                    <section class="container-form">
                        <b-field label="Municipio">
                            <b-select v-model="cityorigen"
                                      type="is-success"
                                      expanded>
                                <option value="">Seleccione el municipio</option>
                                <option
                                        v-for="(option, index) in data_city"
                                        :value="{
                                            city: option.name_municipality,
                                            price_max: option.pressing_max_send,
                                            price_min: option.pressing_min_send,
                                            size_cover: option.size_cover
                                        }"
                                        :key="index"
                                >
                                    {{ option.name_municipality }}
                                </option>
                            </b-select>
                        </b-field>
                    </section>
                    <section class="container-form">
                        <b-field label="Dirección">
                            <b-input v-model="address1"
                                     placeholder="Ejemplo: Calle 100 # 45 - 80"
                                     expanded
                            ></b-input>
                        </b-field>
                    </section>
                    <div class="subtitle-green"> Información de Destino</div>
                    <section class="container-form">
                        <b-field label="Municipio">
                            <b-select v-model="citydestiny" expanded>
                                <option value="">Seleccione el municipio</option>
                                <option
                                        v-for="(option, index) in data_city"
                                        :value="{
                                            city: option.name_municipality,
                                            price_max: option.pressing_max_send,
                                            price_min: option.pressing_min_send,
                                            size_cover: option.size_cover
                                        }"
                                        :key="index">
                                    {{ option.name_municipality }}
                                </option>
                            </b-select>
                        </b-field>
                    </section>
                    <section class="container-form">
                        <b-field label="Dirección">
                            <b-input v-model="address2"
                                     placeholder="Ejemplo: Cra 47 # 10 - 10"
                                     expanded></b-input>
                        </b-field>
                    </section>
                    <section class="container-form">
                        <b-field>
                            <b-switch v-model="isSwitchedCustom"
                                      type="is-success"
                                      size="is-medium"
                                      true-value="Yes"
                                      false-value="No">
                                <div v-if="isSwitchedCustom == 'Yes'">
                                    <p class="txt-swicth">Deseo que este paquete sea ida y vuelta.</p>
                                </div>
                                <div v-else>
                                    <p class="txt-swicth">No deseo que este paquete tenga ida y vuelta.</p>
                                </div>
                            </b-switch>
                        </b-field>
                    </section>
                    <section class="buttons">
                        <b-button class="btn-submit" @click="submitCotiza" expanded>Cotizar Envio</b-button>
                    </section>
                </div>
                <div class="col-sm-12 col-md-7 col-lg-7 col-xl-7">
                    <img class="image-delivery" src='./../../images/delivery.png' alt="delivery-espress"/>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import "@/frontend/components/puente";
    import {puente} from "frontend/components/puente";
    import Swal from 'sweetalert2'
    import Params_Object from '@/frontend/components/parts/params';

    const data_city = Params_Object.municipaly;

    export default {
        name: "Contact_part1",
        data() {
            return {
                data_city,
                isSwitched: false,
            }
        },

        methods: {
            submitCotiza() {

                let address1 = this.$store.state.address_origen;
                let city1 = this.$store.state.city_origen.city;
                let address2 = this.$store.state.address_destiny;
                let city2 = this.$store.state.city_destiny.city;

                if(city1 === undefined || city1 === ''){
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa la ciudad de origen',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }else if(address1 === undefined || address1 === ''){
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa la dirección origen',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }else if(city2 === undefined || city2 === ''){
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa la ciudad de destino',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }else if(address2 === undefined || address2 === ''){
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa la dirección de destino',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return;
                }else{
                        this.$store.commit('GET_SUCCESS_FULLY', true);
                        puente.$emit('event-submit');
                }
            },

        },

        computed: {
            ...mapState(['success']),

            address1: {
                get() {
                    return this.$store.state.address_origen;
                },
                set(value) {
                    this.$store.commit('GET_ADDRESS_ORIGEN', value)
                }
            },
            address2: {
                get() {
                    return this.$store.state.address_destiny;
                },
                set(value) {
                    this.$store.commit('GET_ADDRESS_DESTINY', value)
                }
            },
            cityorigen: {
                get(){
                    return this.$store.state.city_origen;
                },
                set(value) {
                    this.$store.commit('GET_CITY_ORIGEN', value)
                }
            },
            citydestiny: {
                get() {
                    return this.$store.state.city_destiny;
                },
                set(value) {
                    this.$store.commit('GET_CITY_DESTINY', value)
                }
            },
            isSwitchedCustom:{
                get() {
                    return this.$store.state.reverse;
                },
                set(value) {
                    this.$store.commit('GET_REVERSE', value)
                }
            }
        },



    }

</script>

<style scoped>
    @import '~frontend/components/parts/style.css';
</style>
<template>
    <div v-show="success" id="app" class="form-delivery container">
        <section class="container-delivery">
            <div v-show="loader" class="loader-delivery">
                <div class="loader-general">
                    <div class="lds-ripple">
                        <div></div>
                        <div></div>
                    </div>
                    <p>Procesando...</p>
                </div>
            </div>
            <div v-if="!this.thank">
                <div class="row">
                    <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <img class="img-service" src="./../../images/distance.png" alt="distance"/>
                                <p class="txt-service">Distancia</p>
                                <div v-if="results.length <= 0 || results == undefined">
                                    <h3 class="txt-price-km">0 Km</h3>
                                </div>
                                <div v-else>
                                    <h3 class="txt-price-km">{{`${results[0]} Km`}}</h3>
                                    <p class="txt-service">Tiempo: {{results[2]}}</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <img class="img-service" src="./../../images/value.png" alt="dinner"/>
                                <p class="txt-service">Valor del servicio</p>
                                <div v-if="results.length <= 0 || results == undefined">
                                    <h3 class="txt-price-km">$ 0</h3>
                                </div>
                                <div v-else>
                                    <h3 class="txt-price-km">
                                        {{formatCurrency("es-CO", "COP", 0, results[1])}}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <section class="container-form">
                            <b-field label="Nombre y Apellido">
                                <b-input
                                        v-model="name"
                                        placeholder="Ingresa el correo electrónico"
                                ></b-input>
                            </b-field>
                        </section>
                        <section class="container-form">
                            <b-field label="Correo Electrónico">
                                <b-input type="email"
                                         v-model="email"
                                         placeholder="Ingresa el correo electrónico">
                                </b-input>
                            </b-field>
                        </section>
                        <section class="container-form">
                            <b-field label="Número Celular">
                                <b-input type="text"
                                         v-model="phone"
                                         placeholder="Ingresa el número celular">
                                </b-input>
                            </b-field>
                        </section>
                        <section class="container-form">
                            <b-field label="Ingresa el paquete">
                                <b-input type="textarea"
                                         v-model="comment"
                                         placeholder="Ingresa paquete que vas ha enviar">
                                </b-input>
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
                                        <p class="txt-swicth">Deseo enviar esta cotización por whatsapp.</p>
                                    </div>
                                    <div v-else>
                                        <p class="txt-swicth">No deseo enviar a whatsapp.</p>
                                    </div>
                                </b-switch>
                            </b-field>
                        </section>
                        <section class="buttons">
                            <b-button class="btn-submit" @click="submitDelivery" expanded>Enviar Pedido</b-button>
                        </section>
                    </div>
                    <div class="col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <TravelMap class="travel-map"/>
                    </div>
                </div>
            </div>
            <div v-else>
                <Thank/>
            </div>
        </section>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import Swal from 'sweetalert2';
    import axios from 'axios';

    import TravelMap from "frontend/components/TravelMap.vue";
    import Thank from "frontend/components/parts/Thank.vue";
    import {puente} from "frontend/components/puente";

    import Params_Object from '@/frontend/components/parts/params';

    export default {
        name: "Contact_part2",
        components: {
            TravelMap,
            Thank
        },
        data() {
            return {
                name: '',
                email: '',
                phone: '',
                comment: '',
                isSwitchedCustom: 'Yes',
                thank: false,
            }
        },

        created() {
            puente.$on('event-submit', () => {

                let validate = new Promise((resolve, reject)=>{
                    if(isNaN(this.results[0]) || isNaN(this.results[1])){
                        reject("Error")
                    }else{
                        resolve("Ok")
                    }
                })

                validate.then(
                    (value)=>{
                        this.$store.commit('GET_SUCCESS_FULLY', false);
                        this.$store.dispatch('LOADER_ACTION');
                    },
                    (error)=>{
                        this.$store.dispatch('LOADER_ACTION');
                    }
                )
            });

        },

        methods: {
            formatCurrency: (locales, currency, fractionDigits, number) => {
                let formatted = new Intl.NumberFormat(locales, {
                    style: 'currency',
                    currency: currency,
                    minimumFractionDigits: fractionDigits
                }).format(number);
                return formatted;
            },
            submitDelivery: function (e) {
                e.preventDefault();
                if (!this.name) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa el nombre del titular',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                } else if (!this.email) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa el correo electrónico',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                }else if (this.phone.length < 10) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa corretamente el número celular o movil',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })

                } else if (!this.phone) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Ingresa el número celular o movil',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                } else {

                    axios.post(Params_Object.url,{
                        name: this.name,
                        email: this.email,
                        phone: this.phone,
                        comment: this.comment,
                        address_source: this.address_origen,
                        address_destiny: this.address_destiny,
                        city_source: this.city_origen,
                        city_destiny: this.city_destiny,
                        distance: this.results[0],
                        total: this.results[1]
                    })
                    .then((res)=>{
                        if(this.isSwitchedCustom === 'Yes'){
                            let mobile = Params_Object.mobil
                            const LinkWhatsapp = `https://api.whatsapp.com/send?phone=57${mobile}&text=Nuevo%20pedido%20de%20mensajeria%20Nombre:${this.name}%20Telefono:${this.phone}%20Email:${this.email}%20Direcion%20origen:${this.address_origen}%20Direccion destino:${this.address_destiny}%20Costo:${this.results[1]}%20Distancia:${this.results[0]}`;
                            window.open(LinkWhatsapp, '_blank');
                        }
                        console.log("Se ha enviado correctamente");
                        this.thank = true;
                    })
                    .catch((error)=>{
                        console.log(`Error: ${error}`)
                    })
                }
            }
        },


        computed: {
            ...mapState(['results', 'success', 'loader', 'address_origen', 'address_destiny', 'city_origen', 'city_destiny'])
        }

    }
</script>

<style scoped>

</style>

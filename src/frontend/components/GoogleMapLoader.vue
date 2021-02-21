<template>
    <div>
        <div
                class="google-map"
                ref="googleMap"
        ></div>
        <template v-if="Boolean(this.google) && Boolean(this.map)">
            <slot
                    :google="google"
                    :map="map"
            />
        </template>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import GoogleMapsApiLoader from "google-maps-api-loader";
    import {puente} from "frontend/components/puente";
    import {asyncScheduler}from "rxjs";

    export default {
        props: {
            mapConfig: Object,
            apiKey: String
        },

        data() {
            return {
                google: null,
                map: null,
                geocoder: null,
                coordenate: [],
                result: [],
                servicedistance: []
            };
        },

        async mounted() {
            const googleMapApi = await GoogleMapsApiLoader({
                apiKey: this.apiKey
            });
            this.google = googleMapApi;

            this.initializeMap();
        },

        created() {
            puente.$on('event-submit', () => {
                this.geocoder = new this.google.maps.Geocoder();
                this.geocodeAddress(this.geocoder, this.map);
            });
        },


        methods: {
            initializeMap() {
                const mapContainer = this.$refs.googleMap;
                this.map = new this.google.maps.Map(mapContainer, {
                    zoom: this.zoom,
                    scaleControl: false,
                    mapTypeControl: false,
                    fullscreenControl: false,
                    center: {lat: 4.7102383, lng: -74.096191},
                });
            },

          geocodeAddress(geocoder) {
                this.initializeMap();
                const txtAddress1 = `${this.address_origen} ${this.city_origen.city}`;
                const txtAddress2 = `${this.address_destiny} ${this.city_destiny.city}`;

                const adressArray = [txtAddress1, txtAddress2];

                adressArray.forEach(result => {
                    this.generateLngLta(geocoder, result);
                })

                 const calculate_km = () => {
                    this.calculateDistance(this.coordenate[1], this.coordenate[2], this.coordenate[4], this.coordenate[5], this.city_origen, this.city_destiny);
                    console.log("Calculate distance");
                 }
                 const start_markets = () => {
                     this.generateMarker(this.coordenate);
                     console.log("Start Markers");
                 }
                 const sendData = () => {
                     console.log("Send Data!");
                     this.$store.commit('GET_TOTAL_VALUE', this.servicedistance);
                     this.$store.commit('GET_LOADER', false);
                     this.coordenate = [];
                 }

                 asyncScheduler.schedule(calculate_km, 3000);
                 asyncScheduler.schedule(start_markets, 4000);
                 asyncScheduler.schedule(sendData, 5000);

            },

            generateLngLta(geocoder, txtAddress) {
                geocoder.geocode({address: txtAddress}, (results, status) => {
                    if (status === "OK") {
                        this.map.setCenter(results[0].geometry.location);
                        let lat_coordenate = parseFloat(results[0].geometry.location.lat());
                        let lng_coordenate = parseFloat(results[0].geometry.location.lng());

                        this.coordenate.push(txtAddress, lat_coordenate, lng_coordenate);

                    } else {
                        alert("Error en el rastreo de la dirección vuelva a intentarlo. ");
                    }
                });
            },
            calculateDistance(lat1, lon1, lat2, lon2, city_source, city_destiny) {
                const origin1 = {lat: lat1, lng: lon1};
                const origin2 = this.address_origen;
                const destinationA = this.address_destiny;
                const destinationB = {lat: lat2, lng: lon2};

                const service = new this.google.maps.DistanceMatrixService()

                service.getDistanceMatrix({

                    origins: [origin1, origin2],
                    destinations: [destinationA, destinationB],
                    travelMode: this.google.maps.TravelMode.DRIVING,
                    unitSystem: this.google.maps.UnitSystem.METRIC,
                    avoidHighways: false,
                    avoidTolls: false,

                }, (response, status) => {
                    if (status !== "OK") {
                        alert("Error en el calculo del km");
                    } else {
                        let distance = response.rows[0].elements[1].distance;
                        let duration = response.rows[0].elements[1].duration.text;

                        //[distance, duration];

                        let str = distance.text;
                        let res = str.split(' ');
                            res = parseFloat(res[0]);

                        let total_km = Math.ceil(res);

                        let price_max = city_destiny.price_max;
                        let price_min = city_destiny.price_min;
                        let size_cover = city_destiny.size_cover;

                        let price, total_result;

                        if (total_km <= size_cover) {
                            price = price_min;
                            total_result = price
                        } else {
                            price = price_max
                            total_result = total_km * price;
                        }

                        if (this.reverse === 'Yes') {
                            total_km = total_km * 2;
                            total_result = total_result * 2;
                        }

                        this.servicedistance = [total_km, total_result, duration];
                    }
                });

            },

            generateMarker(data_coordenate) {
                const beaches = [
                    [data_coordenate[0], data_coordenate[1], data_coordenate[2], 2],
                    [data_coordenate[3], data_coordenate[4], data_coordenate[5], 1]
                ];

                const image = {
                    url: "https://domimerkespress.com/wp-content/plugins/form-domimerk-delivery-espress/src/frontend/images/icon_map.png",
                    // This marker is 20 pixels wide by 32 pixels high.
                    size: new google.maps.Size(79, 79),
                    // The origin for this image is (0, 0).
                    origin: new google.maps.Point(0, 0),
                    // The anchor for this image is the base of the flagpole at (0, 32).
                    anchor: new google.maps.Point(0, 32),
                };

                for (let i = 0; i < beaches.length; i++) {
                    const beach = beaches[i];
                    new this.google.maps.Marker({
                        position: {lat: beach[1], lng: beach[2]},
                        map: this.map,
                        icon: image,
                        title: beach[0],
                        zIndex: beach[3],
                    });
                }
            }

        },

        computed: {
            ...mapState(['address_origen', 'city_origen', 'address_destiny', 'city_destiny', 'zoom', 'reverse'])

        }

    };
</script>

<style scoped>
    .google-map {
        width: 100%;
        min-height: 100%;
    }
</style>

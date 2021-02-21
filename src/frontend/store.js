import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default  new Vuex.Store({
    state:{
        address_origen: '',
        address_destiny: '',
        city_origen: '',
        city_destiny: '',
        reverse: 'No',
        zoom: 12,
        success: false,
        loader: false,
        results: [],
    },
    mutations:{
        GET_ADDRESS_ORIGEN (state, data){
            state.address_origen = data;
        },
        GET_ADDRESS_DESTINY(state, data){
            state.address_destiny = data;
        },
        GET_CITY_ORIGEN(state, data){
            state.city_origen = data;
        },
        GET_CITY_DESTINY(state, data){
            state.city_destiny = data;
        },
        GET_TOTAL_VALUE(state, data){
            state.results = data;
        },
        GET_SUCCESS_FULLY(state, data){
            state.success = data
        },
        GET_REVERSE(state, data){
            state.reverse = data
        },
        GET_LOADER(state, data){
            state.loader = data;
        }
    },
    actions:{
        LOADER_ACTION(context) {
            context.commit('GET_LOADER', true)
        }
    }
})
import Vue from 'vue'
import vmNav from '../components/vm-nav.vue'

let nav = new Vue({
    el: '.nav',
    data(){
        return{
            user: BLADE.user,
        }
    },
    components:{
        vmNav,
    }

});
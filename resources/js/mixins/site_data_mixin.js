import Vue from "vue";
import axios from 'axios';

export default {
    data(){
        return {
            global_data: []
        }
    },
    methods: {
        get_global_site_data() {
            axios
            .get("/siteData/get_site_locale_data/"+localStorage.getItem('lang'))
            .then((response) => (
                this.global_data = response
            ));
        }
    }
}
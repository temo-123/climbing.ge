<script>
export default {
    components: {
        // SlickItem,
    },
    props: ["route_grade"],
    data: function () {
        return {
            
            mtp_post_list: false,
        };
    },
    mounted() {
        // this.get_outdoor_routes();
    },

    watch: {
        $route(to, from) {
            // this.get_outdoor_routes();
        },
    },
    beforeDestroy() {
        document.removeEventListener("grade", "this.storageListener");
    },
    methods: {
        grade_chart(grade_fr) {
            var grad = "";
            if (localStorage.getItem("grade") == "yds") {
                if (grade_fr == "4") grad = "5.6";
                else if (grade_fr == "5a" || grade_fr == "5a+") grad = "5.7";
                else if (grade_fr == "5b" || grade_fr == "5b+") grad = "5.8";
                else if (grade_fr == "5c" || grade_fr == "5c+") grad = "5.9";
                else if (grade_fr == "6a") grad = "5.10a";
                else if (grade_fr == "6a+") grad = "5.10b";
                else if (grade_fr == "6b") grad = "5.10c";
                else if (grade_fr == "6b+") grad = "5.10d";
                else if (grade_fr == "6c") grad = "5.11a/5.11b";
                else if (grade_fr == "6c+") grad = "5.11c";
                else if (grade_fr == "7a") grad = "5.11d";
                else if (grade_fr == "7a+") grad = "5.12a";
                else if (grade_fr == "7b") grad = "5.12b";
                else if (grade_fr == "7b+") grad = "5.12c";
                else if (grade_fr == "7c") grad = "5.12d";
                else if (grade_fr == "7c+") grad = "5.13a";
                else if (grade_fr == "8a") grad = "5.13b";
                else if (grade_fr == "8a+") grad = "5.13c";
                else if (grade_fr == "8b") grad = "5.13d";
                else if (grade_fr == "8b+") grad = "5.14a";
                else if (grade_fr == "8c") grad = "5.14b";
                else if (grade_fr == "8c+") grad = "5.14c";
                else if (grade_fr == "9a") grad = "5.14d";
                else if (grade_fr == "9a+") grad = "5.15a";
                else if (grade_fr == "9b") grad = "5.15b";
                else if (grade_fr == "9b+") grad = "5.15c";
                else if (grade_fr == "9c") grad = "5.15d";
                else if (grade_fr == "9c+") grad = "5.16a";
                else grad = "?";
            } else if (localStorage.getItem("grade") == "UIAA") {
                if (grade_fr == "4") grad = "5.6";
                else if (grade_fr == "6a") grad = "VI+";
                else if (grade_fr == "6a+") grad = "VII-";
                else if (grade_fr == "6b") grad = "VII";
                else if (grade_fr == "6b+") grad = "VII+";
                else if (grade_fr == "6c") grad = "VII+/VIII-";
                else if (grade_fr == "6c+") grad = "VIII-";
                else if (grade_fr == "7a") grad = "VIII";
                else if (grade_fr == "7a+") grad = "VIII+";
                else if (grade_fr == "7b") grad = "VIII+/IX-";
                else if (grade_fr == "7b+") grad = "IX-";
                else if (grade_fr == "7c") grad = "IX";
                else if (grade_fr == "7c+") grad = "IX+";
                else if (grade_fr == "8a") grad = "IX+/X-";
                else if (grade_fr == "8a+") grad = "X-";
                else if (grade_fr == "8b") grad = "X";
                else if (grade_fr == "8b+") grad = "X+";
                else if (grade_fr == "8c") grad = "XI-";
                else if (grade_fr == "8c+") grad = "XI";
                else if (grade_fr == "9a") grad = "XI+";
                else if (grade_fr == "9a+") grad = "XII-";
                else if (grade_fr == "9b") grad = "XII";
                else grad = "?";
            }
            return grad;
        },
    },
};
</script>


<template>
    <div class="row">
        <hr class="footer_bottom_line">
        <p class="footer_bottom_text">© 2017-{{ active_year }} Climbing In Georgia. Project by Temo Samsonadze <a href="https://github.com/temo-123" target="_blank">(Github -> temo-123)</a></p>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                active_year: new Date().getFullYear(),
            };
        },
    }
</script>

<style>
    .footer_bottom_line {
        margin: 5% 5% 2%;
        border-color: #565656;
    }
    .footer_bottom_text{
        text-align: center;
        color: #878787;
    }
</style>
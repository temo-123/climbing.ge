<template>
    <div class="col-md-12">
        <div class="confirm_page_text">
            <h1>Orer is completed! </h1>
            <h2>Plis check your email (your_email@gmail.com) for confirm order!</h2>
            <p>Befor email confirming order is not socsesful</p>
        </div>

        <div class="confirm_page_back_button">
            <router-link :to="{name: 'cart'}" exact> 
                <button class="btn btn-primary " >{{ $t('user menu cart') }}</button>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>

<style>
    .confirm_page_text{
        text-align: center;
    }

    .confirm_page_back_button {
        text-align: center;
    }
</style>
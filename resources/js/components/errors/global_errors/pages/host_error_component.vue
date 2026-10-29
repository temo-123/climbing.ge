<template>
    <div class="container">
        <div class="cg-offroute">
            <div class="cg-offroute__scene">
                <!-- lost on the approach: the compass won't settle -->
                <cg-scene name="lost" />
            </div>

            <div class="cg-offroute__text">
                <span class="cg-eyebrow">climbing.ge</span>
                <h1 class="error-title">{{ $t("error.host_error.title") }}</h1>
                <p class="error-details">{{ $t("error.host_error.description") }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'HostError',
    }
</script>

<style>

</style>
<template>
    <aside class="cg-auth__aside">
        <span class="cg-eyebrow">climbing.ge</span>
        <h2 class="cg-auth__title">{{ $t('auth.aside.title') }}</h2>
        <p class="cg-auth__text">{{ $t('auth.aside.text') }}</p>
        <ul class="cg-auth__perks">
            <li><i class="fa fa-map-o" aria-hidden="true"></i> {{ $t('auth.aside.perk_guide') }}</li>
            <li><i class="fa fa-flag" aria-hidden="true"></i> {{ $t('auth.aside.perk_summit') }}</li>
            <li><i class="fa fa-shopping-bag" aria-hidden="true"></i> {{ $t('auth.aside.perk_shop') }}</li>
            <li><i class="fa fa-heartbeat" aria-hidden="true"></i> {{ $t('auth.aside.perk_training') }}</li>
        </ul>
    </aside>
</template>

<script>
    // Left panel of every auth page (login, register, password reset…).
    export default {
        name: 'AuthAside',
    }
</script>

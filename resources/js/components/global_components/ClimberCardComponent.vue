<template>
    <div class="climber-card" @click="$emit('open', user.id)">
        <img
            v-if="user.image"
            :src="'/public/images/user_profil_img/' + user.image"
            class="climber-card__avatar"
            alt="avatar"
        >
        <div v-else class="climber-card__avatar climber-card__avatar--placeholder">
            <i class="fa fa-user" aria-hidden="true"></i>
        </div>

        <div class="climber-card__name">{{ user.name }} {{ user.surname }}</div>
        <div class="climber-card__followers">
            <i class="fa fa-users" aria-hidden="true"></i> {{ user.followers_count }}
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            user: { type: Object, required: true },
        },
        emits: ['open'],
    };
</script>

<style scoped>
.climber-card {
    cursor: pointer;
    text-align: center;
    padding: 10px 6px;
    border-radius: 8px;
    transition: background .15s;
}
.climber-card:hover {
    background: #f5f5f5;
}
.climber-card__avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 6px;
}
.climber-card__avatar--placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee;
    font-size: 2.2rem;
    color: #aaa;
}
.climber-card__name {
    font-size: 1.7rem;
    font-weight: 600;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.climber-card__followers {
    font-size: 1.4rem;
    color: #888;
}
</style>

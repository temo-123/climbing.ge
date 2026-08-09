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

        <div class="climber-card__stats">
            <span class="climber-card__badge climber-card__badge--followers">
                <i class="fa fa-users" aria-hidden="true"></i> {{ user.followers_count || 0 }}
            </span>
            <span class="climber-card__badge climber-card__badge--points">
                <i class="fa fa-star" aria-hidden="true"></i> {{ user.points_total || 0 }}
            </span>
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
    padding: 16px 10px;
    border-radius: 12px;
    background: #fff;
    border: 1px solid #ececec;
    transition: box-shadow .15s, transform .15s, border-color .15s;
}
.climber-card:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
    border-color: #dcdcdc;
    transform: translateY(-2px);
}
.climber-card__avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 10px;
    border: 2px solid #f0f0f0;
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
    margin-bottom: 8px;
}
.climber-card__stats {
    display: flex;
    justify-content: center;
    gap: 6px;
}
.climber-card__badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 1.25rem;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 999px;
    background: #f4f4f5;
    color: #666;
}
.climber-card__badge--points {
    background: #fff7e0;
    color: #b5860a;
}
</style>

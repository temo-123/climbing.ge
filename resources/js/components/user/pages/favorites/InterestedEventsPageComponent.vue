<template>
	<div class="row">
		<left-menu />

		<div class="col-sm-12">

			<div class="row">
				<div class="col-md-12">
					<breadcrumb />
				</div>
			</div>

			<div class="favorites_header">
				<div class="favorites_header_title">
					<i class="fa fa-heart favorites_header_icon"></i>
					<h3>{{ $t('user.favorites.events.title') }}</h3>
				</div>
				<div class="favorites_header_actions">
					<button type="button" class="btn_icon" :class="{ 'is_spinning': is_loading }" @click="get_interestid_events()" :title="$t('common.refresh')">
						<i class="fa fa-refresh"></i>
					</button>
				</div>
			</div>

			<div v-if="is_loading" class="favorites_loading">
				<div class="spinner-border" role="status"></div>
			</div>

			<div class="event_list" v-else-if="events.length">
				<transition-group name="favorite_card_fade" tag="div" class="event_list_inner">
					<div class="event_row" :class="row_action(event.end_data)" v-for="event in events" :key="event.id">
						<div class="event_date">
							<span class="event_date_day">{{ show_d(event.start_data) }}</span>
							<span class="event_date_month">{{ show_m(event.start_data) }}</span>
							<span class="event_date_year">{{ show_y(event.start_data) }}</span>
						</div>

						<div class="event_img" @click="go_to_events_list('/event/'+event.url_title)">
							<img v-if="event.image != null" :src="'/public/images/event_img/'+event.image" :alt='event.url_title' />
							<img v-else :src="'/public/images/site_img/image.png'" :alt='event.url_title' />
						</div>

						<div class="event_info">
							<h2 class="event_title cursor_pointer" @click="go_to_events_list('/event/'+event.url_title)">
								{{ event.us_event ? event.us_event.title : event.url_title }}
							</h2>
							<span class="event_finished_badge" v-if="row_action(event.end_data) == 'completed_event'">
								{{ $t('user.favorites.events.finished') }}
							</span>
						</div>

						<button
							type="button"
							class="favorite_card_remove"
							@click="del_interested_event(event.id)"
							:title="$t('user.favorites.events.remove_tooltip')"
						>
							<i class="fa fa-times"></i>
						</button>
					</div>
				</transition-group>
			</div>

			<div class="favorites_empty" v-else>
				<i class="fa fa-calendar favorites_empty_icon"></i>
				<h2>{{ $t('user.favorites.events.empty') }}</h2>
			</div>
		</div>
	</div>
</template>

<script>
	import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import moment from "moment"; // https://www.npmjs.com/package/vue-moment

	export default {
		components: {
			breadcrumb,
		},
        data: function () {
            return {
                events: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                is_loading: true,
            };
        },
        mounted() {
            this.get_interestid_events()
        },
        methods: {
			row_action(end_data){
                if (!end_data) return '';
                return moment(end_data).isBefore(moment()) ? 'completed_event' : '';
            },
			show_y(data){return moment(data).format("Y")},
			show_m(data){return moment(data).format("MMM")},
			show_d(data){return moment(data).format("D")},
            get_interestid_events(){
                this.is_loading = true
                axios
                .get('/get_faworite/get_interested_events')
                .then(response => {
                    this.events = response.data
                })
                .catch(error => {
                    this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') })
                })
                .finally(() => {
                    this.is_loading = false
                })
            },

            go_to_events_list(page){
                window.open(this.MIX_APP_SSH + '' + this.MIX_SITE_URL + page)
            },

            del_interested_event(id){
                if(confirm(this.$t('user.favorites.events.confirm_delete'))){
                    axios
                    .post('/set_faworite/del_interested_event/' + id, {
                        article_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_interestid_events()
                    })
                    .catch(error => {
                        this.$bus.$emit('toast', { type: 'danger', message: this.$t('user.favorites.error') })
                    })
                }
            }
        }
	}
</script>

<style scoped>
.favorites_header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin: 20px 0 24px;
}
.favorites_header_title {
    display: flex;
    align-items: center;
    gap: 10px;
}
.favorites_header_title h3 {
    margin: 0;
}
.favorites_header_icon {
    color: #279fbb;
    font-size: 1.3rem;
}
.favorites_header_actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn_icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid #e9e9e9;
    background: #fff;
    color: #606060;
    cursor: pointer;
    transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
}
.btn_icon:hover {
    background: #279fbb;
    border-color: #279fbb;
    color: #fff;
}
.btn_icon.is_spinning i {
    animation: favorites_spin 0.8s linear infinite;
}
@keyframes favorites_spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.favorites_loading {
    display: flex;
    justify-content: center;
    padding: 60px 0;
    color: #279fbb;
}

.event_list_inner {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.event_row {
    position: relative;
    display: flex;
    align-items: stretch;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.event_row:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}
.favorite_card_fade-move,
.favorite_card_fade-enter-active,
.favorite_card_fade-leave-active {
    transition: all 0.25s ease;
}
.favorite_card_fade-enter-from,
.favorite_card_fade-leave-to {
    opacity: 0;
    transform: scale(0.97);
}
.favorite_card_fade-leave-active {
    position: absolute;
}

.event_row.completed_event {
    opacity: 0.7;
}

.event_date {
    flex: 0 0 90px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #279fbb;
    color: #fff;
    text-align: center;
    padding: 10px 6px;
}
.event_date_day {
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1.1;
}
.event_date_month {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.event_date_year {
    font-size: 0.75rem;
    opacity: 0.85;
}

.event_img {
    flex: 0 0 110px;
    cursor: pointer;
    background: #f4f4f4;
}
.event_img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.event_info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 10px 50px 10px 18px;
    min-width: 0;
}
.event_title {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0 0 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.event_finished_badge {
    display: inline-block;
    align-self: flex-start;
    font-size: 0.75rem;
    font-weight: 600;
    color: #e3342f;
    background: #fbe4e3;
    border-radius: 50px;
    padding: 2px 10px;
}

.favorite_card_remove {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: none;
    background: rgba(0, 0, 0, 0.45);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s ease;
}
.favorite_card_remove:hover {
    background: #e3342f;
}

.favorites_empty {
    text-align: center;
    padding: 60px 20px;
    color: #606060;
}
.favorites_empty_icon {
    font-size: 2.5rem;
    color: #ccc;
    margin-bottom: 14px;
    display: block;
}
.favorites_empty h2 {
    font-size: 1.3rem;
    margin-bottom: 6px;
}

@media (max-width: 600px) {
    .event_row {
        flex-wrap: wrap;
    }
    .event_date {
        flex: 0 0 70px;
    }
    .event_img {
        flex: 0 0 90px;
    }
    .event_info {
        padding: 10px 45px 10px 14px;
    }
}
</style>

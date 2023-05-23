<template>
	<div class="row">
		<div class="col-sm-3">
			<left-menu />
		</div>
		
		<div class="col-sm-9">
			
			<div class="row">
				<div class="col-md-12">
					<breadcrumb />
				</div>
			</div>

			<div class="row font-italic">
				<div class="col-md-6">
					<h3 class="mt-3 pb-3 mb-4 ">
						Interestid ivents
					</h3>
				</div>
				<div class="col-md-6">
					<button class="btn btn-success float-right" @click="get_interestid_events()">
						Refresh
					</button>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-md-12">
					<button class="btn btn-success float-right" @click="get_interestid_events()">
						Refresh
					</button>
				</div>
			</div> -->
			
			<div class="container">
				<div class="row" v-if="events.length">
					<div class="col-xs-12 col-sm-12">
						<ul class="event-list">

							<li v-for="event in events" :key="event.id">
								<time datetime="2014-07-20">
									<span class="day">4</span>
									<span class="month">Jul</span>
									<span class="year">2014</span>
									<!-- <span class="time">ALL DAY</span> -->
								</time>

								<!-- <span @click="go_to_events_list('/event/'+event.url_title)" > -->
									<site-img v-if="event.image != NULL" :src="'/public/images/event_img/'+event.image" :alt='event.url_title'/>
									<site-img v-else :src="'/public/images/site_img/image.png'" :alt='event.url_title'/>
								<!-- </span> -->

								<!-- <img alt="Independence Day" src="https://farm4.staticflickr.com/3100/2693171833_3545fb852c_q.jpg" /> -->
								<div class="info">
									<h2 class="title">
										<a @click="go_to_events_list('/event/'+event.url_title)">{{ event.url_title }}</a>
										<span @click="del_interested_event(event.id)" class="float-right">X</span>
									</h2>
									<!-- <p class="desc">United States Holiday</p> -->
								</div>
							</li>


						</ul>
					</div>
				</div>
				<div class="row" v-else>
					<h2>You dont have interestid event</h2>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import breadcrumb from '../../items/BreadcrumbComponent.vue'
	export default {
		components: {
			breadcrumb,
		},
        data: function () {
            return {
                events: [],
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        mounted() {
            this.get_interestid_events()
        },
        methods: {
            get_interestid_events(){
                axios
                .get('../api/event/get_interested_events')
                .then(response => {
                    this.events = response.data
                })
                .catch(error =>{
                })
            },

            go_to_events_list(page){
                window.open(this.MIX_APP_SSH + '' + this.MIX_SITE_URL + page)
            },

            del_interested_event(id){
                if(confirm('Are you sure, you want delite this event from your faworites?')){
                    axios
                    .post('../api/event/del_interested_event/'+id, {
                        article_id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.get_interestid_events()
                        // alert("Ewnt delited from your favorite list!");
                    })
                    .catch(error => {
                        alert("Error");
                    })
                }
            }
        }
	}
</script>

<style scoped>
.event-list {
		list-style: none;
		font-family: 'Lato', sans-serif;
		margin: 0px;
		padding: 0px;
	}
	.event-list > li {
		background-color: rgb(255, 255, 255);
		box-shadow: 0px 0px 5px rgb(51, 51, 51);
		box-shadow: 0px 0px 5px rgba(51, 51, 51, 0.7);
		padding: 0px;
		margin: 0px 0px 20px;
	}
	.event-list > li > time {
		display: inline-block;
		width: 100%;
		color: rgb(255, 255, 255);
		background-color: rgb(197, 44, 102);
		padding: 5px;
		text-align: center;
		text-transform: uppercase;
	}
	.event-list > li:nth-child(even) > time {
		background-color: rgb(165, 82, 167);
	}
	.event-list > li > time > span {
		display: none;
	}
	.event-list > li > time > .day {
		display: block;
		font-size: 56pt;
		font-weight: 100;
		line-height: 1;
	}
	.event-list > li time > .month {
		display: block;
		font-size: 24pt;
		font-weight: 900;
		line-height: 1;
	}
	.event-list > li > img {
		width: 100%;
	}
	.event-list > li > .info {
		padding-top: 5px;
		text-align: center;
	}
	.event-list > li > .info > .title {
		font-size: 17pt;
		font-weight: 700;
		margin: 0px;
	}
	.event-list > li > .info > .desc {
		font-size: 13pt;
		font-weight: 300;
		margin: 0px;
	}
	.event-list > li > .info > ul,
	.event-list > li > .social > ul {
		display: table;
		list-style: none;
		margin: 10px 0px 0px;
		padding: 0px;
		width: 100%;
		text-align: center;
	}
	.event-list > li > .social > ul {
		margin: 0px;
	}
	.event-list > li > .info > ul > li,
	.event-list > li > .social > ul > li {
		display: table-cell;
		cursor: pointer;
		color: rgb(30, 30, 30);
		font-size: 11pt;
		font-weight: 300;
        padding: 3px 0px;
	}
    .event-list > li > .info > ul > li > a {
		display: block;
		width: 100%;
		color: rgb(30, 30, 30);
		text-decoration: none;
	} 
    .event-list > li > .social > ul > li {    
        padding: 0px;
    }
    .event-list > li > .social > ul > li > a {
        padding: 3px 0px;
	} 
	.event-list > li > .info > ul > li:hover,
	.event-list > li > .social > ul > li:hover {
		color: rgb(30, 30, 30);
		background-color: rgb(200, 200, 200);
	}

	@media (min-width: 768px) {
		.event-list > li {
			position: relative;
			display: block;
			width: 100%;
			height: 120px;
			padding: 0px;
		}
		.event-list > li > time,
		.event-list > li > img  {
			display: inline-block;
		}

		.event-list > li > time,
		.event-list > li > img {
			/* width: 120px; */
			height: 100%;
			float: left;
		}
		.event-list > li > .info {
			background-color: rgb(245, 245, 245);
			overflow: hidden;
		}
		.event-list > li > time,
		.event-list > li > img {
			/* width: 120px;
			height: 120px; */
			height: 100%;
			width: auto;
			padding: 0px;
			margin: 0px;
		}
		.event-list > li > time {
			width: 120px;
			height: 120px;
		}
		.event-list > li > .info {
			position: relative;
			height: 120px;
			text-align: left;
			padding-right: 40px;
		}	
		.event-list > li > .info > .title, 
		.event-list > li > .info > .desc {
			padding: 0px 10px;
		}
		.event-list > li > .info > ul {
			position: absolute;
			left: 0px;
			bottom: 0px;
		}
		.event-list > li > .social {
			position: absolute;
			top: 0px;
			right: 0px;
			display: block;
			width: 40px;
		}
        .event-list > li > .social > ul {
            border-left: 1px solid rgb(230, 230, 230);
        }
		.event-list > li > .social > ul > li {			
			display: block;
            padding: 0px;
		}
		.event-list > li > .social > ul > li > a {
			display: block;
			width: 40px;
			padding: 10px 0px 9px;
		}
	}
</style>
<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <left-menu />
            </div>
            
            <div class="col-sm-9">
                
                <div class="container">
                    <div class="row">
                        <div class="[ col-xs-12 col-sm-12 ]">
                            <ul class="event-list">
                                <li>
                                    <time datetime="2014-07-20">
                                        <span class="day">4</span>
                                        <span class="month">Jul</span>
                                        <span class="year">2014</span>
                                        <!-- <span class="time">ALL DAY</span> -->
                                    </time>
                                    <img alt="Independence Day" src="https://farm4.staticflickr.com/3100/2693171833_3545fb852c_q.jpg" />
                                    <div class="info">
                                        <h2 class="title">Independence Day</h2>
                                        <p class="desc">United States Holiday</p>
                                    </div>
                                    <!-- <div class="social">
                                        <ul>
                                            <li class="facebook" style="width:33%;"><a href="#facebook"><span class="fa fa-facebook"></span></a></li>
                                            <li class="twitter" style="width:34%;"><a href="#twitter"><span class="fa fa-twitter"></span></a></li>
                                            <li class="google-plus" style="width:33%;"><a href="#google-plus"><span class="fa fa-google-plus"></span></a></li>
                                        </ul>
                                    </div> -->
                                </li>

                                <!-- <li>
                                    <time datetime="2014-07-20 0000">
                                        <span class="day">8</span>
                                        <span class="month">Jul</span>
                                        <span class="year">2014</span>
                                        <span class="time">12:00 AM</span>
                                    </time>
                                    <div class="info">
                                        <h2 class="title">One Piece Unlimited World Red</h2>
                                        <p class="desc">PS Vita</p>
                                        <ul>
                                            <li style="width:50%;"><a href="#website"><span class="fa fa-globe"></span> Website</a></li>
                                            <li style="width:50%;"><span class="fa fa-money"></span> $39.99</li>
                                        </ul>
                                    </div>
                                    <div class="social">
                                        <ul>
                                            <li class="facebook" style="width:33%;"><a href="#facebook"><span class="fa fa-facebook"></span></a></li>
                                            <li class="twitter" style="width:34%;"><a href="#twitter"><span class="fa fa-twitter"></span></a></li>
                                            <li class="google-plus" style="width:33%;"><a href="#google-plus"><span class="fa fa-google-plus"></span></a></li>
                                        </ul>
                                    </div>
                                </li> -->

                                <!-- <li>
                                    <time datetime="2014-07-20 2000">
                                        <span class="day">20</span>
                                        <span class="month">Jan</span>
                                        <span class="year">2014</span>
                                        <span class="time">8:00 PM</span>
                                    </time>
                                    <img alt="My 24th Birthday!" src="https://farm5.staticflickr.com/4150/5045502202_1d867c8a41_q.jpg" />
                                    <div class="info">
                                        <h2 class="title">Mouse0270's 24th Birthday!</h2>
                                        <p class="desc">Bar Hopping in Erie, Pa.</p>
                                        <ul>
                                            <li style="width:33%;">1 <span class="glyphicon glyphicon-ok"></span></li>
                                            <li style="width:34%;">3 <span class="fa fa-question"></span></li>
                                            <li style="width:33%;">103 <span class="fa fa-envelope"></span></li>
                                        </ul>
                                    </div>
                                    <div class="social">
                                        <ul>
                                            <li class="facebook" style="width:33%;"><a href="#facebook"><span class="fa fa-facebook"></span></a></li>
                                            <li class="twitter" style="width:34%;"><a href="#twitter"><span class="fa fa-twitter"></span></a></li>
                                            <li class="google-plus" style="width:33%;"><a href="#google-plus"><span class="fa fa-google-plus"></span></a></li>
                                        </ul>
                                    </div>
                                </li>

                                <li>
                                    <time datetime="2014-07-31 1600">
                                        <span class="day">31</span>
                                        <span class="month">Jan</span>
                                        <span class="year">2014</span>
                                        <span class="time">4:00 PM</span>
                                    </time>
                                    <img alt="Disney Junior Live On Tour!" src="http://www.thechaifetzarena.com/images/main/DL13_PiratePrincess_thumb.jpg" />
                                    <div class="info">
                                        <h2 class="title">Disney Junior Live On Tour!</h2>
                                        <p class="desc"> Pirate and Princess Adventure</p>
                                        <ul>
                                            <li style="width:33%;">$49.99 <span class="fa fa-male"></span></li>
                                            <li style="width:34%;">$29.99 <span class="fa fa-child"></span></li>
                                        </ul>
                                    </div>
                                    <div class="social">
                                        <ul>
                                            <li class="facebook" style="width:33%;"><a href="#facebook"><span class="fa fa-facebook"></span></a></li>
                                            <li class="twitter" style="width:34%;"><a href="#twitter"><span class="fa fa-twitter"></span></a></li>
                                            <li class="google-plus" style="width:33%;"><a href="#google-plus"><span class="fa fa-google-plus"></span></a></li>
                                        </ul>
                                    </div>
                                </li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
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
	/* .facebook a,
	.twitter a,
	.google-plus a {
		display: block;
		width: 100%;
		color: rgb(75, 110, 168) !important;
	}
	.twitter a {
		color: rgb(79, 213, 248) !important;
	}
	.google-plus a {
		color: rgb(221, 75, 57) !important;
	}
	.facebook:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(75, 110, 168) !important;
	}
	.twitter:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(79, 213, 248) !important;
	}
	.google-plus:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(221, 75, 57) !important;
	} */

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
			width: 120px;
			float: left;
		}
		.event-list > li > .info {
			background-color: rgb(245, 245, 245);
			overflow: hidden;
		}
		.event-list > li > time,
		.event-list > li > img {
			width: 120px;
			height: 120px;
			padding: 0px;
			margin: 0px;
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
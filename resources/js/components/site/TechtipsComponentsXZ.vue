  <template>
    <div id="carousel3d">
        <carousel-3d :perspective="0" :space="200" :display="5" :controls-visible="true" :controls-prev-html="'❬'" :controls-next-html="'❭'" :controls-width="30" :controls-height="60" :clickable="true" :autoplay="true" :autoplay-timeout="5000">
            <!-- @foreach($securities as $security) -->
            <slide :index="22">
                <a href="#">
                    <img src="{{ asset('images/security_img/'.$security->image) }}" alt="{!! $security -> title !!}">
                </a>
                <a href="#">{{security.title}}</a>
            </slide>
            <!-- @endforeach -->
        </carousel-3d>
    </div>

</template>

<script>
    export default {
        // props: [
        //     "comments",
        // ],
        // data: function() {
        //     return{
        //         urldata: [],
        //         is_refresh: false,
        //         id: 0
        //     }
        // },
        // mounted() {
        //     console.log(this.urldata);
        // },
        // methods: {
        //     update: function(){
        //         this.is_refresh = true
        //         axios.get('/get_comments').then((response) => {
        //             console.log(response)
        //             this.urldata = response.data
        //             this.is_refresh = false
        //             this.id++
        //         });
                  
        //     }
        // }

        // new Vue({
        el: '#carousel3d',
        components: {
            'carousel-3d': Carousel3d.Carousel3d,
            'slide': Carousel3d.Slide
        }
        // })
    }
</script>

   <!-- Page Content -->
   @if(isset($articles_gallery))
   <div class="container page-top">
        <div class="row">
            <h2 id="gallery">Gallery</h2>
            @foreach($articles_gallery as $article_gallery)
             <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                <a href="{{ asset('assets/img/article_gallery_img/'.$article_gallery->image)}}" class="fancybox" rel="ligthbox">
                    <img  src="{{ asset('assets/img/article_gallery_img/'.$article_gallery->image)}}" class="zoom img-fluid "  alt="">
                </a>
            </div>
            @endforeach
       </div>
    </div>
    @endif
    
    
    
    <style>
        #demo {
  height:100%;
  position:relative;
  overflow:hidden;
}


.green{
  background-color:#6fb936;
}
        .thumb{
            margin-bottom: 30px;
        }
        
        .page-top{
            margin-top:85px;
        }

   
img.zoom {
    width: 100%;
    height: 200px;
    border-radius:5px;
    object-fit:cover;
    -webkit-transition: all .3s ease-in-out;
    -moz-transition: all .3s ease-in-out;
    -o-transition: all .3s ease-in-out;
    -ms-transition: all .3s ease-in-out;
}
        
 
.transition {
    -webkit-transform: scale(1.2); 
    -moz-transform: scale(1.2);
    -o-transform: scale(1.2);
    transform: scale(1.2);
}
    .modal-header {
   
     border-bottom: none;
}
    .modal-title {
        color:#000;
    }
    .modal-footer{
      display:none;  
    }

    </style>
    
    <script>
        $(document).ready(function(){
  $(".fancybox").fancybox({
        openEffect: "none",
        closeEffect: "none"
    });
    
    $(".zoom").hover(function(){
		
		$(this).addClass('transition');
	}, function(){
        
		$(this).removeClass('transition');
	});
});
    
    </script>


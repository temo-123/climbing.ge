<div class="container top_menu_margin h-recent-work">
	<div class="row">
  		<div class="col-sm-3">
              
            @include('admin.components.left_block')
          
        </div>
            <div class="col-sm-9">
                <ul class="list-group">
                    @forelse($products as $product)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2 col-md-2">
                                <!-- <img src="http://placehold.it/120" class="img-circle img-responsive" alt="" /> -->
                                <img src="{{ asset('images/shop_img/'.$product -> image_1) }}" class="img-circle img-responsive product_table_img">
                            </div>
                            <div class="col-xs-10 col-md-10">
                                <div>
                                    <a href="http://bootsnipp.com/BhaumikPatel/snippets/Obgj"><h3>{{$product -> title}}</h3></a>
                                </div>
                                <div class="comment-text">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh
                                    euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim
                                </div>
                                <div class="action">
                                	<div style="float: left;">
                                        @if($product -> approved == 1)
	                                    <button type="button" class="btn btn-success btn-xs" title="Approved">
	                                        <span class="glyphicon glyphicon-ok"></span>
	                                    </button>
                                        @elseif($product -> approved == 1 || $product -> approved == NULL)
                                        <button type="button" class="btn btn-danger btn-xs" title="Approved">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                        @endif
                                	</div>
                                	<div style="float: right;">
	                                    <button type="button" class="btn btn-primary btn-xs" title="Edit">
	                                        <span class="glyphicon glyphicon-pencil"></span>
	                                    </button>
	                                    <button type="button" class="btn btn-danger btn-xs" title="Delete">
	                                        <span class="glyphicon glyphicon-trash"></span>
	                                    </button>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <h2>You don't have products</h2>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>




<style type="text/css">
.product_table_img{
    height: auto;
    width: 120%;
}
</style>
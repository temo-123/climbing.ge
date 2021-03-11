@extends('user.layouts.app')

@section('content')
<div class="container top_menu_margin h-recent-work">
	<div class="row">
  		<div class="col-sm-3">
              
            @include('user.components.left_block')
          
        </div>
            <div class="col-sm-9">

            <comments_list_component
                @if(isset($table_1_name)) :table_1_name="{{ json_encode($table_1_name) }}" @endif
                @if(isset($table_2_name)) :table_2_name="{{ json_encode($table_2_name) }}" @endif
            > 
            </comments_list_component>
                
            </div>
        </div>
    </div>
</div>
@endsection
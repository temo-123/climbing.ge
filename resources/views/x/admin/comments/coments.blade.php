@extends('admin.layouts.app_admin')

@section('content')

<div class="container">

  @component('admin.components.breadcrumb')
    @slot('title') Coments list ({{$count_comment}}) @endslot
    @slot('parent') Home @endslot
    @slot('active') Coments @endslot
  @endcomponent

  <hr>

    <div class="container bootstrap snippet">
        <div class="row">
            <div class="col-sm-3"><!--left col-->
            
            @include('admin.components.left_block')
            
            </div><!--/col-3-->
        
            <div class="col-sm-9">
            
                <div class="col-xs-12">
                
                    @if($coments)
                        @foreach($coments as $k=>$comment)
                        
                            <div class="row user-row">
                                <div class="col-xs-3 col-sm-2 col-md-1 col-lg-1">
                                    <img class="img-circle" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=50" alt="User Pic">
                                </div>
                                
                                <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                    <strong>{{ $comment->name }} {{$comment -> surname}}</strong>
                                    <strong>({{$comment -> email}})</strong>
                                    <br>
                                    <span class="text-muted">{{ $comment->article }}</span> 
                                </div>
                                
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 dropdown-user" data-for=".slider{{$comment -> id}}">
                                    <i class="glyphicon glyphicon-chevron-down text-muted"></i>
                                </div>
                            </div>
                            
                            <div class="row user-infos slider{{$comment -> id}}">
                                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-1">
                                    <div class="form-group">
                                        <div class="col-xs-1">
                                            ID - {{$comment -> id}}
                                        </div>
                                        <div class="col-xs-11">
                                            {{$comment -> text}}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-12">
                                    <span class="pull-right">
                                    {!! Html::link(route('commentsEdit',['outdoor'=>$comment->id]), 'Edit', ['class'=>'btn btn-sm btn-default', 'alt'=>$comment->name]) !!}
                                    
                                    {!! Form::open(['url'=>route('commentsEdit',['outdoor'=>$comment->id]), 'class' => 'btn btn-sm', 'method' => 'POST']) !!}
                                    
                                    {{ method_field('DELETE') }}
                                    {!! Form::button('Delete',['class'=>'btn btn-sm btn-default', 'type'=>'submit']) !!}
                                    
                                    {!! Form::close() !!}
                                    </span>
                                </div>
                                
                            </div>
                        
                        @endforeach
                    @endif
                
                </div>
                
            </div>
        
        </div><!--/tab-pane-->
    </div><!--/tab-content-->

</div>

@endsection
@if($msg != NULL)
    <p>{!!$msg!!}</p>
@endif

@if(isset($article_array[0]))
    <h1>{!!$article_array[0]!!}</h1>
@endif

@if(isset($article_array[1]))
    {!!$article_array[1]!!}
@endif

@if(isset($article_array[2]))
    <h3>How to get there</h3>
    {!!$article_array[2]!!}
@endif

@if(isset($article_array[3]))
    @if($article_array[3] != 0)
        <h3>Best time to climb</h3>
        {!!$article_array[3]!!}
    @endif
@endif

@if(isset($article_array[4]))
    @if($article_array[4] != 0)
        <h3>What you need</h3>
        {!!$article_array[4]!!}
    @endif
@endif

@if(isset($article_array[5]))
    <h3>Prices from</h3>
    {!!$article_array[5]!!}
@endif

@if(isset($article_array[0]) && isset($article_url_array[0]))
    <a href='climbing.ge/{{$article_url_array[0].'/'.$article_url_array[1]}}'><h2>To learn more</h2></a>
@endif

<?php //dd($article_array);
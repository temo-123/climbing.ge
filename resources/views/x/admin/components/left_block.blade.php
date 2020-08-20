            <ul class="list-group">
                <li class="list-group-item text-muted">Category</li>
                @if(isset($table_1))
                    @if(isset($table_1_count))
                        <li class="list-group-item text-right"><span class="pull-left"><strong>{{$table_1_name}}</strong></span> {{$table_1_count}}</li>
                    @endif
                @endif
                @if(isset($table_2))
                    @if(isset($table_2_count))
                        <li class="list-group-item text-right"><span class="pull-left"><strong>{{$table_2_name}}</strong></span> {{$table_2_count}}</li>
                    @endif
                @endif
                @if(isset($table_4))
                    @if(isset($table_4_count))
                        <li class="list-group-item text-right"><span class="pull-left"><strong>{{$table_1_name}}</strong></span> {{$table_4_count}}</li>
                    @endif
                @endif
                @if(isset($table_4))
                    @if(isset($table_4_count))
                        <li class="list-group-item text-right"><span class="pull-left"><strong>{{$table_2_name}}</strong></span> {{$table_4_count}}</li>
                    @endif
                @endif

                @if(isset($count_1) || isset($count_2) || isset($count_3) || isset($count_4) || isset($count_5))
                    @if(isset($count_1_name) || isset($count_2_name) || isset($count_3_name) || isset($count_4_name) || isset($count_5_name))
                <li class="list-group-item text-right"><span class="pull-left"></span></li>
                    @endif
                @endif

                @if(isset($count_1) && isset($count_1_name))
                <li class="list-group-item text-right"><span class="pull-left"><strong>{{$count_1_name}}</strong></span> {{$count_1}}</li>
                @endif
                @if(isset($count_2) && isset($count_2_name))
                <li class="list-group-item text-right"><span class="pull-left"><strong>{{$count_2_name}}</strong></span> {{$count_2}}</li>
                @endif
                @if(isset($count_3) && isset($count_3_name))
                <li class="list-group-item text-right"><span class="pull-left"><strong>{{$count_3_name}}</strong></span> {{$count_3}}</li>
                @endif
                @if(isset($count_4) && isset($count_4_name))
                <li class="list-group-item text-right"><span class="pull-left"><strong>{{$count_4_name}}</strong></span> {{$count_4}}</li>
                @endif
                @if(isset($count_5) && isset($count_5_name))
                <li class="list-group-item text-right"><span class="pull-left"><strong>{{$count_5_name}}</strong></span> {{$count_5}}</li>
                @endif
            </ul>

            <style>
                #active{
                    background-color: #ddd;
                }
            </style>

<!--             <ul class="list-group display_none_768px">
                <li class="list-group-item text-muted">
                    Your Status = 
                    @if(Auth::user()->hasRole('admin'))
                        Admin
                    @elseif(Auth::user()->hasRole('manager'))
                        Content manager
                    @else
                        User
                    @endif
                </li>
            </ul> -->

            <ul class="list-group display_none_768px">
                <li class="list-group-item text-muted">Munu</li>

                @if((Auth::user()->hasRole('user'))||(Auth::user()->hasRole('admin')) || (Auth::user()->hasRole('manager')))
                <li class="list-group-item text-muted" @if($page_name == 'Home') id="active" @endif>
                    <a href="{{route('home')}}">My page </a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Products') id="active" @endif>
                    <a href="{{route('products')}}">My products</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Favorite products') id="active" @endif>
                    <a href="{{route('favorite')}}">My favorite products</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Review & Comments') id="active" @endif>
                    <a href="{{route('myReviewsAndComments')}}">My Review & Comments</a>
                </li>
                @endif

                @if((Auth::user()->hasRole('admin')) || (Auth::user()->hasRole('manager')))

                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted" @if($page_name == 'Outdoor') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'outdoor'))}}">Outdoor climbing</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Climbing Secrots And Routes') id="active" @endif>
                    <a href="{{route('routes_and_sectors')}}">Route(Beta)</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Indoor') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'indoor'))}}">Indoor climbing</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Mount And Mount Route') id="active" @endif>
                    <a href="{{route('Mountaineering')}}">Mountaineering</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Ice') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'ice'))}}">Ice and mixed</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'News') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'news'))}}">News</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Other') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'other'))}}">Other</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Securities') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'security'))}}">Security</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Partners') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'partners'))}}">Partners</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Events') id="active" @endif>
                    <a href="{{route('article_table', array('article_category'=>'events'))}}">Events</a>
                </li>

                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted" @if($page_name == 'Gallery') id="active" @endif>
                    <a href="{{route('gallery_table', array('gallery_category'=>'index_gallery'))}}">Index Gallery</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Article Gallery') id="active" @endif>
                    <a href="{{route('gallery_table', array('gallery_category'=>'article_gallery'))}}">Article Gallery</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Head Slider') id="active" @endif>
                    <a href="{{route('gallery_table', array('gallery_category'=>'index_header'))}}">Head slider</a>
                </li>

                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted" @if($page_name == 'About') id="active" @endif>
                    <a href="{{route('about')}}">About us</a>
                </li>
                @endif

                @if(Auth::user()->hasRole('admin'))

                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted" @if($page_name == 'Comments') id="active" @endif>
                    <a href="#">Coment</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Users') id="active" @endif>
                    <a href="{{route('users')}}">Users</a>
                </li>
                <li class="list-group-item text-muted" @if($page_name == 'Comments') id="active" @endif>
                    <a href="#">Other</a>
                </li>
                @endif

                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted" @if($page_name == 'Options') id="active" @endif>
                    <a href="{{route('options')}}">Options</a>
                </li>

                <li class="list-group-item text-muted">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
            <ul class="list-group">
                <li class="list-group-item text-muted">Munu</li>

                @if(
                    (Auth::user()->hasRole('user')) ||
                    (Auth::user()->hasRole('admin')) || 
                    (Auth::user()->hasRole('us_manager')) || 
                    (Auth::user()->hasRole('ka_manager')) || 
                    (Auth::user()->hasRole('ru_manager')) || 
                    (Auth::user()->hasRole('manager'))
                )
                <li class="list-group-item text-muted @if($page_name == 'Home') menu_active @endif">
                    <a href="{{route('user_index')}}">My page </a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'favorite_products') menu_active @endif">
                    <a href="{{Route('favorite')}}">My favorite products</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'interested_events') menu_active @endif">
                    <a href="{{Route('interested')}}">Interested events</a>
                </li>
                @endif

                @if(
                    (Auth::user()->hasRole('admin')) || 
                    (Auth::user()->hasRole('us_manager')) || 
                    (Auth::user()->hasRole('ka_manager')) || 
                    (Auth::user()->hasRole('ru_manager')) || 
                    (Auth::user()->hasRole('manager')) ||
                    (Auth::user()->hasRole('seller'))
                )
                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted @if($page_name == 'Products') menu_active @endif">
                    <a href="{{Route('products_list')}}">Products & Category</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Services') menu_active @endif">
                    <a href="{{Route('services_list')}}">Services</a>
                </li>
                @endif

                @if(
                    (Auth::user()->hasRole('admin')) || 
                    (Auth::user()->hasRole('us_manager')) || 
                    (Auth::user()->hasRole('ka_manager')) || 
                    (Auth::user()->hasRole('ru_manager')) || 
                    (Auth::user()->hasRole('manager'))
                )
                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted @if($page_name == 'Outdoor') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'outdoor'))}}">Outdoor climbing</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Climbing Secrots And Routes') menu_active @endif">
                    <a href="{{ Route('routes_and_sectors') }}">Routs & Sectors</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Mount And Mount Route') menu_active @endif">
                    <a href="{{route('mountaineering')}}">Mount & Mount Route</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Indoor') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'indoor'))}}">Indoor climbing</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Ice & Mixed') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'ice'))}}">Ice and mixed</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'News') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'news'))}}">News</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Other') menu_active @endif" >
                    <a href="{{route('article_list', array('article_category'=>'other'))}}" >Other</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Security') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'security'))}}">Security</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Partners') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'partner'))}}">Partners</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Events') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'event'))}}">Events</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Gallery') menu_active @endif">
                    <a href="{{ Route('gallery_list') }}">Gallery</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'site info') menu_active @endif">
                    <a href="{{ Route('about') }}">About us</a>
                </li>
                @endif

                <li class="list-group-item text-muted"></li>

                @if(Auth::user()->hasRole('admin'))
                <li class="list-group-item text-muted @if($page_name == 'Comments') menu_active @endif">
                    <a href="{{ Route('comments_list')}}">Comments</a>
                </li>

                <li class="list-group-item text-muted @if($page_name == 'Users and Roles') menu_active @endif">
                    <a href="{{ Route('users') }}">Users</a>
                </li>
                @endif

                <li class="list-group-item text-muted @if($page_name == 'Options') menu_active @endif">
                    <a href="{{ Route('options') }}">Options</a>
                </li>

                <li class="list-group-item text-muted">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
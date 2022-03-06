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
                    <a href="{{route('user_index')}}">@lang('admin.menu home') </a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Cart') menu_active @endif">
                    <a href="{{route('cart_index')}}">@lang('admin.menu cart')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'My Orders') menu_active @endif">
                    <a href="{{route('my_orders')}}">@lang('admin.menu orders')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'favorite_products') menu_active @endif">
                    <a href="{{Route('favorite')}}">@lang('admin.menu my favorite products')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'interested_events') menu_active @endif">
                    <a href="{{Route('interested')}}">@lang('admin.menu interested events')</a>
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
                    <a href="{{Route('products_list')}}">@lang('admin.menu products & category')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Services') menu_active @endif">
                    <a href="{{Route('services_list')}}">@lang('admin.menu services')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Orders') menu_active @endif">
                    <a href="{{route('all_orders')}}">@lang('admin.menu total orders')</a>
                </li>
                @endif

                @if(
                    (Auth::user()->hasRole('admin')) ||
                    (Auth::user()->hasRole('manager'))
                )
                    <li class="list-group-item text-muted"></li>

                    <li class="list-group-item text-muted @if($page_name == 'Films') menu_active @endif">
                        <a href="#">@lang('admin.menu films') (Beta)</a>
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
                    <a href="{{route('article_list', array('article_category'=>'outdoor'))}}">@lang('admin.menu outdoor climbing')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Climbing Secrots And Routes') menu_active @endif">
                    <a href="{{ Route('routes_and_sectors') }}">@lang('admin.menu sectors & routes')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Mount And Mount Route') menu_active @endif">
                    <a href="{{route('mountaineering')}}">@lang('admin.menu mountaineering')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Indoor') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'indoor'))}}">@lang('admin.menu indoor climbing')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Ice & Mixed') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'ice'))}}">@lang('admin.menu ice climbing')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'News') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'news'))}}">@lang('admin.menu news')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Other') menu_active @endif" >
                    <a href="{{route('article_list', array('article_category'=>'other'))}}" >@lang('admin.menu other')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Security') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'security'))}}">@lang('admin.menu security')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Partners') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'partner'))}}">@lang('admin.menu partners')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Events') menu_active @endif">
                    <a href="{{route('article_list', array('article_category'=>'event'))}}">@lang('admin.menu events')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'Gallery') menu_active @endif">
                    <a href="{{ Route('gallery_list') }}">@lang('admin.menu gallery')</a>
                </li>
                <li class="list-group-item text-muted @if($page_name == 'site info') menu_active @endif">
                    <a href="{{ Route('about') }}">@lang('admin.menu about')</a>
                </li>
                @endif

                <li class="list-group-item text-muted"></li>

                @if(Auth::user()->hasRole('admin'))
                <li class="list-group-item text-muted @if($page_name == 'Comments') menu_active @endif">
                    <a href="{{ Route('comments_list')}}">@lang('admin.menu comments')</a>
                </li>

                <li class="list-group-item text-muted @if($page_name == 'Users and Roles') menu_active @endif">
                    <a href="{{ Route('users') }}">@lang('admin.menu users')</a>
                </li>
                @endif

                <li class="list-group-item text-muted"></li>

                <li class="list-group-item text-muted @if($page_name == 'Options') menu_active @endif">
                    <a href="{{ Route('options') }}">@lang('admin.menu options')</a>
                </li>

                <li class="list-group-item text-muted">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                        Logout
                    </a>    
                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
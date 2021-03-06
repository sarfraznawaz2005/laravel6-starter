<header class="{{getThemeColor()['textColor']}}" style="background: {{getThemeColor()['titleColor']}};">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark {{getThemeColor()['navColor']}} fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <strong><i class="fa fa-code"></i> {{appName()}}</strong>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{active_link(['/', 'home'])}}">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>

                    @auth
                        @if(Module::isEnabled('Task'))
                            <li class="nav-item {{active_link(['tasks.index', 'tasks.edit'])}}">
                                <a class="nav-link" href="{{route('tasks.index')}}">Tasks</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @auth
                        @if(user()->isSuperAdmin())
                            @if(Module::isEnabled('Admin'))
                                <li class="nav-item">
                                    <a class="nav-link" target="_blank" href="{{route('admin.login')}}">
                                        <i class="fa fa-cog"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endauth

                    @guest
                        @if(Module::isEnabled('User'))
                            @if(config('user.allow_user_registration', true))
                                <li class="nav-item nav-item {{active_link('login')}}">
                                    <a class="nav-link" href="{{ route('login')  }}">
                                        <i class="fa fa-sign-in"></i> Sign In
                                    </a>
                                </li>

                                <li class="nav-item {{active_link('register')}}">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fa fa-user"></i> Create Account
                                    </a>
                                </li>
                            @endif
                        @endif
                    @else
                        @if(Module::isEnabled('User'))
                            @if(config('user.allow_user_registration', true))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#"
                                       data-toggle="dropdown"
                                       id="dropdown"
                                       role="button"
                                       aria-expanded="false">
                                        {{ user()->full_name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu" role="menu" aria-labelledby="dropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out"></i> Sign Out
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endif
                        @endif
                    @endguest
                </ul>

            </div>
        </div>
    </nav>

    <div class="text-center page-title animated zoomInDown">
        <h1 class="display-4">{{Meta::get('title')}}</h1>
    </div>

</header>

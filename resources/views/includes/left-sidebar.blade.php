
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{ asset('assets/images/PAQ-SED.svg') }}" alt="" style="width: 160px;">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('assets/images/PAQ-SED.svg') }}" alt="" style="width: 160px;">
        </span>
    </a>

    <!-- LOGO -->
    <a href="#" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('assets/images/PAQ-SED.svg') }}" alt="" style="width: 160px;">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar="">

        <!--- Sidemenu -->
        <ul class="side-nav">
            <br>
{{--            <li class="side-nav-item">--}}
{{--                <a href="dashboard.php"  class="side-nav-link">--}}
{{--                    <i class="uil-home-alt"></i>--}}
{{--                    <span> Dashboard</span>--}}
{{--                </a>--}}
{{--            </li>--}}
            @if (Auth::user()->hasRole('DEO'))
            <li class="side-nav-item">
                <a href="{{url('assembly-question')}}" class="side-nav-link">
                    <i class="uil-tag-alt"></i>
                    <span> Assembly Question </span>
                </a>
            </li>
                <li class="side-nav-item">
                    <a href="{{url('assembly-question-list')}}" class="side-nav-link">
                        <i class="uil uil-mailbox-alt"></i>
                        <span>All Question list </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{url('archived-assembly-question-list')}}" class="side-nav-link">
                        <i class="uil uil-mailbox-alt"></i>
                        <span>Archived Question list </span>
                    </a>
                </li>
            @endif
            @if(!Auth::user()->hasRole('DEO') && !Auth::user()->hasRole('Sectary') )
                <li class="side-nav-item">
                    <a href="{{url('assembly-question-list')}}" class="side-nav-link">
                        <i class="uil uil-mailbox-alt"></i>
                        <span>Received Question list</span>
                    </a>
                </li>
            <li class="side-nav-item">
                <a href="{{url('accepted-assembly-question-list')}}" class="side-nav-link">
                    <i class="uil uil-check-circle"></i>
                    <span> Accepted Question</span>
                </a>
            </li>
                <li class="side-nav-item">
                <a href="{{url('forwarded-assembly-question-list')}}" class="side-nav-link">
                    <i class="uil uil-check-circle"></i>
                    <span> Forwarded Question</span>
                </a>
            </li>
            @endif
            @if(Auth::user()->hasRole('Assembly'))
                <li class="side-nav-item">
                    <a href="{{url('completed-assembly-question-list')}}" class="side-nav-link">
                        <i class="uil-home-alt"></i>
                        <span> Completed Question</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->hasRole('Sectary'))
                <li class="side-nav-item">
                    <a href="{{url('dashboard')}}" class="side-nav-link">
                        <i class="uil-home-alt"></i>
                        <span> Dashboard</span>
                    </a>
                </li>
            @endif
{{--            <li class="side-nav-item">--}}
{{--                <a href="assembly-Rec.php" class="side-nav-link">--}}
{{--                    <i class="uil uil-check-circle"></i>--}}
{{--                    <span> Res. Received </span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="side-nav-item">--}}
{{--                <a href="assing_verfication.php" class="side-nav-link">--}}
{{--                    <i class="uil uil-envelope-bookmark"></i>--}}
{{--                    <span>Verfication </span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="side-nav-item">--}}
{{--                <a href="sub-MoE.php" class="side-nav-link">--}}
{{--                    <i class="uil uil-post-stamp"></i>--}}
{{--                    <span>Sub-MoE</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="side-nav-item">--}}
{{--                <a href="resulation.php" class="side-nav-link">--}}
{{--                    <i class="uil-tag-alt"></i>--}}
{{--                    <span>Resolution</span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a href="all-user.php"  class="side-nav-link">--}}
{{--                    <i class="uil-user"></i>--}}
{{--                    <span> Users </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a href="index.php" class="side-nav-link">--}}
{{--                    <i class="mdi mdi-logout me-1"></i>--}}
{{--                    <span>Logout</span>--}}
{{--                </a>--}}
{{--            </li>--}}

        </ul>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>

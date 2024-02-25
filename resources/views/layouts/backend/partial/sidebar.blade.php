<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="index.html" class="site_title"><i class="fa fa-cart-plus"
                    aria-hidden="true"></i><span>SG-POS</span></a>
        </div>
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ URL::asset('asset/images/img.jpg') }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ getLoginUser() }}</h2>
            </div>
        </div>
        <br />
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-home" href="{{ url('/index') }}"></i> Home</a>
                    </li>
                    <li><a><i class="fa fa-history" aria-hidden="true"></i> Shift <span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/sg-backend/shift/index') }}">list</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-users" aria-hidden="true"></i> User <span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/sg-backend/user/') }}">Create</a></li>
                            <li><a href="{{ url('/sg-backend/user/list') }}">list</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-list-alt" aria-hidden="true"></i> Category <span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/sg-backend/category/') }}">Create</a></li>
                            <li><a href="{{ url('/sg-backend/category/list') }}">list</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-cubes" aria-hidden="true"></i> Item <span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/sg-backend/item/') }}">Create</a></li>
                            <li><a href="{{ url('/sg-backend/item/list') }}">list</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-percent" aria-hidden="true"></i> Promotion <span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/sg-backend/discount/') }}">Create</a></li>
                            <li><a href="{{ url('/sg-backend/discount/list') }}">list</a></li>
                        </ul>
                    </li>

                    <li><a><i class="fa fa-cog" aria-hidden="true"></i>Setting <span
                                class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ url('/sg-backend/setting/') }}">Create</a></li>
                            <li><a href="{{ url('/sg-backend/setting/list') }}">List</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>

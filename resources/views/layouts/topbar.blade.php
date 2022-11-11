<header>
    <nav class="navbar navbar-expand navbar-light">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                 </ul>
                <div class="dropdown">


                    <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar avatar-md2">
                            <img src="{{ url('assets/images/faces/2.jpg') }} ">
                        </div>
                        <div class="text">
                          <h6 class="user-dropdown-name">{{ Auth::user()->name }}</h6>
                          <p class="user-dropdown-status text-sm text-muted">

                          </p>
                        </div>
                      </a>
                    <ul class="dropdown-menu dropdown-menu-end topbarUserDropdown" aria-labelledby="topbarUserDropdown">
                        <li><a class="dropdown-item" href="{{ route('changepassword') }}"><i
                                class="icon-mid bi bi-gear me-2"></i>Change Password</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }} "
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"><i
                                    class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

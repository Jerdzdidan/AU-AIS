<!DOCTYPE html>

<html
    lang="en"
    class="layout-wide customizer-hide"
    data-assets-path="{{ asset('themes/sneat/assets') }}"
    data-template="vertical-menu-template-free">
    <head>
    <meta charset="utf-8" />
    <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login - AU-AIS</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo/arellano_logo.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('themes/sneat/assets/vendor/css/pages/page-auth.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('themes/sneat/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('themes/sneat/assets/js/config.js') }}"></script>
</head>

<body>

    <!-- Carousel -->
    <div id="backgroundCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="bg-slide" style="background-image: url('{{ asset('img/auth/login_carousel/au-main_1.jpg') }}');">
            <div class="overlay"></div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="bg-slide" style="background-image: url('{{ asset('img/auth/login_carousel/au-malabon-elisa.jpg') }}');">
            <div class="overlay"></div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="bg-slide" style="background-image: url('{{ asset('img/auth/login_carousel/au-malabon-rizal.jpg') }}');">
            <div class="overlay"></div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="bg-slide" style="background-image: url('{{ asset('img/auth/login_carousel/au-pasay-abad.jpg') }}');">
            <div class="overlay"></div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="bg-slide" style="background-image: url('{{ asset('img/auth/login_carousel/au-pasay-mabini.jpg') }}');">
            <div class="overlay"></div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="bg-slide" style="background-image: url('{{ asset('img/auth/login_carousel/au-pasig.jpg') }}');">
            <div class="overlay"></div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="bg-slide" style="background-image: url('{{ asset('img/auth/login_carousel/au-plaridel-1.jpg') }}');">
            <div class="overlay"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card px-sm-6 px-0">
            <div class="card-body">
              <!-- Logo -->
                <div class="app-brand justify-content-center">
                    <span class="app-brand-logo demo">
                        <span class="text-primary">
                        <img src="{{ asset('img/logo/arellano_logo.png') }}" id="logo_img" alt="">
                        </span>
                    </span>
                    <span class="app-brand-text demo text-heading fw-bold ms-3">AU-AIS (ADMIN)</span>
                </div>
              <!-- /Logo -->
                <div class="d-flex align-items-center">
                    <h4 class="mb-1">Welcome!</h4>
                    <img src="{{ asset('img/auth/hand.gif') }}" id="hand_gif" class="mb-2" alt="">
                </div>
               
                <p class="mb-6">Arellano University - Academic Information System</p>

                @foreach(['success', 'error', 'warning', 'info'] as $type)
                    @if(session($type))
                        <div class="alert alert-{{ $type === 'error' ? 'danger' : $type }} alert-dismissible" role="alert">
                            {{ session($type) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                @endforeach

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="formAuthentication" method="post" action="{{ route('auth.admin.authenticate') }}" class="mb-6">
                    @csrf
                    <div class="mb-6">
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter your username" autofocus maxlength="150" required>
                    </div>

                    <div class="mb-6 form-password-toggle">
                      <label for="password">Password:</label>
                      <div class="input-group input-group-merge">
                          <input type="password" name="password" id="password" class="form-control" placeholder="••••••••••••" aria-describedby="password" required>
                          <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                      </div>
                    </div>

                    <div class="mb-8">
                        <div class="d-flex justify-content-between">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="remember-me" />
                                <label class="form-check-label" for="remember-me"> Remember Me </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                    </div>
                </form>

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>


    <!-- Core JS -->

    <script src="{{ asset('themes/sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('themes/sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('themes/sneat/assets/vendor/js/bootstrap.js') }}"></script>

    <script src="{{ asset('themes/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('themes/sneat/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="{{ asset('themes/sneat/assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>

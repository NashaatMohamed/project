<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ get_system_setting('application_name') }}</title>
  <meta content="{{ get_system_setting('meta_description') }}" name="description">
  <meta content="{{ get_system_setting('meta_keywords') }}" name="keywords">

  @if(get_system_setting('application_favicon'))
    <!-- Favicon -->
    <link rel="icon" href="{{ get_system_setting('application_favicon') }}">
  @endif

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Krub:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('themes/bikin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/bikin/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/bikin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/bikin/vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/bikin/vendor/venobox/venobox.css') }}" rel="stylesheet">
  <link href="{{ asset('themes/bikin/vendor/aos/aos.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('themes/bikin/css/style.css') }}" rel="stylesheet">
</head>

<body>
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
 
      @if(get_system_setting('application_logo'))
        <!-- Image logo -->
        <a href="/" class="logo mr-auto"><img src="{{ get_system_setting('application_logo') }}" alt="logo" class="img-fluid"></a>
      @else
        <!-- Text logo -->
        <h1 class="logo mr-auto"><a href="/">{{ get_system_setting('application_name') }}</a></h1>
      @endif

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li class="active"><a href="/">{{ __('bikin.home') }}</a></li>
          <li><a href="{{url('/')}}#about">{{ __('bikin.about') }}</a></li>
          <li><a href="{{url('/')}}#features">{{ __('bikin.features') }}</a></li>
          <li><a href="{{url('/')}}#services">{{ __('bikin.services') }}</a></li>
          <li><a href="{{url('/')}}#pricing">{{ __('bikin.pricing') }}</a></li>
          <li><a href="{{url('/')}}#contact">{{ __('bikin.contact') }}</a></li>
          <li><a href="{{ route('login') }}">{{ __('bikin.login') }}</a></li>
        </ul>
      </nav>

      <a href="{{ route('register') }}" class="get-started-btn">{{ __('bikin.get_started') }}</a>
    </div>
  </header>

  <main id="main">
    <section class="features mt-5" data-aos="fade-up">
      <div class="container">
        <div class="section-title">
          <h2>{{ $page_title }}</h2>
        </div>
        {!! $page_content !!}
      </div>
    </section>
  </main>

  <footer id="footer" style="position: fixed;bottom:0;width:100%">
    <div class="container d-md-flex py-4">
      <div class="mr-md-auto text-center text-md-left">
        <div class="copyright">
          &copy; Copyright {{ date('Y') }} <strong><span>{{ get_system_setting('application_name') }}</span></strong>. All Rights Reserved.
        </div>
      </div>
      <div class="social-links text-center text-md-right pt-3 pt-md-0">
        @if(get_theme_setting('bikin', 'social_twitter_link'))
          <a href="{{ get_theme_setting('bikin', 'social_twitter_link') }}" class="twitter"><i class="bx bxl-twitter"></i></a>
        @endif

        @if(get_theme_setting('bikin', 'social_facebook_link'))
          <a href="{{ get_theme_setting('bikin', 'social_facebook_link') }}" class="facebook"><i class="bx bxl-facebook"></i></a>
        @endif

        @if(get_theme_setting('bikin', 'social_instagram_link'))
          <a href="{{ get_theme_setting('bikin', 'social_instagram_link') }}" class="instagram"><i class="bx bxl-instagram"></i></a>
        @endif

        @if(get_theme_setting('bikin', 'social_linkedin_link'))
          <a href="{{ get_theme_setting('bikin', 'social_linkedin_link') }}" class="linkedin"><i class="bx bxl-linkedin"></i></a>
        @endif
      </div>
      <div class="form-group ml-3">
        @if (count($languages) > 1)
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="changeLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ __('messages.language') }}
            </button>
            <div class="dropdown-menu" aria-labelledby="changeLanguage">
              @foreach ($languages as $language => $name)
                <a class="dropdown-item" href="/change-language/{{ $language }}">{{ $name }}</a>
              @endforeach
            </div>
          </div>
        @endif
      </div> 
    </div>
  </footer>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
  <div id="preloader"></div>

  <script src="{{ asset('themes/bikin/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('themes/bikin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('themes/bikin/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
  <script src="{{ asset('themes/bikin/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('themes/bikin/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('themes/bikin/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('themes/bikin/vendor/venobox/venobox.min.js') }}"></script>
  <script src="{{ asset('themes/bikin/vendor/aos/aos.js') }}"></script>

  <script src="{{ asset('themes/bikin/js/main.js') }}"></script>

</body>
</html>
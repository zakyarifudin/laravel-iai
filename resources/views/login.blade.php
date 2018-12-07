
<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			Login
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script>
		<!--end::Web font -->
        <!--begin::Base Styles -->
		<link href="{!! asset('assets/vendors/base/vendors.bundle.css') !!}" rel="stylesheet" type="text/css" />
		<link href="{!! asset('assets/demo/default/base/style.bundle.css') !!}" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="{!! asset('assets/demo/default/media/img/logo/favicon.ico') !!}" />
	</head>
	<!-- end::Head -->
    <!-- end::Body -->
	<body  class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-login m-login--signin  m-login--5" id="m_login" style="background-image: url({!! asset('assets/app/media/img//bg/bg-3.jpg') !!}">
				<div class="m-login__wrapper-1 m-portlet-full-height">
					<div class="m-login__wrapper-1-1">
						<div class="m-login__contanier">
							<div class="m-login__content">
								<div class="m-login__logo">
									<a href="#">
										{{-- <img src="{!! asset('assets/app/media/img//logos/logo-2.png') !!}"> --}}
									</a>
								</div>
								<div class="m-login__title">
									<h3>
										Chatify
									</h3>
								</div>
								<div class="m-login__desc">
									Questions  - Answers
								</div>
								<div class="m-login__form-action">
									<a href="{{URL::to('/register')}}"  class="btn btn-outline-focus m-btn--pill">
										Sign Up
									</a>
								</div>
							</div>
						</div>
						<div class="m-login__border">
							<div></div>
						</div>
					</div>
				</div>
				<div class="m-login__wrapper-2 m-portlet-full-height">
					<div class="m-login__contanier">
						<div class="m-login__signin">
							<div class="m-login__head">
								<h3 class="m-login__title">
									Login
								</h3>
							</div>
							<form class="m-login__form m-form" action="{{ URL::to('/login')}}" method="post">
                                {{ csrf_field() }}
								<div class="form-group m-form__group @if ($errors->has('email')) has-danger @endif">
									<input class="form-control m-input" type="email" placeholder="Your Email" name="email" value="" required>
                                    @if ($errors->has('email'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

								<div class="form-group m-form__group @if ($errors->has('password')) has-danger @endif">
									<input class="form-control m-input m-login__form-input--last" type="password" placeholder="Your Password" name="password" required>
                                    @if ($errors->has('password'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>


								<div class="row m-login__form-sub">
									<div class="col m--align-right">
										{{-- <a href="{{URL::to('/forget')}}"  class="m-link">
											Lupa Password ?
										</a> --}}
									</div>
								</div>
								<div class="m-login__form-action">
									<button type="submit"  class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
										Login
									</button>
									{{-- <a href="" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air btn-google"><i class="fa fa-google"></i> Google</a> --}}
									<a href="{{ url('/auth/google') }}" class="btn btn-outline-danger m-btn m-btn--custom m-btn--icon m-btn--outline m-btn--pill m-btn--air">
										<span>
											<i class="fa fa-google"></i>
											<span>
												With Google
											</span>
										</span>
									</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end:: Page -->
    	<!--begin::Base Scripts -->
		<script src="{!! asset('assets/vendors/base/vendors.bundle.js') !!}" type="text/javascript"></script>
		<script src="{!! asset('assets/demo/default/base/scripts.bundle.js') !!}" type="text/javascript"></script>
		<!--end::Base Scripts -->
        <!--begin::Page Snippets -->
		<script src="{!! asset('assets/snippets/custom/pages/user/login.js') !!}" type="text/javascript"></script>
		<!--end::Page Snippets -->
        @if (Session::has('sweet_alert.alert'))
            <script>
                swal({!! Session::get('sweet_alert.alert') !!});
            </script>
        @endif
	</body>
	<!-- end::Body -->
</html>

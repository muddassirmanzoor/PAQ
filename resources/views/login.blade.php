@include('includes.login-header')
<div class="auth-fluid">
    <!--Auth fluid left content -->
    <div class="auth-fluid-form-box">
        <div class="align-items-center d-flex h-100">
            <div class="card-body">

                <!-- Logo -->
                <div class="text-center text-lg-center">
                    <a href="#" class="logo-light">
                        <span><img src="{{ asset('assets/images/PAQ-SED.svg') }}" alt="" style="width: 160px;"></span>
                    </a>
                </div>

                <!-- title
                <h4 class="mt-0">Sign In</h4>
                <p class="text-muted mb-4">Enter your email address and password to access account.</p>-->
                <br>
                <!-- form -->
                <form action="{{url('login')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="emailaddress" class="form-label">Email address</label>
                        <input class="form-control" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
{{--                        <a href="pages-recoverpw.php" class="text-muted float-end"><small>Forgot your password?</small></a>--}}
                        <label for="password" class="form-label">Password</label>
                        <input class="form-control" type="password" name="password" required="" id="password" placeholder="Enter your password">
                    </div>
                    <div class="d-grid mb-0 text-center">
                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-login"></i> Log In </button>
                    </div>
                </form>
                <!-- end form-->

                <!-- Footer-->
{{--                <footer class="footer footer-alt">--}}
{{--                    <p class="text-muted">Don't have an account? <a href="register.php" class="text-muted ms-1"><b>Sign Up</b></a></p>--}}
{{--                </footer>--}}

            </div> <!-- end .card-body -->
        </div> <!-- end .align-items-center.d-flex.h-100-->
    </div>
    <!-- end auth-fluid-form-box-->

    <!-- Auth fluid right content -->
    <div class="auth-fluid-right text-center">
        <div class="auth-user-testimonial">
            <img src="{{ asset('assets/images/gov-punjab-logo.svg') }}" style="width: 50%;">
            <h2 class="mb-0" style="color: #0A481E;">Welcome PAQ-SED Dashboard!</h2>
            <h4 class="mb-3" style="color: #0A481E;"><sub>School Education Department</sub></h4>

            <p  style="color: #0A481E;">
                © <script>document.write(new Date().getFullYear())</script> PMIU PESRP. All Rights Reserved.
            </p>
        </div> <!-- end auth-user-testimonial-->
    </div>
    <!-- end Auth fluid right content -->
</div>
<!-- end auth-fluid-->
@include('includes.login-footer')


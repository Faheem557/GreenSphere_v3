<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/brand/favicon.ico">

    <!-- TITLE -->
    <title>{{ env('APP_NAME') }} – Create new user</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="../assets/css/plugins.css" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="../assets/css/icons.css" rel="stylesheet">

    <!-- INTERNAL Switcher css -->
    <link href="../assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="../assets/switcher/demo.css" rel="stylesheet">

</head>

<body class="app sidebar-mini ltr login-img">

    <!-- BACKGROUND-IMAGE -->
    <div class="">

        <!-- GLOBAL LOADER -->
        <div id="global-loader">
            <img src="../assets/images/loader.svg" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOBAL LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="">
                <!-- Theme-Layout -->

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <a href="{{ url('/') }}"><img src="../assets/images/brand/logo-white.png"
                                class="header-brand-img m-0" alt="Logo"></a>
                    </div>
                </div>
                <div class="container-login100">

                    <div class="wrap-login100 col-6 p-6">
                        <form class="login100-form validate-form" method="POST" action="{{ route('register') }}">
                            @csrf
                            <span class="login100-form-title">
                                Registration
                            </span>

                            <div class="row">
                                <!-- Name Field (First Column) -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="mdi mdi-account" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Name" value="{{ old('name') }}" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Field (First Column) -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="zmdi zmdi-email" aria-hidden="true"></i>
                                        </span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Email" value="{{ old('email') }}" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Field (Second Column) -->
                                <div class="col-md-6 mb-3" id="Password-toggle">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">

                                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                            <i class="zmdi zmdi-eye-off" aria-hidden="true"></i>
                                        </a>

                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Password" required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password Field (Second Column) -->
                                <div class="col-md-6 mb-3" id="Password-toggle">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                            <i class="zmdi zmdi-eye-off" aria-hidden="true"></i>
                                        </a>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Confirm Password" required>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Location Field (First Column) -->
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="mdi mdi-map-marker" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" id="location" name="location"
                                            placeholder="Location" value="{{ old('location') }}">
                                    </div>
                                    @error('location')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone Field (Second Column) -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="mdi mdi-phone" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            placeholder="Phone Number" value="{{ old('phone') }}">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- User Type Field (First Column) -->
                                <div class="col-md-6 mb-3">
                                    <label for="usertype" class="form-label">User Type</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="mdi mdi-account" aria-hidden="true"></i>
                                        </span>
                                        <select class="form-control" id="usertype" name="usertype" required>
                                            {{-- <option value="">Select User Type</option> --}}
                                            <option value="user" {{ old('usertype') == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="seller" {{ old('usertype') == 'seller' ? 'selected' : '' }}>Saller</option>
                                               
                                        </select>
                                    </div>
                                    @error('usertype')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Preference Field (Second Column) -->
                                <div class="col-md-6 mb-3" id="preference-container" style="display: none;">
                                    <label for="preference" class="form-label">Preference</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted">
                                            <i class="mdi mdi-leaf" aria-hidden="true"></i>
                                        </span>
                                        <select class="form-control" id="preference" name="preference">
                                            <option value="">Select Preference</option>
                                            <option value="indoor"
                                                {{ old('preference') == 'indoor' ? 'selected' : '' }}>Indoor Plants
                                            </option>
                                            <option value="outdoor"
                                                {{ old('preference') == 'outdoor' ? 'selected' : '' }}>Outdoor Plants
                                            </option>
                                        </select>
                                    </div>
                                    @error('preference')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="container-login100-form-btn mb-3">
                                <button type="submit" class="login100-form-btn btn-primary">
                                    Register
                                </button>
                            </div>

                            <!-- Sign In Link -->
                            <div class="text-center pt-3">
                                <p class="text-dark mb-0 d-inline-flex">Already have an account? <a
                                        href="{{ route('login') }}" class="text-primary ms-1">Sign In</a></p>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- END PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- JQUERY JS -->
    <script src="../assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- SHOW PASSWORD JS -->
    <script src="../assets/js/show-password.min.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="../assets/plugins/p-scroll/perfect-scrollbar.js"></script>

    <!-- Color Theme js -->
    <script src="../assets/js/themeColors.js"></script>

    <!-- CUSTOM JS -->
    <script src="../assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="../assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="../assets/switcher/js/switcher.js"></script>

    <script>
        document.getElementById('usertype').addEventListener('change', function() {
            const preferenceContainer = document.getElementById('preference-container');
            if (this.value === 'user') {
                preferenceContainer.style.display = 'block';
            } else {
                preferenceContainer.style.display = 'none';
            }
        });
    </script>

</body>

</html>


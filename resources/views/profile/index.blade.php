<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Settings</title>

    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container mt-4">
        <h2>Account Settings</h2>

        <form id="profileInfoForm">
            <!-- Profile Information Section -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    Profile Information
                </div>
                <div class="card-body">
                    <!-- Show Message -->
                    <div id="showMessage"></div>

                    <!-- Profile Picture -->
                    <div class="mb-3">
                        <center>
                            <img id="preview" class="lazy-load mt-2 img-thumbnail" style="max-width: 200px;"
                                alt="Profile Preview" src="{{ asset('image/default-profile/default-avatar1.jpg') }}">
                        </center>
                        <br />
                        <label for="profilePicture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" name="profile" id="profilePicture" accept="image/*">
                        <small class="text-muted">Upload a new profile picture</small>

                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" value="{{ auth()->user()->name }}"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}"
                            readonly>
                    </div>
                </div>
            </div>

            <!-- Security Settings Section -->
            <div class="card mt-3" style="margin-bottom: 5rem;">
                <div class="card-header bg-primary text-white">
                    Security Settings
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="changePassword" class="form-label">Change Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                            placeholder="Confirm new password">
                        <span id="confirmPasswordValidationText"></span>
                    </div>
                    <!-- Add more security settings as needed -->
                    <button type="button" class="btn btn-primary" id="saveProfileBtn">Save Changes</button>
                    <a href="{{ route('landing.page') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>

            <!-- Two Factor Authentication Section -->
            <div class="card mt-3" style="margin-bottom:5rem;">
                <div class="card-header bg-primary text-white">
                    Two Factor Authentication
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="enable2fa" name="enable2fa">
                        <label class="form-check-label" for="enable2fa">
                            Enable Two Factor Authentication
                        </label>
                    </div>

                    <!-- QR Code and Key Combinations -->
                    <div id="twoFactorAuthSection" class="d-none">
                        <h5>Scan QR Code or Enter Key Combinations</h5>

                        <div class="row mb-3">
                            <div class="col-md-6 text-center">
                                <div id="qrcode"></div>
                                <br />
                                <label for="qrCode" class="form-label">Scan this QR Code</label>
                            </div>

                            <div class="col-md-6">
                                <label for="keyCombinations" class="form-label fw-bold">Key Combinations</label>
                                <div id="keyCombinations"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/profile/profile.js') }}" data-profile="{{ route('save.profile') }}"></script>
    <script src="{{ asset('js/profile/two-factor-auth.js') }}"></script>
</body>

</html>

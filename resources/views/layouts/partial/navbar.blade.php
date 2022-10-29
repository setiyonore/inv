
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{Url('home')}}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
{{--                <span class="badge badge-warning navbar-badge">15</span>--}}
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
{{--                <span class="dropdown-item dropdown-header">15 Notifications</span>--}}
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" class="dropdown-item" id="changePassword">
                    <i class="fas fa-lock mr-2"></i> Ganti Kata sandi
{{--                    <span class="float-right text-muted text-sm">3 mins</span>--}}
                </a>
                <div class="dropdown-divider"></div>
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fas fa-users mr-2"></i> 8 friend requests--}}
{{--                    <span class="float-right text-muted text-sm">12 hours</span>--}}
{{--                </a>--}}
                <div class="dropdown-divider"></div>
{{--                <a href="#" class="dropdown-item">--}}
{{--                    <i class="fas fa-file mr-2"></i> 3 new reports--}}
{{--                    <span class="float-right text-muted text-sm">2 days</span>--}}
{{--                </a>--}}
                <div class="dropdown-divider"></div>
{{--                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>--}}
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('global.changePassword')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="short">@lang('global.oldPassword')</label>
                    <input type="password" id="oldPassword" class="form-control">
                </div>
                <div class="form-group">
                    <label for="short">@lang('global.newPassword')</label>
                    <input type="password" id="newPassword" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submit-password" class="btn btn-success" onclick="updatePassword()">@lang('global.save')</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('global.cancel')</button>
            </div>
        </div>
    </div>
</div>
<!-- /.navbar -->
<script>
    const APP_URL = {!! json_encode(url('/')) !!}
    document.getElementById('changePassword').onclick = function(){
        $('#modalChangePassword').modal('show');
    }
    const token = $('#tokenPass').val();
    function updatePassword(){
        const oldPassword = $('#oldPassword').val();
        const newPassword = $('#newPassword').val();
        $.ajax({
            url: APP_URL+'/users/changePassword',
            method: 'POST',
            data: {
                _token:  "{{ csrf_token() }}",
                oldPassword: oldPassword,
                newPassword: newPassword,
            },
            success: function (data) {
                if (data.errors) {
                    $.each(data.errors, function (key, value) {
                        alert(value);
                    });
                } else {
                    if (data.success === 1){
                        alert('Password Berhasil di ubah');
                        $('#modalChangePassword').modal('hide');
                    } else if(data.success === 0){
                        alert('Password lama salah')
                    }
                }
            }
        });
    }
</script>

 <nav class="navbar navbar-expand-lg main-navbar">
     <form class="form-inline mr-auto">
         <ul class="navbar-nav mr-3">
             <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
             <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                         class="fas fa-search"></i></a></li>
         </ul>

     </form>
     <ul class="navbar-nav navbar-right">


         <li class="dropdown"><a href="#" data-toggle="dropdown"
                 class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                 <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                 <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->username }}
                     ({{ auth()->user()->role_label }})</div>
             </a>
             <div class="dropdown-menu dropdown-menu-right">
                 @php
                     $lastLogin = auth()->user()->last_login_at;
                     $now = now();
                 @endphp
                 <div class="dropdown-title">
                     @if ($lastLogin)
                         @php
                             $minutes = $now->diffInMinutes($lastLogin);
                             $hours = $now->diffInHours($lastLogin);
                             $days = $now->diffInDays($lastLogin);
                         @endphp

                         @if ($minutes < 60)
                             Logged in {{ $minutes }} min ago
                         @elseif($hours < 24)
                             Logged in {{ $hours }} hour{{ $hours > 1 ? 's' : '' }} ago
                         @else
                             Logged in {{ $days }} day{{ $days > 1 ? 's' : '' }} ago
                         @endif
                     @else
                         Never logged in
                     @endif
                 </div>
                 <a href="features-profile.html" class="dropdown-item has-icon">
                     <i class="far fa-user"></i> Profile
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" id="logout-btn" class="dropdown-item has-icon text-danger">
                     <i class="fas fa-sign-out-alt"></i> Logout
                 </a>
             </div>
         </li>
     </ul>
 </nav>


 @push('scripts')
     <script>
         document.getElementById('logout-btn').addEventListener('click', function(e) {
             e.preventDefault();

             if (!confirm('Apakah Anda yakin ingin logout?')) return;

             fetch("{{ route('logout') }}", {
                     method: "POST",
                     headers: {
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json',
                         'Content-Type': 'application/json'
                     },
                 })
                 .then(response => response.json())
                 .then(data => {

                     if (data.success && data.redirect) {
                         setTimeout(() => {
                             window.location.replace(data.redirect);
                         }, 100); // 100 ms
                         //  window.location.href = data.redirect;
                         iziToast.success({
                             title: 'Success',
                             message: 'Successfully logged out.',
                             position: 'topRight'
                         });
                     } else {
                         iziToast.error({
                             title: 'Error',
                             message: data.message || 'Failed to logout.',
                             position: 'topRight'
                         });
                     }
                 })
                 .catch(() => {
                     iziToast.error({
                         title: 'Error',
                         message: 'An error occurred while trying to logout.',
                         position: 'topRight'
                     });
                 });
         });
     </script>
 @endpush

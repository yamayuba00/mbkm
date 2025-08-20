@php
    $menus = [];

    if (Auth::check()) {
        $role = Auth::user()->role;
        $status = 1;

        switch ($role) {
            case 1:
                $dashboardRoute = route('admin.dashboard');
                $dashboardActive = request()->is('admin/dashboard');
                break;
            case 2:
                $dashboardRoute = route('kaprodi.dashboard');
                $dashboardActive = request()->is('kaprodi/dashboard');
                break;
            case 3:
                $dashboardRoute = route('lecturer.dashboard');
                $dashboardActive = request()->is('lecturer/dashboard');
                break;
            case 4:
                $dashboardRoute = route('student.dashboard');
                $dashboardActive = request()->is('student/dashboard');
                break;
            default:
                $dashboardRoute = '';
                $dashboardActive = false;
                break;
        }

        $menus['dashboard'] = [
            'label' => 'Dashboard',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'fas fa-fire',
                    'route' => $dashboardRoute,
                    'active' => $dashboardActive,
                ],
            ],
        ];

        // admin
        if ($role == 1) {
            $menus['verification'] = [
                'label' => 'Verification',
                'items' => [
                    [
                        'label' => 'MBKM Program',
                        'icon' => 'fa fa-check',
                        'route' => route('admin.verification-program-mbkm.index'),
                        'active' => request()->is('admin/verification-program-mbkm*'),
                    ],
                    [
                        'label' => 'List MBKM Program',
                        'icon' => 'fa fa-list',
                        'route' => route('admin.verification-program-mbkm.view'),
                        'active' => request()->routeIs('admin.verification-program-mbkm.view'),
                    ],
                ],
            ];

            $menus['user'] = [
                'label' => 'User',
                'items' => [
                    [
                        'label' => 'Students',
                        'icon' => 'fa fa-user',
                        'route' => route('admin.students.index'),
                        'active' => request()->is('admin/students*'),
                    ],
                    [
                        'label' => 'Lecturers',
                        'icon' => 'fa fa-users',
                        'route' => route('admin.lecturers.index'),
                        'active' => request()->is('admin/lecturers*'),
                    ],
                ],
            ];

            $menus['master'] = [
                'label' => 'Master',
                'items' => [
                    [
                        'label' => 'Faculty and Study Program',
                        'icon' => 'fa fa-university',
                        'route' => route('admin.program-campus.index'),
                        'active' => request()->is('admin/program-campus*'),
                    ],
                    [
                        'label' => 'Submission Periode',
                        'icon' => 'fa fa-file',
                        'route' => route('admin.submission-periode.index'),
                        'active' => request()->is('admin/submission-periode*'),
                    ],
                    [
                        'label' => 'Submission Program',
                        'icon' => 'fa fa-file',
                        'route' => route('admin.submission-type.index'),
                        'active' => request()->is('admin/submission-type*'),
                    ],
                    [
                        'label' => 'Information List Program',
                        'icon' => 'fa fa-info',
                        'route' => route('admin.information-program.index'),
                        'active' => request()->is('admin/information-program*'),
                    ],
                ],
            ];
        }
        // admin
        if ($role == 2) {
            $menus['verification'] = [
                'label' => 'Verification',
                'items' => [
                    [
                        'label' => 'List MBKM Program',
                        'icon' => 'fa fa-list',
                        'route' => route('admin.verification-program-mbkm.view'),
                        'active' => request()->routeIs('admin.verification-program-mbkm.view'),
                    ],
                ],
            ];

            $menus['user'] = [
                'label' => 'User',
                'items' => [
                    [
                        'label' => 'Students',
                        'icon' => 'fa fa-user',
                        'route' => route('admin.students.index'),
                        'active' => request()->is('admin/students*'),
                    ],
                    [
                        'label' => 'Lecturers',
                        'icon' => 'fa fa-users',
                        'route' => route('admin.lecturers.index'),
                        'active' => request()->is('admin/lecturers*'),
                    ],
                ],
            ];
        }

        // lecturer
        if ($role == 3) {
            $menus['verification'] = [
                'label' => 'Verification',
                'items' => [
                    [
                        'label' => 'MBKM Program',
                        'icon' => 'fa fa-check',
                        'route' => route('lecturer.verification-program-mbkm.index'),
                        'active' => request()->is('lecturer/verification-program-mbkm*'),
                    ],
                ],
            ];
            $menus['mbkm'] = [
                'label' => 'MBKM Program',
                'items' => [
                    [
                        'label' => 'Activities Students',
                        'icon' => 'fa fa-users',
                        'route' => route('lecturer.activities.index'),
                        'active' => request()->is('lecturer/activities*'),
                    ],
                    [
                        'label' => 'Logbook Students',
                        'icon' => 'fa fa-book',
                        'route' => route('lecturer.logbook.index'),
                        'active' => request()->is('lecturer/logbook*'),
                    ],
                ],
            ];
            $menus['master'] = [
                'label' => 'Master',
                'items' => [
                    [
                        'label' => 'Students',
                        'icon' => 'fa fa-users',
                        'route' => route('lecturer.students.index'),
                        'active' => request()->is('lecturer/students*'),
                    ],
                ],
            ];
        }
        // student
        if ($role == 4) {
            $menus['mbkm'] = [
                'label' => 'MBKM',
                'items' => [
                    [
                        'label' => 'Registration',
                        'icon' => 'fa fa-clipboard-list',
                        'route' => route('student.mbkm-program.index'),
                        'active' => request()->is('student/mbkm-program*'),
                    ],
                    [
                        'label' => 'Logbook',
                        'icon' => 'fa fa-file',
                        'route' => route('student.logbook.index'),
                        'active' => request()->is('student/logbook*'),
                    ],
                    [
                        'label' => 'Activities',
                        'icon' => 'fa fa-bolt',
                        'route' => route('student.activities.index'),
                        'active' => request()->is('student/activities*'),
                    ],
                ],
            ];

            $menus['program'] = [
                'label' => 'Program',
                'items' => [
                    [
                        'label' => 'Information Program',
                        'icon' => 'fa fa-info',
                        'route' => route('student.information-program.index'),
                        'active' => request()->is('student/information-program*'),
                    ],
                ],
            ];
        }
    }
@endphp

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="#">
                <img src="{{ asset('assets/mbkm-137.png') }}" alt="logo" width="200" class="logo-square">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="#">MBKM</a>
        </div>

        <ul class="sidebar-menu">
            @foreach ($menus as $section)
                <li class="menu-header">{{ $section['label'] }}</li>
                @foreach ($section['items'] as $item)
                    <li class="{{ $item['active'] ? 'active' : '' }}">
                        <a href="{{ $item['route'] }}" class="nav-link">
                            <i class="{{ $item['icon'] }}"></i> <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </aside>
</div>

1. Primary setup
    composer create-project laravel/laravel v2-multi-auth
    cd v2-multi-auth
    //setup db in .env
2. Migration
    php artisan make:migration create_admins_table
    //make Blueprint
    php artisan make:migration create_writers_table
    //make Blueprint
    php artisan migrate
3. Setup models
    php artisan make:model Admin
    php artisan make:model Writer
    //same the user, set $guard, $fillable, $hidden, $casts
    protected $guard = 'admin'; //assign with this role
4. Define guard
    //add 'admin'&'writer' guards
    //add 'admins'&'writers' providers
5. Add & Setup auth controllers
    ## use laravel/ui
    composer require laravel/ui
    php artisan ui bootstrap --auth
    npm install & npm run dev
    ## LoginController
    //__construct need to add middleware for admin & writer
    //add showAdminLoginForm function (return view and assign url) 
    //add adminLogin function (
        validate $request, attempt with guard and 
        - redirect()->intended('/admin')
        - back()->withInput($request->only('email', 'remember'));)
    //do the same thing with writer
    ## RegisterController
    //__construct need to add middleware for admin & writer
    //add showAdminRegisterForm function (return view and assign url)
    //add createAdmin function
        protected function createAdmin(Request $request)
        {
            $this->validator($request->all())->validate();
            $admin = Admin::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
            return redirect()->intended('login/admin');
        }
    //do the same thing with writer
6. Setup authentication pages
    //change login.blade.php
        <div class="card-body">
            @isset($url)
            <form method="POST" action='{{ url("login/$url") }}' aria-label="{{ __('Login') }}">
            @else
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
            @endisset
                @csrf
                [...]
        </div>
    //do the same thing with register.blade.php
7. Create homepage for each role
    $ touch resources/views/layouts/auth.blade.php
    $ touch resources/views/admin.blade.php
    $ touch resources/views/writer.blade.php
    $ touch resources/views/home.blade.php
    //paste the contents into this page
8. Setup routes in routes/web.php
    //setup
9. Setup Middleware/RedirectIfAuthenticate.php
        if ($guard == "admin" && Auth::guard($guard)->check()) {
            return redirect('/admin');
        }
        if ($guard == "writer" && Auth::guard($guard)->check()) {
            return redirect('/writer');
        }
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }
10. Avoid access but not authenticated
    //redirect not auth user say /writer to /login/writer or the same thing
    //add function unauthenticated() to Handler class on app/Exceptions/Handler.php
        protected function unauthenticated($request, AuthenticationException $exception)
        {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->guest('/login/admin');
            }
            if ($request->is('writer') || $request->is('writer/*')) {
                return redirect()->guest('/login/writer');
            }
            return redirect()->guest(route('login'));
        }
11. Run app
    //localhost:8000/{register|login/{writer|admin}}
    
https://pusher.com/tutorials/multiple-authentication-guards-laravel/#set-up-authentication-pages

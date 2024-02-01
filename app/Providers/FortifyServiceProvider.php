<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // laravel start with loading all service provider then call all register then all booted so we but the code in reg
    public function register(): void
    {
        $request = request();
        if ($request->is('admin/*')) {
            Config::set('fortify.guard', 'admin');
            Config::set('fortify.passwords', 'admins');
            Config::set('fortify.prefix', 'admin');
            Config::set('fortify.home', 'admin/dashboard');
        }
        //we can use app->singleton to return same object
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request) {
                if ($request->user('admin')) { // admin from admin guard
                    return redirect()->intended('admin/dashboard');
                    // intended used to redired user to page he want to reach before log in
                }

                return redirect()->intended('/'); // other guard
            }
        });

        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request) {
                return redirect('/');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {



        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });


        if(Config::get('fortify.guard')=='admin'){
            Fortify::authenticateUsing([new AuthenticateUser ,'authenticate']); // take or closer function or login in authenticationUser.php take a user or false
            // we pass it in array like routing (Call back function)
            // when method is not static we send a object not class like new class_name
            Fortify::viewPrefix('auth.');
        }
        else{
            Fortify::viewPrefix('front.auth.');
        }


        //Fortify::viewPrefix('auth.');
//        Fortify::loginView('auth.login'); // make view of login to auth.login
//        Fortify::requestPasswordResetLinkView('auth.forget-password');
//        Fortify::registerView('auth.register');
    }
}

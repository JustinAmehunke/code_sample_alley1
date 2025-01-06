<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;
use App\Http\Traits\OTPGenerator;
use Session;

class JetstreamServiceProvider extends ServiceProvider
{
    use OTPGenerator;
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  /**
   * The `boot` function configures permissions, handles user authentication using email or mobile
   * number, and generates an OTP for two-factor authentication if necessary.
   * 
   * @return void If the conditions are met, the function is returning the `` object.
   */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where(function($query) use ($request) {
                $query->where('email', $request->email)
                      ->orWhere('mobile', $request->email);
            })
            ->where('deleted', 0)
            ->first();

           if($user){
            // Session::forget('user_2fa');
                if(!Session::has('user_2fa')){
                    //send OTP
                    $this->generateCode($user->id, $request->email);

                }else{
                    if (($request->password == $user->auth_code)) {
                        return $user;
                    }
                }
           }
    
            // if ($user &&
            //     Hash::check($request->password, $user->password)) {
            //     return $user;
            // }
        });
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}

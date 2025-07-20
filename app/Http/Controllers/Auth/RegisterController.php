<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'user_name' => ['unique:users,user_name,id'],
            'picture' => ['nullable', 'max:2048'],
            'role' => ['nullable', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
       $userData = DB::transaction(function () use ($data) {

        $picture = null;

        if(request()->hasFile('picture')) {
            $filename = 'user_'.time().'_' . date('d-M-Y').".". request()->file('picture')->extension();
            request()->file('picture')->storeAs('user',$filename,'public');
            $picture = $filename;
        }

          $user = User::create([
                'name' => $data['name'],
                'picture' => $picture,
                'user_name' => $data['user_name'],
                'email' => $data['email'],
                'role' => $data['role'] ?? 'user' ,
                'password' => Hash::make($data['password']),
            ]);

            Profile::create([
                'user_id' => $user->id,
            ]);

            return $user;
        });

        return $userData;

    }
}

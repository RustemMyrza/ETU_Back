<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function edit()
    {
        return view('user.edit');
    }
    public function save(Request $request)
    {
        $request->validate([
            'password'              => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ],
        [
            'password.required' => 'Пароль обязательно для заполнения',
            'password.min' => 'Минимальная длина пароля 6 символов',
            'password_confirmation.required' => 'Подтвердите новый пароль',
            'password.confirmed' => 'Пароли не совпадают'
        ]);

        $user = Auth::user();

        $user = User::find($user->id);
        $user->password = Hash::make(request('password'));
        if($user->update()){
            return redirect('admin/edit')->with('success', 'Пароль изменен');
        }else{
            return redirect('admin/edit')->with('error', 'Ошибка изменения пароля');
        }
    }
}

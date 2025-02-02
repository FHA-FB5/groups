<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class AppController extends Controller
{
    /**
     * Display the index page
     */
    public function index(): RedirectResponse
    {
        // check if registration module is active
        if (Module::where('key', 'registration')->first()->active) {
            return Redirect::route('app.register');
        }

        return Redirect::route('app.login');
    }

    /**
     * Display the login page
     */
    public function login(): Response
    {
        return Inertia::render('Login');
    }

    /**
     * Display the register page
     */
    public function register(): Response
    {
        // get courses ordered by name
        $courses = Course::orderBy('name')->get();

        return Inertia::render('Register', [
            'courses' => $courses,
        ]);
    }

    /**
     * Display the 404 page
     */
    public function notFound(): Response
    {
        return Inertia::render('404');
    }

    /**
     * Register a new user
     */
    public function registerUser(): RedirectResponse
    {
        // check if user with email already exists and login
        $user = User::where('email', strtolower(Request::input('email')))->first();
        if ($user) {
            Session::flash('info', 'Wir haben dich bereits in unserer Datenbank gefunden und haben dich automatisch eingeloggt.');

            return $this->authenticate($user);
        }

        // validate the request
        $validated = Request::validate([
            'firstname' => ['required', 'string', 'min:2', 'max:255'],
            'lastname' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'min:3', 'max:255', 'unique:users'],
            'email_confirm' => ['required', 'string', 'email', 'min:3', 'max:255', 'same:email'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        // remove email_confirm from array
        unset($validated['email_confirm']);

        $validated['email'] = strtolower($validated['email']);

        // create the user
        $user = User::create($validated);

        return $this->authenticate($user);
    }

    /**
     * Login an existing user
     */
    public function loginUser(): RedirectResponse
    {
        // validate the request
        $validated = Request::validate([
            'email' => ['required', 'string', 'email', 'min:3', 'max:255'],
        ]);

        // check if user exists
        $user = User::where('email', strtolower($validated['email']))->first();
        if (! $user) {
            Session::flash('error', 'Es konnte kein Benutzer mit dieser E-Mail-Adresse gefunden werden.');

            return Redirect::back();
        }

        return $this->authenticate($user);
    }

    /**
     * Authenticate a user
     */
    protected function authenticate(User $user): RedirectResponse
    {
        // login the user
        Auth::login($user, true);

        return Redirect::route('dashboard.index');
    }
}

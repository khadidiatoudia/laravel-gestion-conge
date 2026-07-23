<?php
namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin() {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'user.dashboard');
        }

        return view('auth.login');
    }

    public function showRegister() {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'user.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'matricule_solde' => 'nullable|string|exists:agents,matricule_solde',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'gestionnaire',
            'agent_id' => null,
        ]);

        if (!empty($validated['matricule_solde'])) {
            $agent = Agent::where('matricule_solde', $validated['matricule_solde'])->first();
            if ($agent) {
                $user->agent()->associate($agent);
                $user->save();
            }
        }

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Compte créé avec succès. ' . ($user->agent ? 'Votre solde de congés est disponible.' : 'Demandez l’association de votre profil agent à l’administrateur.'));
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $defaultRoute = Auth::user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard');
            return redirect()->intended($defaultRoute);
        }
        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    public function account() {
        $user = Auth::user();
        $agent = $user->agent?->load([
            'conges' => fn($q) => $q->orderByDesc('date_cessation_service'),
            'absences' => fn($q) => $q->orderByDesc('date_debut'),
            'lieuAffectation',
        ]);

        if ($user->isAdmin()) {
            return view('dashboard.index', compact(
                'user', 'agent'
            ));
        }

        return view('dashboard.user', compact('user', 'agent'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

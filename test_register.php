<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST INSCRIPTION ===\n\n";

// Test 1: Vérifier la connexion DB
echo "1. Connexion DB: ";
try {
    \DB::connection()->getPdo();
    echo "OK\n";
} catch (\Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
}

// Test 2: Vérifier les colonnes de la table users
echo "2. Colonnes users: ";
$cols = \Schema::getColumnListing('users');
echo implode(', ', $cols) . "\n";

// Test 3: Simuler la validation du formulaire
echo "3. Test création User: ";
try {
    // Supprimer l'utilisateur de test s'il existe
    \App\Models\User::where('email', 'test_register@example.com')->delete();
    
    $user = \App\Models\User::create([
        'name' => 'Utilisateur Test',
        'email' => 'test_register@example.com',
        'password' => \Hash::make('MotDePasse123'),
        'role' => 'gestionnaire',
        'agent_id' => null,
    ]);
    echo "OK (id={$user->id})\n";

    // Nettoyage
    $user->delete();
} catch (\Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
}

// Test 4: Vérifier la table sessions
echo "4. Table sessions existe: ";
echo (\Schema::hasTable('sessions') ? "OUI" : "NON") . "\n";

// Test 5: Vérifier la table cache
echo "5. Table cache existe: ";
echo (\Schema::hasTable('cache') ? "OUI" : "NON") . "\n";

// Test 6: Vérifier les routes
echo "6. Route 'user.dashboard' définie: ";
try {
    $url = route('user.dashboard');
    echo "OUI ($url)\n";
} catch (\Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
}

// Test 7: Vérifier le message de succès - apostrophes
echo "7. Test message succès (apostrophes): ";
$user = new \App\Models\User();
$user->agent_id = null;
$msg = 'Compte créé avec succès. ' . ($user->agent ? 'Votre solde de congés est disponible.' : "Demandez l'association de votre profil agent à l'administrateur.");
echo "OK - '$msg'\n";

// Test 8: Vérifier driver de session
echo "8. Driver session: ";
echo config('session.driver') . "\n";

echo "\n=== FIN DU TEST ===\n";

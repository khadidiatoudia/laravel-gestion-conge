<?php
namespace App\Models;
use App\Models\Agent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['name', 'email', 'password', 'role', 'agent_id'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function agent() {
        return $this->belongsTo(Agent::class);
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
}

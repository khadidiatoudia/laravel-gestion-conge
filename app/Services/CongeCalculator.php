<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\JourFerie;

class CongeCalculator {
    private array $joursFeries = [];

    public function __construct(int $annee) {
        $this->joursFeries = JourFerie::where('annee', $annee)
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();
    }

    public function estJourOuvrable(Carbon $date): bool {
        // Dimanche = non ouvrable
        if ($date->dayOfWeek === Carbon::SUNDAY) return false;
        // Jour férié = non ouvrable
        if (in_array($date->format('Y-m-d'), $this->joursFeries)) return false;
        return true; // Lundi-Samedi hors fériés
    }

    public function calculerDateReprise(Carbon $dateCessation, int $joursAPrendre): Carbon {
        // Si cessation un vendredi, le comptage commence lundi
        $debut = $dateCessation->copy()->addDay();
        if ($dateCessation->dayOfWeek === Carbon::FRIDAY) {
            $debut = $dateCessation->copy()->next(Carbon::MONDAY);
        }

        $joursComptes = 0;
        $date = $debut->copy();

        while ($joursComptes < $joursAPrendre) {
            if ($this->estJourOuvrable($date)) {
                $joursComptes++;
            }
            if ($joursComptes < $joursAPrendre) {
                $date->addDay();
            }
        }

        // La reprise est le lendemain ouvrable après le dernier jour de congé
        $reprise = $date->copy()->addDay();
        while (!$this->estJourOuvrable($reprise)) {
            $reprise->addDay();
        }

        return $reprise;
    }

    public function compterJoursOuvrables(Carbon $debut, Carbon $fin): int {
        $count = 0;
        $date = $debut->copy();
        while ($date->lte($fin)) {
            if ($this->estJourOuvrable($date)) $count++;
            $date->addDay();
        }
        return $count;
    }
}

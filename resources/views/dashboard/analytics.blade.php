<!-- Graphique 1 : Évolution des congés et absences par mois -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <span class="card-title-text"><i class="fas fa-chart-line text-success me-2"></i>Évolution des Congés et Absences par Mois ({{ $annee }})</span>
    </div>
    <div class="card-body">
        <div style="position: relative; height: 300px;">
            <canvas id="evolutionChart"></canvas>
        </div>
    </div>
</div>

<!-- Graphique 2 : Répartition des congés par type de personnel -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <span class="card-title-text"><i class="fas fa-users text-primary me-2"></i>Jours de Congés par Type de Personnel (Total: {{ $congesPER + $congesPATS }}j)</span>
    </div>
    <div class="card-body d-flex justify-content-center" style="height: 250px;">
        <div style="position: relative; width: 100%; max-width: 300px;">
            <canvas id="personelChart"></canvas>
        </div>
    </div>
</div>

<!-- Graphique 3 : Statuts des demandes de congés -->
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <span class="card-title-text"><i class="fas fa-tasks text-warning me-2"></i>Statuts des Demandes de Congés</span>
    </div>
    <div class="card-body d-flex justify-content-center" style="height: 250px;">
        <div style="position: relative; width: 100%; max-width: 350px;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>


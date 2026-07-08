@extends('layouts.app')
@section('title', 'Génération de Rapports')
@section('page-title', 'Rapports & Analyses RH')

@section('content')
<style>
    .custom-card { background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 12px !important; box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important; }
    .report-icon-box { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.3rem; }
    .select-custom, .input-custom { border: 1px solid #cbd5e1 !important; border-radius: 8px !important; height: 42px; font-size: 0.9rem; color: #334155; }
    .select-custom:focus, .input-custom:focus { border-color: #15803d !important; box-shadow: 0 0 0 3px rgba(21, 163, 74, 0.1) !important; }
    .btn-green-primary { background-color: #15803d !important; border-color: #15803d !important; color: #ffffff !important; border-radius: 8px !important; font-weight: 600; height: 42px; transition: all 0.2s ease; }
    .btn-green-primary:hover { background-color: #166534 !important; transform: translateY(-1px); }
    .btn-pdf { background-color: #fef2f2 !important; border: 1px solid #fee2e2 !important; color: #dc2626 !important; font-weight: 600; border-radius: 8px; height: 42px; }
    .btn-pdf:hover { background-color: #dc2626 !important; color: #ffffff !important; }
    .btn-excel { background-color: #f0fdf4 !important; border: 1px solid #dcfce7 !important; color: #16a34a !important; font-weight: 600; border-radius: 8px; height: 42px; }
    .btn-excel:hover { background-color: #16a34a !important; color: #ffffff !important; }
    .card-title-text { font-size: 0.95rem; font-weight: 600; color: #1e293b; }
</style>

<div class="mb-4">
    <h4 class="fw-700 text-dark mb-1">Génération de Rapports</h4>
    <p class="text-muted mb-0 small"><i class="fas fa-file-alt text-success me-1"></i> Module d'édition des états de congés et des registres d'effectifs USSEIN.</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card custom-card">
            <div class="card-header bg-white py-3">
                <span class="card-title-text"><i class="fas fa-sliders-h text-success me-2"></i>Critères d'extraction</span>
            </div>
            <div class="card-body">
                <form id="formRapport" action="{{ route('rapports.generer') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-600 text-secondary">Modèle d'état</label>
                        <select id="typeRapport" name="type_rapport" class="form-select select-custom">
                            <option value="conges">Planning récapitulatif des Congés</option>
                            <option value="effectifs">Registre matriculaire du Personnel</option>
                            <option value="alertes">Soldes Critiques (&ge; 48 jours)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-600 text-secondary">Composante / UFR</label>
                        <select id="structureCode" name="structure_code" class="form-select select-custom">
                            <option value="all">Toutes les composantes (Global)</option>
                            @foreach($structures as $struct)
                                <option value="{{ $struct->code }}">{{ $struct->code }} - {{ $struct->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-600 text-secondary">Corps de métier</label>
                        <select id="corpsCode" name="corps_code" class="form-select select-custom">
                            <option value="all">Tous les personnels</option>
                            @foreach($corpsPersonnel as $corps)
                                <option value="{{ $corps->code }}">{{ $corps->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label small fw-600 text-secondary">Du</label>
                            <input type="date" id="dateDebut" name="date_debut" class="form-control input-custom" value="{{ now()->startOfYear()->format('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-600 text-secondary">Au</label>
                            <input type="date" id="dateFin" name="date_fin" class="form-control input-custom" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <button type="submit" id="btnPrevisualiser" class="btn btn-green-primary w-100"><i class="fas fa-sync me-2"></i> Prévisualiser l'état</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card custom-card h-100">
            <div class="card-header bg-white py-3">
                <span class="card-title-text"><i class="fas fa-layer-group text-success me-2"></i>Panneau d'exportation</span>
            </div>
            <div class="card-body">
                <div id="rapportNotGennerated" class="p-4 border border-dashed rounded-3 text-center mb-4 bg-light-subtle">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="report-icon-box bg-success-subtle text-success"><i class="fas fa-file-invoice"></i></div>
                    </div>
                    <h5 class="text-dark fw-600 mb-1">Aucun document chargé</h5>
                    <p class="small text-muted mb-0">Définissez vos filtres à gauche pour compiler les indicateurs réglementaires.</p>
                </div>

                <div id="rapportGenerated" style="display: none;" class="mb-4">
                    <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
                        <div>
                            <i class="fas fa-check-circle me-2"></i>
                            <span id="rapportTitle"></span>
                        </div>
                        <small id="rapportTime" class="text-muted"></small>
                    </div>
                </div>

                <h6 class="fw-700 text-dark mb-3">Sélectionner un format de sortie :</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card p-3 border rounded-3 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fs-2 text-danger"><i class="far fa-file-pdf"></i></div>
                                    <div>
                                        <span class="fw-600 text-dark d-block">Document PDF</span>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Idéal pour impression officielle</small>
                                    </div>
                                </div>
                                <form id="formPdf" style="display: none;" method="POST">
                                    @csrf
                                    <input type="hidden" name="type_rapport">
                                    <input type="hidden" name="structure_code">
                                    <input type="hidden" name="corps_code">
                                    <input type="hidden" name="date_debut">
                                    <input type="hidden" name="date_fin">
                                </form>
                                <button id="btnPdf" class="btn btn-pdf px-3"><i class="fas fa-download"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card p-3 border rounded-3 bg-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fs-2 text-success"><i class="far fa-file-excel"></i></div>
                                    <div>
                                        <span class="fw-600 text-dark d-block">Tableur Excel / CSV</span>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Idéal pour retraitements et statistiques</small>
                                    </div>
                                </div>
                                <button id="btnExcel" class="btn btn-excel px-3 disabled" disabled><i class="fas fa-download"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formRapport = document.getElementById('formRapport');
    const btnPrevisualiser = document.getElementById('btnPrevisualiser');
    const btnPdf = document.getElementById('btnPdf');
    const btnExcel = document.getElementById('btnExcel');
    const rapportNotGenerated = document.getElementById('rapportNotGennerated');
    const rapportGenerated = document.getElementById('rapportGenerated');
    const formPdf = document.getElementById('formPdf');

    // Soumettre le formulaire de rapport (HTML form submission)
    formRapport.addEventListener('submit', function(e) {
        // Laisser le formulaire se soumettre normalement
        btnPrevisualiser.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Génération en cours...';
        btnPrevisualiser.disabled = true;
    });

    // Bouton PDF
    btnPdf.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Copier les valeurs du formulaire principal
        formPdf.action = '{{ route("rapports.pdf") }}';
        formPdf.querySelector('input[name="type_rapport"]').value = document.getElementById('typeRapport').value;
        formPdf.querySelector('input[name="structure_code"]').value = document.getElementById('structureCode').value;
        formPdf.querySelector('input[name="corps_code"]').value = document.getElementById('corpsCode').value;
        formPdf.querySelector('input[name="date_debut"]').value = document.getElementById('dateDebut').value;
        formPdf.querySelector('input[name="date_fin"]').value = document.getElementById('dateFin').value;
        
        // Soumettre le formulaire
        formPdf.submit();
    });

    // Bouton Excel (pour le moment, juste affiche un message)
    btnExcel.addEventListener('click', function(e) {
        e.preventDefault();
        alert('La fonctionnalité Excel sera disponible prochainement.');
    });

    // Afficher les statuts après génération (optionnel, s'il y a une redirection)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('rapport_generated') === '1') {
        rapportNotGenerated.style.display = 'none';
        rapportGenerated.style.display = 'block';
        document.getElementById('rapportTitle').textContent = 'Rapport généré avec succès';
        document.getElementById('rapportTime').textContent = new Date().toLocaleString('fr-FR');
        
        // Activer les boutons d'export
        btnPdf.disabled = false;
        btnExcel.disabled = false;
        btnExcel.classList.remove('disabled');
    }
});
</script>
@endsection

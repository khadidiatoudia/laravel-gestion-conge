@extends('layouts.app')
@section('title', 'Jours Fériés')
@section('page-title', 'Gestion des Jours Fériés')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<style>
    #calendar {
        background: #ffffff;
        padding: 12px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .fc .fc-toolbar-title {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: #333333;
        text-transform: capitalize;
    }
    .fc .fc-button-primary {
        background-color: #0f5132 !important; /* Vert USSEIN */
        border-color: #0f5132 !important;
        border-radius: 8px !important;
        font-weight: 600;
    }
    .fc .fc-button-primary:hover {
        background-color: #0a3622 !important;
    }
    .fc .fc-button-active {
        background-color: #ffc107 !important; /* Or USSEIN */
        border-color: #ffc107 !important;
        color: #000000 !important;
    }

    /* Permet l'affichage complet du titre dans les cases du mois */
    .fc-event-main {
        white-space: normal !important;
        word-break: break-word !important;
    }
    .fc-event {
        border: none !important;
        padding: 4px 6px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        line-height: 1.2 !important;
        margin-bottom: 2px !important;
    }

    /* Style personnalisé des badges */
    .holiday-grid-fixe {
        background-color: #e0f7fa !important;
        color: #006064 !important;
        border-left: 4px solid #00bcd4 !important;
    }
    .holiday-grid-variable {
        background-color: #f5f5f5 !important;
        color: #424242 !important;
        border-left: 4px solid #9e9e9e !important;
    }
</style>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 fw-bold border-bottom-0">
                <i class="fas fa-plus me-2 text-success"></i>Ajouter un jour férié
            </div>
            <div class="card-body pt-0">
                <form method="POST" action="{{ route('jours_feries.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-500 small">Nom *</label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex: Grand Magal" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500 small">Date *</label>
                        <input type="date" name="date" id="calendar_input_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500 small">Année *</label>
                        <input type="number" name="annee" class="form-control" value="{{ $annee }}" min="2020" max="2030" required>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="recurrent" id="rec" value="1">
                        <label class="form-check-label small fw-medium" for="rec">
                            Jour fixe (même date chaque année)
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        <i class="fas fa-save me-2"></i>Ajouter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                <span class="fw-bold"><i class="fas fa-calendar-alt me-2 text-danger"></i>Calendrier RH</span>
                <form method="GET" action="{{ route('jours_feries.index') }}">
                    <select name="annee" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 95px; border-radius: 8px; font-weight: 600;">
                        @foreach([2024, 2025, 2026, 2027, 2028, 2029, 2030] as $y)
                        <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="card-body p-3 pt-0">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<form id="delete-holiday-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var anneeEnCours = "{{ $annee }}";

        var eventsList = [
            @foreach($jours as $j)
            {
                id: "{{ $j->id }}",
                title: "{!! addslashes($j->nom) !!}",
                @if($j->recurrent)
                    start: anneeEnCours + "-" + "{{ $j->date->format('m-d') }}",
                    className: "holiday-grid-fixe"
                @else
                    start: "{{ $j->date->format('Y-m-d') }}",
                    className: "holiday-grid-variable"
                @endif
            },
            @endforeach
        ];

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
            initialDate: anneeEnCours + "-01-01",
            locale: 'fr',
            firstDay: 1,
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            buttonText: {
                today: "Aujourd'hui",
                month: "Mois",
                list: "Planning"
            },
            events: eventsList,

            dateClick: function(info) {
                document.getElementById('calendar_input_date').value = info.dateStr;
            },

            eventClick: function(info) {
                if (confirm("Voulez-vous supprimer le jour férié '" + info.event.title + "' ?")) {
                    var deleteForm = document.getElementById('delete-holiday-form');

                    // Utilisation de l'URL brute absolue pour correspondre à 100% avec web.php sans erreur 404
                    deleteForm.action = window.location.origin + "/jours-feries/" + info.event.id;

                    deleteForm.submit();
                }
            }
        });

        calendar.render();
    });
</script>
@endsection

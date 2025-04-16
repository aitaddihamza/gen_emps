<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps - {{ $classe }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 100px;
        }

        .header h1 {
            margin: 10px 0 0;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .module {
            font-weight: bold;
            color: #333;
        }

        .prof,
        .salle,
        .semaine {
            font-size: 12px;
            color: #555;
        }

        /* Colors for different types of modules */
        .tp {
            background-color: #e0f8e0;
            border-left: 4px solid #1e6e1e;
        }

        .cours {
            background-color: #e2f0fb;
            border-left: 4px solid #0e4377;
        }

        .sport {
            background-color: #fdebd3;
            border-left: 4px solid #8c5d00;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ $logo }}" alt="School Logo">
        <h1>Emploi du Temps - {{ $classe }}</h1>
    </div>
    <table>
        <thead>
            <tr>
                <th>Jour</th>
                <th>08:30-10:30</th>
                <th>10:40-12:30</th>
                <th>13:30-15:30</th>
                <th>15:40-17:30</th>
            </tr>
        </thead>
        <tbody>
            @php
                $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
                $creneaux = ['08:30-10:30', '10:40-12:30', '13:30-15:30', '15:40-17:30'];
            @endphp
            @foreach ($jours as $jour)
                <tr>
                    <td>{{ $jour }}</td>
                    @foreach ($creneaux as $creneau)
                        <td>
                            @if (isset($timetable[$jour][$creneau]))
                                @foreach ($timetable[$jour][$creneau] as $cours)
                                    @php
                                        $class = '';
                                        if (str_contains(strtolower($cours['module']), 'tp')) {
                                            $class = 'tp';
                                        } elseif (str_contains(strtolower($cours['module']), 'esp')) {
                                            $class = 'sport';
                                        } else {
                                            $class = 'cours';
                                        }
                                    @endphp
                                    <div class="{{ $class }}">
                                        <div class="module">{{ $cours['module'] }}</div>
                                        <div class="prof">Prof: {{ $cours['prof'] }}</div>
                                        <div class="salle">Salle: {{ $cours['salle'] }}</div>
                                        <div class="semaine">Semaines:
                                            S{{ $cours['semaine_debut'] }}-S{{ $cours['semaine_fin'] }}</div>
                                    </div>
                                    <hr>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

@extends('layouts.pdf-alfa-eja')

@section('title', 'Relatório de Participação e Avaliação por Encontro')

@section('styles')
    <style>
        .table-header {
            font-size: 11px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 4px;
        }
        .table-data {
            font-size: 10px;
            padding: 3px;
        }
        .text-end {
            text-align: right;
        }
        .subtotal-row {
            background-color: #e8daea;
            font-weight: bold;
            font-size: 10px;
        }
    </style>
@endsection

@section('content')
<h2>Relatório de Participação e Avaliação por Encontro</h2>

@if($atividades->isEmpty())
    <p style="text-align: center; color: #666;">Nenhum encontro encontrado com os filtros aplicados.</p>
@else
<table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
    <thead>
        <tr>
            <th class="table-header">Ação</th>
            <th class="table-header">Momento</th>
            <th class="table-header">Município</th>
            <th class="table-header">Data</th>
            <th class="table-header">Período</th>
            <th class="table-header text-end">Qtd Previstas</th>
            <th class="table-header text-end">Qtd Presentes</th>
            <th class="table-header text-end">% Presentes</th>
            <th class="table-header text-end">Qtd Avaliações</th>
            <th class="table-header text-end">% Avaliações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($atividades->groupBy('evento_nome') as $nomeAcao => $grupo)
            @foreach($grupo as $a)
            @php
                $horaStr = substr($a->hora_inicio ?? '', 0, 5);
                $hora = (int) substr($horaStr, 0, 2);
                $periodoLabel = $hora < 12 ? 'Manhã' : ($hora < 18 ? 'Tarde' : 'Noite');

                $previstas = (int) $a->publico_esperado;
                $presentes = (int) $a->presentes_count;
                $avaliacoes = (int) $a->avaliacoes_count;

                $propPres = $previstas > 0 ? round($presentes / $previstas * 100, 1) : 0;
                $propAval = $presentes > 0 ? round($avaliacoes / $presentes * 100, 1) : 0;
            @endphp
            <tr style="border-bottom: 1px solid #ddd;">
                <td class="table-data">{{ $a->evento_nome ?? '—' }}</td>
                <td class="table-data">{{ $a->descricao ?? '—' }}</td>
                <td class="table-data">{{ $a->municipio_nome ?? '—' }}</td>
                <td class="table-data">{{ $a->dia ? \Carbon\Carbon::parse($a->dia)->format('d/m/Y') : '—' }}</td>
                <td class="table-data">{{ $horaStr ? $periodoLabel . ' (' . $horaStr . ')' : '—' }}</td>
                <td class="table-data text-end">{{ $previstas ?: '—' }}</td>
                <td class="table-data text-end">{{ $presentes }}</td>
                <td class="table-data text-end">{{ $previstas > 0 ? $propPres . '%' : '—' }}</td>
                <td class="table-data text-end">{{ $avaliacoes }}</td>
                <td class="table-data text-end">{{ $presentes > 0 ? $propAval . '%' : '—' }}</td>
            </tr>
            @endforeach

            @php
                $totalPrevistas = $grupo->sum('publico_esperado');
                $totalPresentes = $grupo->sum('presentes_count');
                $totalAvaliacoes = $grupo->sum('avaliacoes_count');
                $propTotPres = $totalPrevistas > 0 ? round($totalPresentes / $totalPrevistas * 100, 1) : 0;
                $propTotAval = $totalPresentes > 0 ? round($totalAvaliacoes / $totalPresentes * 100, 1) : 0;
            @endphp
            <tr class="subtotal-row">
                <td colspan="5" style="text-align: right; padding: 3px;">Subtotal — {{ $nomeAcao ?? 'Sem ação' }}</td>
                <td class="text-end">{{ $totalPrevistas ?: '—' }}</td>
                <td class="text-end">{{ $totalPresentes }}</td>
                <td class="text-end">{{ $totalPrevistas > 0 ? $propTotPres . '%' : '—' }}</td>
                <td class="text-end">{{ $totalAvaliacoes }}</td>
                <td class="text-end">{{ $totalPresentes > 0 ? $propTotAval . '%' : '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection

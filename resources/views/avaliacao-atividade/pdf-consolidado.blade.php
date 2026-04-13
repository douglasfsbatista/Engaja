<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatórios Consolidados — {{ $atividade->descricao ?? 'Momento' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; margin: 0 0 4px 0; color: #421944; }
        h2 { font-size: 14px; margin: 18px 0 8px 0; color: #421944; }
        h3 { font-size: 13px; margin: 14px 0 6px 0; color: #333; border-bottom: 2px solid #421944; padding-bottom: 4px; }
        .muted { color: #666; }
        .box { border: 1px solid #ddd; border-radius: 6px; padding: 10px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
        th { background: #f6f6f6; text-align: left; }
        .question { margin-top: 12px; }
        .answer { border: 1px solid #ddd; padding: 8px; border-radius: 4px; background: #fafafa; }
        .small { font-size: 11px; }
        .page-break { page-break-before: always; }
        .separator { border-top: 3px solid #421944; margin: 24px 0 16px 0; }
        .badge { display: inline-block; background: #421944; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 11px; }
    </style>
</head>
<body>
@php
    $evento = $atividade->evento;
    $checklistLabels = [
        'upload_evidencias'       => 'Fez o upload das evidências (fotos, vídeos com depoimentos) na pasta correspondente a essa ação dentro do Drive',
        'lista_presenca_digital'  => 'Conferiu as listas de presença digital (link acima), garantindo que todos os campos estejam devidamente preenchidos',
        'lista_presenca_impressa' => 'Conferiu as listas de presença impressa, garantindo que todos os campos estejam devidamente preenchidos',
        'upload_lista_impressa'   => 'Fez o upload das listas de presença impressas na pasta dentro do Drive, depois de devidamente conferida e ajustada',
    ];
@endphp

{{-- CAPA --}}
<h1>Relatórios Consolidados</h1>
<p class="muted">Todos os relatórios pós-ação para o momento abaixo</p>

<div class="box">
    <table>
        <tr>
            <th style="width: 25%;">Ação pedagógica</th>
            <td>{{ $evento->nome ?? '—' }}</td>
            <th style="width: 15%;">Momento</th>
            <td>{{ $atividade->descricao ?? '—' }}</td>
        </tr>
        <tr>
            <th>Data</th>
            <td>{{ $atividade->dia ? \Carbon\Carbon::parse($atividade->dia)->format('d/m/Y') : '—' }}</td>
            <th>Horário</th>
            <td>
                {{ $atividade->hora_inicio ? \Carbon\Carbon::parse($atividade->hora_inicio)->format('H:i') : '?' }} -
                {{ $atividade->hora_fim ? \Carbon\Carbon::parse($atividade->hora_fim)->format('H:i') : '?' }}
            </td>
        </tr>
        <tr>
            <th>Total de relatórios</th>
            <td colspan="3"><strong>{{ $relatorios->count() }}</strong> relatório(s)</td>
        </tr>
    </table>
</div>

<h2>Quadro Resumo de Público</h2>
<table>
    <tr><th>Quantidade prevista de participantes</th><td>{{ $resumoPublico['prevista'] ?? 0 }}</td></tr>
    <tr><th>Quantidade de inscritos</th><td>{{ $resumoPublico['inscritos'] ?? 0 }}</td></tr>
    <tr><th>Quantidade de presentes na ação</th><td>{{ $resumoPublico['presentes'] ?? 0 }}</td></tr>
    <tr><th>Participantes ligados aos movimentos sociais</th><td>{{ $resumoPublico['movimentos'] ?? 0 }}</td></tr>
    <tr><th>Participantes com vínculo com a prefeitura</th><td>{{ $resumoPublico['prefeitura'] ?? 0 }}</td></tr>
</table>

{{-- RELATÓRIOS INDIVIDUAIS --}}
<div class="separator"></div>

<h2>Perguntas e Respostas Consolidadas</h2>
<p class="muted">Cada pergunta abaixo reúne todas as respostas enviadas para este mesmo momento.</p>

@foreach($respostasPorPergunta as $indexPergunta => $itemPergunta)
    <div class="question">
        <div><strong>{{ $itemPergunta['pergunta'] }}</strong></div>

        @if($itemPergunta['respostas']->isEmpty())
            <div class="answer muted">Nenhuma resposta registrada.</div>
        @else
            @foreach($itemPergunta['respostas'] as $resposta)
                <div class="answer" style="margin-top: 6px;">
                    <div class="small muted">
                        <strong>Responsável:</strong> {{ $resposta['responsavel_nome'] }}
                        @if(!empty($resposta['atualizado_em']))
                            | <strong>Enviado em:</strong> {{ $resposta['atualizado_em']->format('d/m/Y') }}
                        @endif
                    </div>
                    <div style="margin-top: 4px;">{{ $resposta['resposta'] }}</div>
                </div>
            @endforeach
        @endif
    </div>

    @if(! $loop->last)
        <div style="margin-top: 12px; border-top: 1px dashed #bbb;"></div>
    @endif
@endforeach
</body>
</html>

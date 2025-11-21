<div {{ $attributes->merge(['class' => '']) }}>
    <h1 class="h4 fw-bold text-engaja mb-1">{{ $atividade->descricao ?? 'Momento' }}</h1>

    @php
        use Carbon\Carbon;

        $dia = Carbon::parse($atividade->dia)
            ->locale('pt_BR')
            ->translatedFormat('l, d \\d\\e F \\d\\e Y');

        $inicio = Carbon::parse($atividade->dia . ' ' . $atividade->hora_inicio);
        $fim = $atividade->hora_fim ? Carbon::parse($atividade->dia . ' ' . $atividade->hora_fim) : null;

        if ($fim && $fim->lessThanOrEqualTo($inicio)) {
            $fim->addDay();
        }

        $duracaoLabel = null;
        if ($fim) {
            $mins = $inicio->diffInMinutes($fim, false);
            if ($mins < 0) {
                $mins += 24 * 60;
            }
            $h = intdiv($mins, 60);
            $m = $mins % 60;
            $duracaoLabel = $h > 0 ? $h . 'h' . ($m ? ' ' . $m . 'min' : '') : $m . 'min';
        }
    @endphp

    @php
        $ocultarDetalhesExtras = request()?->routeIs('presenca.confirmar');
    @endphp

    <p class="text-muted mb-1">
        {{ $dia }} &bull; {{ $inicio->format('H:i') }}@if($fim) a {{ $fim->format('H:i') }}@endif
    </p>

    @if ($duracaoLabel)
        <p class="text-muted mb-1">Duração: {{ $duracaoLabel }}</p>
    @endif

    @if (!$ocultarDetalhesExtras && $atividade->municipio)
        <p class="text-muted mb-1">Município: {{ $atividade->municipio->nome_com_estado }}</p>
    @endif

    @if ($atividade->local)
        <p class="text-muted mb-1">Local: {{ $atividade->local }}</p>
    @endif

    @if(!$ocultarDetalhesExtras && !is_null($atividade->publico_esperado))
        <p class="text-muted mb-1">
            Público esperado: {{ number_format($atividade->publico_esperado, 0, ',', '.') }}
        </p>
    @endif

    @php
        $carga = $atividade->carga_horaria;
        $cargaFormatada = !is_null($carga) ? number_format($carga, 0, ',', '.') : null;
    @endphp

    @if(!$ocultarDetalhesExtras && $cargaFormatada)
        <p class="text-muted mb-1">Carga horária: {{ $cargaFormatada }}h</p>
    @endif
</div>

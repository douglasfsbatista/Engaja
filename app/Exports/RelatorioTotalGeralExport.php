<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\RelatorioQuantitativoController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RelatorioTotalGeralExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Request $request)
    {
    }

    public function collection(): Collection
    {
        $controller = new RelatorioQuantitativoController();
        $totalGeral = $this->callBuildTotalGeralData($controller);

        return $totalGeral->filter(fn ($r) => !isset($r['_is_total']));
    }

    public function headings(): array
    {
        return [
            'Região',
            'Município',
            'Previstos',
            'Com CPF',
            'Sem CPF',
            '% Com CPF',
        ];
    }

    public function map($row): array
    {
        $totalCpf = $row['metricas']['cpf']['com'] + $row['metricas']['cpf']['sem'];
        $pctCpf = $totalCpf > 0 ? $row['metricas']['cpf']['pct'] : '—';

        return [
            $row['regiao'] ?? '—',
            $row['municipio_nome'] ?? '—',
            $row['previstos'] ?: '—',
            $row['metricas']['cpf']['com'],
            $row['metricas']['cpf']['sem'],
            is_numeric($pctCpf) ? $pctCpf . '%' : $pctCpf,
        ];
    }

    private function callBuildTotalGeralData($controller): Collection
    {
        $reflection = new \ReflectionMethod(RelatorioQuantitativoController::class, 'buildTotalGeralData');
        $reflection->setAccessible(true);

        return $reflection->invoke($controller, $this->request);
    }
}

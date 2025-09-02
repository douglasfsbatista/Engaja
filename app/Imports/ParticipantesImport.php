<?php

namespace App\Imports;

use App\Models\Participante;
use App\Models\User;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ParticipantesImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{

    public Collection $importados;

    public function __construct()
    {
        // Inicializa como uma nova Collection vazia
        $this->importados = new Collection();
    }


    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        // 🔹 Tenta achar município, mas se não encontrar, deixa null
        // TODO melhorar performance evitando consulta a cada linha
        $municipioId = null;
        if (!empty($row['municipio'])) {
            $municipio = Municipio::whereRaw('LOWER(nome) = ?', [mb_strtolower(trim($row['municipio']))])->first();
            if ($municipio) {
                $municipioId = $municipio->id;
            }
        }

        // 🔹 Cria ou reaproveita usuário pelo email
        $email = strtolower(trim((string)($row['email'] ?? '')));
        $name  = trim((string)($row['nome'] ?? ''));

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $name !== '' ? $name : ($row['cpf'] ?? 'Participante'),
                'password' => Hash::make(Str::random(12)),
            ]
        );

        // 🔹 Normaliza data de entrada
        $dataEntrada = null;
        if (!empty($row['data_entrada'])) {
            try {
                $dataEntrada = Carbon::parse($row['data_entrada'])->format('Y-m-d');
            } catch (\Throwable $e) {
                $dataEntrada = null;
            }
        }

        // 🔹 Cria ou atualiza participante
        $participante = Participante::updateOrCreate(
            [
                'user_id'   => $user->id,
            ],
            [
                'municipio_id'   => $municipioId, // pode ser null
                'cpf'            => $row['cpf'] ?? null,
                'telefone'       => $row['telefone'] ?? null,
                'escola_unidade' => $row['escola_unidade'] ?? null,
                'data_entrada'   => $dataEntrada,
            ]
        );

        $this->importados->push($participante);

        return $participante;
    }
}

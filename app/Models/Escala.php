<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    protected $fillable = [
        'descricao',
        'opcao1',
        'opcao2',
        'opcao3',
        'opcao4',
        'opcao5',
    ];

    // Caso cada questão possa ter uma escala diferente
    public function questoes()
    {
        return $this->hasMany(Questao::class);
    }
}

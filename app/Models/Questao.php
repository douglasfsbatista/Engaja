<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questao extends Model
{
    use SoftDeletes;
    protected $fillable = ['indicador_id', 'escala_id', 'texto', 'tipo', 'fixa'];

    public function indicador(): BelongsTo
    {
        return $this->belongsTo(Indicador::class);
    }

    public function escala(): BelongsTo
    {
        return $this->belongsTo(Escala::class);
    }

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(TemplateAvaliacao::class, 'questao_template_avaliacaos')
            ->withPivot('ordem');
    }
}

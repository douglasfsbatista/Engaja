<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    protected $fillable = ['template_avaliacao_id', 'inscricao_id', 'atividade_id'];

    public function templateAvaliacao(): BelongsTo
    {
        return $this->belongsTo(TemplateAvaliacao::class);
    }
}

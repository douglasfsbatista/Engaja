<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestaoTemplateAvaliacao extends Model
{
    protected $fillable = ['template_avaliacao_id', 'questao_id', 'ordem'];

    public function templateAvaliacao(): BelongsTo
    {
        return $this->belongsTo(TemplateAvaliacao::class);
    }

    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }
}

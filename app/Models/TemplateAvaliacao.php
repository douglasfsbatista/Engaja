<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateAvaliacao extends Model
{
    protected $fillable = ['nome', 'descricao'];

    public function questoes(): HasMany
    {
        return $this->hasMany(QuestaoTemplateAvaliacao::class);
    }

    public function inscricao(): BelongsTo
    {
        return $this->belongsTo(Inscricao::class);
    }
    public function atividade(): BelongsTo
    {
        return $this->belongsTo(Atividade::class);
    }

    public function questoesTemplate(): HasMany
    {
        return $this->hasMany(QuestaoTemplateAvaliacao::class);
    }

}

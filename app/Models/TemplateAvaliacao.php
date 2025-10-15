<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateAvaliacao extends Model
{
    protected $fillable = ['nome', 'descricao'];

    public function questoesTemplate(): HasMany
    {
        return $this->hasMany(QuestaoTemplateAvaliacao::class)->orderBy('ordem');
    }

    public function questoes(): BelongsToMany
    {
        return $this->belongsToMany(Questao::class, 'questao_template_avaliacaos')
            ->withPivot('ordem')
            ->orderBy('questao_template_avaliacaos.ordem');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }
}

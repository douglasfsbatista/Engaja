@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-xl-10">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 fw-bold text-engaja mb-0">Editar avaliação</h1>
      <a href="{{ route('avaliacoes.show', $avaliacao) }}" class="btn btn-outline-secondary">Ver detalhes</a>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <form method="POST" action="{{ route('avaliacoes.update', $avaliacao) }}">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-md-6">
              <label for="atividade_id" class="form-label">Atividade</label>
              <select id="atividade_id" name="atividade_id"
                class="form-select @error('atividade_id') is-invalid @enderror" required>
                <option value="">Selecione...</option>
                @foreach ($atividades as $atividade)
                <option value="{{ $atividade->id }}"
                  @selected(old('atividade_id', $avaliacao->atividade_id) == $atividade->id)>
                  {{ $atividade->descricao }} — {{ $atividade->evento->nome ?? 'Sem evento' }}
                </option>
                @endforeach
              </select>
              @error('atividade_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="template_avaliacao_id" class="form-label">Modelo de avaliação</label>
              <select id="template_avaliacao_id" name="template_avaliacao_id"
                class="form-select @error('template_avaliacao_id') is-invalid @enderror" required>
                <option value="">Selecione...</option>
                @foreach ($templates as $template)
                <option value="{{ $template->id }}"
                  @selected(old('template_avaliacao_id', $avaliacao->template_avaliacao_id) == $template->id)>
                  {{ $template->nome }}
                </option>
                @endforeach
              </select>
              @error('template_avaliacao_id')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mt-4">
            @include('avaliacoes._questoes', [
                'templates' => $templates,
                'selectedTemplateId' => old('template_avaliacao_id', $avaliacao->template_avaliacao_id),
                'personalizacoes' => $personalizacoes ?? [],
                'respostas' => [],
                'exibirRespostas' => false,
                'evidenciasOptions' => $evidenciasOptions ?? [],
                'evidenciasData' => $evidenciasData ?? collect(),
                'escalasOptions' => $escalasOptions ?? [],
                'escalasData' => $escalasData ?? collect(),
                'tiposQuestao' => $tiposQuestao ?? [],
                'questoesAdicionais' => old('questoes_adicionais', $questoesAdicionais ?? []),
            ])
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('avaliacoes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-engaja">Salvar alterações</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

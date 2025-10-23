@php
  $selectedTemplateId = $selectedTemplateId ?? null;
  $personalizacoes = $personalizacoes ?? [];
  $respostas = $respostas ?? [];
  $exibirRespostas = $exibirRespostas ?? false;
  $tiposQuestao = $tiposQuestao ?? [];
  $evidenciasOptions = $evidenciasOptions ?? [];
  $evidenciasData = collect($evidenciasData ?? [])->keyBy('id');
  $escalasOptions = $escalasOptions ?? [];
  $escalasData = collect($escalasData ?? [])->keyBy('id');
  $questoesAdicionais = collect($questoesAdicionais ?? [])->values();
@endphp

<div id="blocos-questoes">
  @foreach ($templates as $template)
    @php
      $ativo = (string) $selectedTemplateId === (string) $template->id;
    @endphp

    <div class="card shadow-sm mb-3 template-questoes {{ $ativo ? '' : 'd-none' }}"
      data-template-block="{{ $template->id }}">
      <div class="card-header bg-white">
        <h2 class="h6 fw-semibold mb-0">Questoes do modelo: {{ $template->nome }}</h2>
      </div>

      <div class="card-body">
        @forelse ($template->questoes as $questao)
          @php
            $questaoKey = $questao->id;
            $personalizacao = $personalizacoes[$questaoKey] ?? [];
            $baseKey = "questoes.$questaoKey";

            $textoPersonalizado = $questao->fixa
              ? $questao->texto
              : old("$baseKey.texto", $personalizacao['texto'] ?? $questao->texto);

            $tipoPersonalizado = $questao->fixa
              ? $questao->tipo
              : old("$baseKey.tipo", $personalizacao['tipo'] ?? $questao->tipo);

            if (! array_key_exists($tipoPersonalizado, $tiposQuestao)) {
              $tipoAtual = $questao->tipo;
            } else {
              $tipoAtual = $tipoPersonalizado;
            }

            $evidenciaPersonalizada = $questao->fixa
              ? $questao->evidencia_id
              : old("$baseKey.evidencia_id", $personalizacao['evidencia_id'] ?? $questao->evidencia_id);

            $escalaPersonalizada = $questao->fixa
              ? $questao->escala_id
              : old("$baseKey.escala_id", $personalizacao['escala_id'] ?? $questao->escala_id);

            if ($tipoAtual !== 'escala') {
              $escalaAtual = $questao->fixa ? $questao->escala_id : null;
            } else {
              $escalaAtual = $escalaPersonalizada;
            }

            $indicadorAtual = $questao->indicador;
            if (! $questao->fixa && $evidenciaPersonalizada && $evidenciasData->has((int) $evidenciaPersonalizada)) {
              $indicadorAtual = $evidenciasData[(int) $evidenciaPersonalizada]->indicador;
            }
            $dimensaoAtual = optional($indicadorAtual)->dimensao;

            $escalaSelecionada = null;
            if ($tipoAtual === 'escala') {
              if ($escalaAtual && $escalasData->has((int) $escalaAtual)) {
                $escalaSelecionada = $escalasData[(int) $escalaAtual];
              } elseif ($questao->escala) {
                $escalaSelecionada = $questao->escala;
              }
            }

            $opcoesEscala = $escalaSelecionada
              ? collect([
                  $escalaSelecionada->opcao1 ?? null,
                  $escalaSelecionada->opcao2 ?? null,
                  $escalaSelecionada->opcao3 ?? null,
                  $escalaSelecionada->opcao4 ?? null,
                  $escalaSelecionada->opcao5 ?? null,
                ])->filter()
              : collect();

            $respostaAtual = $respostas[$questaoKey] ?? null;
          @endphp

          <div class="mb-4 question-config" data-questao="{{ $questaoKey }}">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="form-label fw-semibold mb-0">
                Questao {{ $questao->ordem ?? $loop->iteration }}
              </span>
              <span class="badge {{ $questao->fixa ? 'bg-light text-muted border' : 'bg-primary-subtle text-primary border-primary' }}">
                {{ $questao->fixa ? 'Fixa' : 'Personalizavel' }}
              </span>
            </div>

            <p class="text-muted small mb-2">
              Indicador: {{ $indicadorAtual->descricao ?? '-' }}
              @if ($dimensaoAtual)
                &bull; Dimensao: {{ $dimensaoAtual->descricao ?? '-' }}
              @endif
            </p>

            @if ($questao->fixa)
              <p class="fw-semibold mb-2">{{ $questao->texto }}</p>
              <div class="row g-2 text-muted small mb-3">
                <div class="col-md-4">Tipo: {{ $tiposQuestao[$questao->tipo] ?? ucfirst($questao->tipo) }}</div>
                <div class="col-md-4">
                  Evidencia:
                  {{ $questao->evidencia->descricao ?? 'Sem evidencia' }}
                </div>
                <div class="col-md-4">
                  Escala:
                  {{ $questao->escala->descricao ?? ($questao->tipo === 'escala' ? 'Defina uma escala' : '---') }}
                </div>
              </div>
            @else
              <div class="row g-3 align-items-start mb-3">
                <div class="col-12">
                  <label class="form-label small text-muted" for="questoes-{{ $questaoKey }}-texto">
                    Ajuste o enunciado para esta avaliacao
                  </label>
                  <textarea
                    class="form-control @error("questoes.$questaoKey.texto") is-invalid @enderror"
                    id="questoes-{{ $questaoKey }}-texto"
                    name="questoes[{{ $questaoKey }}][texto]"
                    rows="3"
                  >{{ $textoPersonalizado }}</textarea>
                  @error("questoes.$questaoKey.texto")
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label small text-muted" for="questoes-{{ $questaoKey }}-tipo">Tipo de resposta</label>
                  <select
                    class="form-select @error("questoes.$questaoKey.tipo") is-invalid @enderror"
                    id="questoes-{{ $questaoKey }}-tipo"
                    name="questoes[{{ $questaoKey }}][tipo]"
                    data-tipo-select
                  >
                    @foreach ($tiposQuestao as $valor => $rotulo)
                      <option value="{{ $valor }}" @selected($tipoAtual === $valor)>{{ $rotulo }}</option>
                    @endforeach
                  </select>
                  @error("questoes.$questaoKey.tipo")
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label class="form-label small text-muted" for="questoes-{{ $questaoKey }}-evidencia_id">Evidencia</label>
                  <select
                    class="form-select @error("questoes.$questaoKey.evidencia_id") is-invalid @enderror"
                    id="questoes-{{ $questaoKey }}-evidencia_id"
                    name="questoes[{{ $questaoKey }}][evidencia_id]"
                    data-evidencia-select
                  >
                    <option value="">Selecione...</option>
                    @foreach ($evidenciasOptions as $id => $descricao)
                      <option value="{{ $id }}" @selected((string) $evidenciaPersonalizada === (string) $id)>{{ $descricao }}</option>
                    @endforeach
                  </select>
                  @error("questoes.$questaoKey.evidencia_id")
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4 escala-wrapper {{ $tipoAtual === 'escala' ? '' : 'd-none' }}" data-escala-wrapper>
                  <label class="form-label small text-muted" for="questoes-{{ $questaoKey }}-escala_id">
                    Escala (quando tipo = Escala)
                  </label>
                  <select
                    class="form-select @error("questoes.$questaoKey.escala_id") is-invalid @enderror"
                    id="questoes-{{ $questaoKey }}-escala_id"
                    name="questoes[{{ $questaoKey }}][escala_id]"
                    data-escala-select
                  >
                    <option value="">Selecione...</option>
                    @foreach ($escalasOptions as $id => $descricao)
                      <option value="{{ $id }}" @selected((string) $escalaAtual === (string) $id)>{{ $descricao }}</option>
                    @endforeach
                  </select>
                  @error("questoes.$questaoKey.escala_id")
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            @endif

            @if ($exibirRespostas)
              <div class="mt-3">
                <label class="form-label fw-semibold d-block mb-2">Resposta</label>

                @switch($tipoAtual)
                  @case('escala')
                    @if ($opcoesEscala->isEmpty())
                      <p class="text-muted small mb-0">
                        Configure opcoes na escala associada antes de registrar respostas.
                      </p>
                    @else
                      <div class="d-flex flex-wrap gap-2">
                        @foreach ($opcoesEscala as $idx => $opcao)
                          @php $inputId = 'questao-'.$questaoKey.'-escala-'.$idx; @endphp
                          <div class="form-check">
                            <input class="form-check-input"
                              type="radio"
                              name="respostas[{{ $questaoKey }}]"
                              id="{{ $inputId }}"
                              value="{{ $opcao }}"
                              {{ (string) $respostaAtual === (string) $opcao ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $inputId }}">{{ $opcao }}</label>
                          </div>
                        @endforeach
                      </div>
                    @endif
                    @break

                  @case('numero')
                    <input type="number"
                      step="any"
                      class="form-control"
                      name="respostas[{{ $questaoKey }}]"
                      value="{{ $respostaAtual }}">
                    @break

                  @case('boolean')
                    <div class="d-flex gap-3">
                      @foreach (['1' => 'Sim', '0' => 'Nao'] as $valorBooleano => $rotulo)
                        @php $inputId = 'questao-'.$questaoKey.'-boolean-'.$valorBooleano; @endphp
                        <div class="form-check">
                          <input class="form-check-input"
                            type="radio"
                            name="respostas[{{ $questaoKey }}]"
                            id="{{ $inputId }}"
                            value="{{ $valorBooleano }}"
                            {{ (string) $respostaAtual === (string) $valorBooleano ? 'checked' : '' }}>
                          <label class="form-check-label" for="{{ $inputId }}">{{ $rotulo }}</label>
                        </div>
                      @endforeach
                    </div>
                    @break

                  @default
                    <textarea class="form-control" rows="3"
                      name="respostas[{{ $questaoKey }}]">{{ $respostaAtual }}</textarea>
                @endswitch
              </div>
            @endif
          </div>
        @empty
          <p class="text-muted mb-0">Nenhuma questao vinculada a este modelo.</p>
        @endforelse
      </div>
    </div>
  @endforeach
</div>

<div class="card shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <div>
      <h2 class="h6 fw-semibold mb-0">Questoes adicionais</h2>
      <small class="text-muted">Personalize a avaliacao adicionando novas questoes especificas.</small>
    </div>
    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-questao-adicional">Adicionar questao</button>
  </div>
  <div class="card-body">
    <div id="questoes-adicionais-container">
      <p class="text-muted small mb-3 {{ $questoesAdicionais->isEmpty() ? '' : 'd-none' }}" data-adicional-empty>Nenhuma questao adicional adicionada.</p>

      @foreach ($questoesAdicionais as $index => $questao)
        @php
          $baseKey = "questoes_adicionais.$index";
          $questaoId = $questao['id'] ?? null;
          $texto = old("$baseKey.texto", $questao['texto'] ?? '');
          $tipoSelecionado = old("$baseKey.tipo", $questao['tipo'] ?? 'texto');
          if (! array_key_exists($tipoSelecionado, $tiposQuestao)) {
            $tipoSelecionado = 'texto';
          }
          $evidenciaSelecionada = old("$baseKey.evidencia_id", $questao['evidencia_id'] ?? '');
          $escalaSelecionada = old("$baseKey.escala_id", $questao['escala_id'] ?? '');
          $ordemSelecionada = old("$baseKey.ordem", $questao['ordem'] ?? '');
          $textoErro = $errors->has("$baseKey.texto");
          $tipoErro = $errors->has("$baseKey.tipo");
          $evidenciaErro = $errors->has("$baseKey.evidencia_id");
          $escalaErro = $errors->has("$baseKey.escala_id");
          $ordemErro = $errors->has("$baseKey.ordem");
        @endphp

        <div class="mb-3 border rounded-3 p-3 question-config" data-adicional-card data-existing="{{ $questaoId ? 'true' : 'false' }}">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <h3 class="h6 fw-semibold mb-0">Questao adicional <span data-adicional-position>{{ $loop->iteration }}</span></h3>
            <button type="button" class="btn btn-sm btn-outline-danger js-remove-adicional">Remover</button>
          </div>

          @if ($questaoId)
          <input type="hidden" name="questoes_adicionais[{{ $index }}][id]" value="{{ $questaoId }}">
          @endif
          <input type="hidden" class="questao-adicional-delete" name="questoes_adicionais[{{ $index }}][_delete]" value="0">

          <div class="mb-3">
            <label class="form-label" for="questoes-adicionais-{{ $index }}-texto">Enunciado</label>
            <textarea id="questoes-adicionais-{{ $index }}-texto" name="questoes_adicionais[{{ $index }}][texto]" rows="3" class="form-control{{ $textoErro ? ' is-invalid' : '' }}">{{ $texto }}</textarea>
            @error("$baseKey.texto")
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label" for="questoes-adicionais-{{ $index }}-tipo">Tipo</label>
              <select id="questoes-adicionais-{{ $index }}-tipo" name="questoes_adicionais[{{ $index }}][tipo]" class="form-select{{ $tipoErro ? ' is-invalid' : '' }}" data-tipo-select>
                @foreach ($tiposQuestao as $valor => $rotulo)
                <option value="{{ $valor }}" @selected($tipoSelecionado === $valor)>{{ $rotulo }}</option>
                @endforeach
              </select>
              @error("$baseKey.tipo")
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label" for="questoes-adicionais-{{ $index }}-evidencia_id">Evidencia</label>
              <select id="questoes-adicionais-{{ $index }}-evidencia_id" name="questoes_adicionais[{{ $index }}][evidencia_id]" class="form-select{{ $evidenciaErro ? ' is-invalid' : '' }}">
                <option value="">Selecione...</option>
                @foreach ($evidenciasOptions as $id => $descricao)
                <option value="{{ $id }}" @selected((string) $evidenciaSelecionada === (string) $id)>{{ $descricao }}</option>
                @endforeach
              </select>
              @error("$baseKey.evidencia_id")
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-4">
              <label class="form-label" for="questoes-adicionais-{{ $index }}-ordem">Ordem</label>
              <input type="number" id="questoes-adicionais-{{ $index }}-ordem" name="questoes_adicionais[{{ $index }}][ordem]" class="form-control{{ $ordemErro ? ' is-invalid' : '' }}" min="1" max="999" value="{{ $ordemSelecionada }}" placeholder="1, 2, 3...">
              @error("$baseKey.ordem")
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mt-3" data-escala-wrapper>
            <label class="form-label" for="questoes-adicionais-{{ $index }}-escala_id">Escala (quando tipo = Escala)</label>
            <select id="questoes-adicionais-{{ $index }}-escala_id" name="questoes_adicionais[{{ $index }}][escala_id]" class="form-select{{ $escalaErro ? ' is-invalid' : '' }}">
              <option value="">Selecione...</option>
              @foreach ($escalasOptions as $id => $descricao)
              <option value="{{ $id }}" @selected((string) $escalaSelecionada === (string) $id)>{{ $descricao }}</option>
              @endforeach
            </select>
            @error("$baseKey.escala_id")
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<template id="questao-adicional-template">
  <div class="mb-3 border rounded-3 p-3 question-config" data-adicional-card data-existing="false">
    <div class="d-flex justify-content-between align-items-start mb-3">
      <h3 class="h6 fw-semibold mb-0">Questao adicional <span data-adicional-position></span></h3>
      <button type="button" class="btn btn-sm btn-outline-danger js-remove-adicional">Remover</button>
    </div>

    <input type="hidden" class="questao-adicional-delete" name="questoes_adicionais[__INDEX__][_delete]" value="0">

    <div class="mb-3">
      <label class="form-label" for="questoes-adicionais-__INDEX__-texto">Enunciado</label>
      <textarea id="questoes-adicionais-__INDEX__-texto" name="questoes_adicionais[__INDEX__][texto]" rows="3" class="form-control"></textarea>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label" for="questoes-adicionais-__INDEX__-tipo">Tipo</label>
        <select id="questoes-adicionais-__INDEX__-tipo" name="questoes_adicionais[__INDEX__][tipo]" class="form-select" data-tipo-select>
          @foreach ($tiposQuestao as $valor => $rotulo)
          <option value="{{ $valor }}" @selected($valor === 'texto')>{{ $rotulo }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label" for="questoes-adicionais-__INDEX__-evidencia_id">Evidencia</label>
        <select id="questoes-adicionais-__INDEX__-evidencia_id" name="questoes_adicionais[__INDEX__][evidencia_id]" class="form-select">
          <option value="">Selecione...</option>
          @foreach ($evidenciasOptions as $id => $descricao)
          <option value="{{ $id }}">{{ $descricao }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label" for="questoes-adicionais-__INDEX__-ordem">Ordem</label>
        <input type="number" id="questoes-adicionais-__INDEX__-ordem" name="questoes_adicionais[__INDEX__][ordem]" class="form-control" min="1" max="999" placeholder="1, 2, 3...">
      </div>
    </div>

    <div class="mt-3" data-escala-wrapper>
      <label class="form-label" for="questoes-adicionais-__INDEX__-escala_id">Escala (quando tipo = Escala)</label>
      <select id="questoes-adicionais-__INDEX__-escala_id" name="questoes_adicionais[__INDEX__][escala_id]" class="form-select">
        <option value="">Selecione...</option>
        @foreach ($escalasOptions as $id => $descricao)
        <option value="{{ $id }}">{{ $descricao }}</option>
        @endforeach
      </select>
    </div>
  </div>
</template>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selectTemplate = document.getElementById('template_avaliacao_id');
    const blocks = document.querySelectorAll('[data-template-block]');

  function toggleBlocks() {
    if (!selectTemplate) {
      return;
    }

    const selecionado = selectTemplate.value;
    blocks.forEach(block => {
      const ativo = block.getAttribute('data-template-block') === selecionado;
      block.classList.toggle('d-none', !ativo);
      block.querySelectorAll('input, textarea, select').forEach(field => {
        field.disabled = !ativo;
      });
    });
  }

  function toggleEscala(select) {
    const questionContainer = select.closest('.question-config');
    if (!questionContainer) {
      return;
    }

    const escalaWrapper = questionContainer.querySelector('[data-escala-wrapper]');
    if (!escalaWrapper) {
      return;
    }

    const mostrar = select.value === 'escala';
    escalaWrapper.classList.toggle('d-none', !mostrar);
  }

  if (selectTemplate) {
    selectTemplate.addEventListener('change', toggleBlocks);
    toggleBlocks();
  }

  document.addEventListener('change', (event) => {
    if (event.target.matches('select[name$="[escala_id]"]')) {
      const card = event.target.closest('.question-config');
      if (!card) {
        return;
      }

      const tipoSelect = card.querySelector('[data-tipo-select]');
      if (!tipoSelect) {
        return;
      }

      if (event.target.value && tipoSelect.value !== 'escala') {
        tipoSelect.value = 'escala';
        tipoSelect.dispatchEvent(new Event('change', { bubbles: true }));
      }

      if (!event.target.value && tipoSelect.value === 'escala') {
        tipoSelect.value = 'texto';
        tipoSelect.dispatchEvent(new Event('change', { bubbles: true }));
      }
    }
  });

  function attachTipoListener(select) {
    if (!select || select.dataset.tipoListener === 'true') {
      return;
    }

    select.addEventListener('change', () => toggleEscala(select));
    toggleEscala(select);
    select.dataset.tipoListener = 'true';
  }

  document.querySelectorAll('[data-tipo-select]').forEach(select => attachTipoListener(select));

  const adicionaisContainer = document.getElementById('questoes-adicionais-container');
  const addAdicionalButton = document.getElementById('btn-add-questao-adicional');
  const adicionalTemplate = document.getElementById('questao-adicional-template');

  function updateAdicionaisPositions() {
    if (!adicionaisContainer) {
      return;
    }

    const cards = Array.from(adicionaisContainer.querySelectorAll('[data-adicional-card]'));
    const ativos = cards.filter(card => !card.classList.contains('d-none'));

    ativos.forEach((card, index) => {
      const marcador = card.querySelector('[data-adicional-position]');
      if (marcador) {
        marcador.textContent = index + 1;
      }
    });

    const emptyMessage = adicionaisContainer.querySelector('[data-adicional-empty]');
    if (emptyMessage) {
      emptyMessage.classList.toggle('d-none', ativos.length > 0);
    }
  }

  function setupAdicionalCard(card) {
    if (!card) {
      return;
    }

    attachTipoListener(card.querySelector('[data-tipo-select]'));

    const removeButton = card.querySelector('.js-remove-adicional');
    if (removeButton) {
      removeButton.addEventListener('click', () => {
        const deleteField = card.querySelector('.questao-adicional-delete');
        const isExisting = card.getAttribute('data-existing') === 'true';

        if (isExisting && deleteField) {
          deleteField.value = '1';
          card.classList.add('d-none');
          card.querySelectorAll('input, textarea, select').forEach(field => {
            const name = field.getAttribute('name');
            if (!name) {
              return;
            }

            if (name.endsWith('[id]') || name.endsWith('[_delete]')) {
              field.disabled = false;
            } else {
              field.disabled = true;
            }
          });
        } else {
          card.remove();
        }

        updateAdicionaisPositions();
      });
    }
  }

  if (adicionaisContainer) {
    adicionaisContainer.addEventListener('change', (event) => {
      if (event.target.matches('[data-tipo-select]')) {
        toggleEscala(event.target);
      }
    });

    adicionaisContainer.querySelectorAll('[data-adicional-card]').forEach(card => setupAdicionalCard(card));
    updateAdicionaisPositions();
  }

  if (addAdicionalButton && adicionalTemplate && adicionaisContainer) {
    addAdicionalButton.addEventListener('click', () => {
      const index = adicionaisContainer.querySelectorAll('[data-adicional-card]').length;
      const fragment = document.createElement('div');
      fragment.innerHTML = adicionalTemplate.innerHTML.replace(/__INDEX__/g, index);
      const card = fragment.firstElementChild;
      adicionaisContainer.appendChild(card);
      setupAdicionalCard(card);
      updateAdicionaisPositions();
    });
  }
});
</script>

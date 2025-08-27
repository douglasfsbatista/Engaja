@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 fw-bold mb-4 text-engaja">Meu perfil</h1>

    {{-- Mensagens globais --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success">Dados do perfil atualizados com sucesso.</div>
    @elseif (session('status') === 'password-updated')
        <div class="alert alert-success">Senha atualizada com sucesso.</div>
    @elseif (session('status') === 'verification-link-sent')
        <div class="alert alert-info">Um novo link de verificação foi enviado para seu e-mail.</div>
    @endif

    <div class="row g-4">
        {{-- DADOS BÁSICOS --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <strong>Informações do perfil</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('patch')

                        {{-- Nome --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input id="name" type="text"
                                   name="name"
                                   value="{{ old('name', $user->name ?? auth()->user()->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- E-mail --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input id="email" type="email"
                                   name="email"
                                   value="{{ old('email', $user->email ?? auth()->user()->email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @php($u = $user ?? auth()->user())
                            @if ($u instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $u->hasVerifiedEmail())
                                <div class="alert alert-warning mt-3 d-flex align-items-center" role="alert">
                                    <div class="me-2">Seu e-mail ainda não foi verificado.</div>
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            Reenviar verificação
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-engaja" type="submit">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ALTERAR SENHA --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <strong>Atualizar senha</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate>
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha atual</label>
                            <input id="current_password" type="password"
                                   name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nova senha</label>
                            <input id="password" type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mínimo 8 caracteres. Use letras, números e símbolos.</div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
                            <input id="password_confirmation" type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   required autocomplete="new-password">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-engaja" type="submit">Atualizar senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EXCLUIR CONTA --}}
        <div class="col-12">
            <div class="card border-danger-subtle shadow-sm">
                <div class="card-header bg-white text-danger">
                    <strong>Excluir conta</strong>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Esta ação é irreversível. Todos os seus dados serão removidos.
                    </p>

                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Tem certeza que deseja excluir sua conta?');">
                        @csrf
                        @method('delete')

                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="password_delete" class="form-label">Confirme sua senha</label>
                                <input id="password_delete" type="password"
                                       name="password"
                                       class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                       required>
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8 d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-danger">
                                    Excluir minha conta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

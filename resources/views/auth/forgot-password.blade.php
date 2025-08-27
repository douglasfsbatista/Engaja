@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row w-100">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white text-center">
                    <h5 class="mb-0 fw-bold text-engaja">Esqueci minha senha</h5>
                </div>

                <div class="card-body">
                    <p class="text-muted">
                        Informe seu e-mail e enviaremos um link para você redefinir sua senha.
                    </p>

                    {{-- status de sucesso (link enviado) --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
                        @csrf

                        {{-- E-mail --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}" class="btn btn-link p-0">Voltar ao login</a>
                            <button type="submit" class="btn btn-engaja">
                                Enviar link
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center">
                    <small class="text-muted">Verifique sua caixa de entrada e o spam.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

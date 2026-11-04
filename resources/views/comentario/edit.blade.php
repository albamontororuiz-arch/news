@extends('template.base')

@section('title', 'Editar comentario · Banditore')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        {{-- Volver a la noticia --}}
        <div class="mb-4">
            <a href="{{ route('noticia.show', $comentario->noticia_id) }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a la noticia
            </a>
        </div>

        {{-- Formulario de edición --}}
        <div class="card border-0 shadow-sm p-4 p-md-5">

            <div class="border-bottom pb-3 mb-4">
                <h1 class="h2 fw-bold">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Editar comentario
                </h1>

                <p class="text-muted small mb-0">
                    Puedes modificar tu comentario durante los primeros 5 minutos.
                </p>
            </div>

            <form action="{{ route('comentario.update', $comentario) }}"
                  method="POST">

                @csrf
                @method('PUT')

                {{-- Mensaje --}}
                <div class="mb-3">
                    <label for="mensaje" class="form-label fw-semibold">
                        Tu comentario
                    </label>

                    <textarea name="mensaje"
                              id="mensaje"
                              rows="5"
                              class="form-control @error('mensaje') is-invalid @enderror"
                              required>{{ old('mensaje', $comentario->mensaje) }}</textarea>

                    @error('mensaje')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Valoración --}}
                <div class="mb-4">
                    <label for="valoracion" class="form-label fw-semibold">
                        Valoración
                    </label>

                    <select name="valoracion"
                            id="valoracion"
                            class="form-select @error('valoracion') is-invalid @enderror"
                            required>

                        <option value="pos"
                            {{ old('valoracion', $comentario->valoracion) === 'pos' ? 'selected' : '' }}>
                            Positiva
                        </option>

                        <option value="neg"
                            {{ old('valoracion', $comentario->valoracion) === 'neg' ? 'selected' : '' }}>
                            Negativa
                        </option>

                    </select>

                    @error('valoracion')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Guardar cambios
                    </button>

                    <a href="{{ route('noticia.show', $comentario->noticia_id) }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>
</div>

@endsection

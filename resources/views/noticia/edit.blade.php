@extends('template.base')

@section('title', 'Editar Noticia · Banditore')

@section('content')

<div class="row g-5">

    {{-- COLUMNA PRINCIPAL --}}
    <div class="col-md-8">

        {{-- Volver a la noticia --}}
        <div class="mb-4">
            <a href="{{ route('noticia.show', $noticia) }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a la noticia
            </a>
        </div>

        {{-- FORMULARIO --}}
        <div class="card shadow-sm border-0 bg-white p-4 p-md-5">

            <div class="border-bottom pb-3 mb-4">

                <h1 class="h2 fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square text-primary me-2"></i>
                    Editar noticia
                </h1>

                <p class="text-muted small mb-0">
                    Puedes modificar esta noticia durante los primeros
                    20 minutos desde su publicación.
                </p>

            </div>

            <form action="{{ route('noticia.update', $noticia) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- TÍTULO --}}
                <div class="mb-4">

                    <label for="titulo" class="form-label fw-semibold">
                        Título de la noticia
                    </label>

                    <input type="text"
                           name="titulo"
                           id="titulo"
                           class="form-control form-control-lg @error('titulo') is-invalid @enderror"
                           value="{{ old('titulo', $noticia->titulo) }}"
                           maxlength="200"
                           required>

                    @error('titulo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Entre 3 y 200 caracteres.
                    </div>

                </div>

                {{-- TEXTO --}}
                <div class="mb-4">

                    <label for="texto" class="form-label fw-semibold">
                        Contenido de la noticia
                    </label>

                    <textarea name="texto"
                              id="texto"
                              rows="12"
                              class="form-control font-monospace @error('texto') is-invalid @enderror"
                              placeholder="# Escribe aquí el contenido...">{{ old('texto', $noticia->texto) }}</textarea>

                    @error('texto')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- ARCHIVO MARKDOWN O TXT --}}
                <div class="mb-4">

                    <label for="archivo_md" class="form-label fw-semibold">
                        Sustituir contenido mediante archivo MD o TXT
                    </label>

                    <input type="file"
                           name="archivo_md"
                           id="archivo_md"
                           class="form-control @error('archivo_md') is-invalid @enderror"
                           accept=".md,.txt,text/plain,text/markdown">

                    @error('archivo_md')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Opcional. Máximo 2 MB. Si seleccionas un archivo,
                        su contenido sustituirá al texto del formulario.
                    </div>

                </div>

                {{-- BOTONES --}}
                <div class="d-flex gap-2 flex-wrap">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Guardar cambios
                    </button>

                    <a href="{{ route('noticia.show', $noticia) }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>

    {{-- BARRA LATERAL --}}
    <div class="col-md-4">

        <div class="position-sticky" style="top: 2rem;">

            <div class="p-4 mb-3 bg-light rounded">

                <h4 class="fst-italic">
                    <i class="bi bi-info-circle me-1"></i>
                    Información
                </h4>

                <p>
                    Estás editando:
                </p>

                <p class="fw-bold">
                    {{ $noticia->titulo }}
                </p>

                <p class="small text-muted mb-0">
                    Publicada el
                    {{ $noticia->created_at->translatedFormat('d/m/Y H:i') }}
                </p>

            </div>

            <div class="p-4">

                <h4 class="fst-italic">Normas de edición</h4>

                <ul>
                    <li>Solo puedes editar durante los primeros 20 minutos.</li>
                    <li>Puedes modificar el título y el contenido.</li>
                    <li>También puedes sustituir el contenido mediante un archivo MD o TXT.</li>
                </ul>

            </div>

        </div>

    </div>

</div>

@endsection

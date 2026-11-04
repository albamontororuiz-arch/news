
@extends('template.base')

@section('title', 'Noticia · Banditore')

@section('content')
<div class="row g-5">

    {{-- COLUMNA PRINCIPAL --}}
    <div class="col-md-8">

        {{-- Volver a portada --}}
        <div class="mb-4">
            <a href="{{ route('noticia.index') }}"
               class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Volver a portada
            </a>
        </div>

        {{-- NOTICIA --}}
        <article class="blog-post">

            <h1 class="blog-post-title fw-bold">
                {{ $noticia->titulo }}
            </h1>

            <p class="blog-post-meta">
                <i class="bi bi-calendar3 me-1"></i>
                Publicada el
                {{ $noticia->created_at->translatedFormat('d/m/Y H:i') }}
            </p>

            <hr>

            {{-- Contenido interpretado como Markdown --}}
            <div class="blog-post-content mb-4">
                {!! \Illuminate\Support\Str::markdown($noticia->texto) !!}
            </div>

            {{-- Acciones de la noticia --}}
            <div class="d-flex gap-2 mb-5">

                @if($noticia->sePuedeEditar())
                    <a href="{{ route('noticia.edit', $noticia) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-1"></i>
                        Editar noticia
                    </a>
                @else
                    <button class="btn btn-outline-secondary btn-sm"
                            type="button" disabled>
                        <i class="bi bi-lock me-1"></i>
                        Edición no disponible
                    </button>
                @endif

            </div>
        </article>

        {{-- COMENTARIOS --}}
        <section id="comentarios" class="mt-5">

            <h3 class="fw-bold border-bottom pb-3 mb-4">
                <i class="bi bi-chat-left-text me-2 text-primary"></i>
                Comentarios ({{ $noticia->comentarios->count() }})
            </h3>

            {{-- Listado de comentarios --}}
            @forelse($noticia->comentarios as $comentario)

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">

                        <div class="d-flex justify-content-between
                                    align-items-center flex-wrap gap-2 mb-2">

                            <span class="fw-semibold">
                                <i class="bi bi-person-circle me-1"></i>
                                Comentario #{{ $comentario->id }}
                            </span>

                            <small class="text-muted">
                                {{ $comentario->created_at->translatedFormat('d/m/Y H:i') }}
                            </small>

                        </div>

                        {{-- Valoración --}}
                        @if($comentario->valoracion === 'pos')
                            <span class="badge bg-success mb-2">
                                <i class="bi bi-hand-thumbs-up me-1"></i>
                                Positiva
                            </span>
                        @else
                            <span class="badge bg-danger mb-2">
                                <i class="bi bi-hand-thumbs-down me-1"></i>
                                Negativa
                            </span>
                        @endif

                        <p class="mb-3">
                            {{ $comentario->mensaje }}
                        </p>

                        {{-- Modificar y borrar comentario --}}
                        @if($comentario->sePuedeModificarOBorrar())

                            <div class="d-flex gap-2">

                                <a href="{{ route('comentario.edit', $comentario) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil me-1"></i>
                                    Editar
                                </a>

                                <form action="{{ route('comentario.destroy', $comentario) }}"
                                      method="POST"
                                      class="confirm-dialog"
                                      data-message="¿Seguro que quieres eliminar este comentario?">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i>
                                        Borrar
                                    </button>
                                </form>

                            </div>

                        @else
                            <small class="text-muted">
                                <i class="bi bi-lock me-1"></i>
                                Ya no se puede modificar ni borrar.
                            </small>
                        @endif

                    </div>
                </div>

            @empty

                <div class="alert alert-light border text-center py-4">
                    <i class="bi bi-chat-square-text fs-2 text-muted"></i>
                    <p class="mb-0 mt-2">
                        Todavía no hay comentarios.
                        ¡Sé el primero en participar!
                    </p>
                </div>

            @endforelse

        </section>

        {{-- FORMULARIO PARA PUBLICAR COMENTARIO --}}
        <section class="mt-5 mb-5">

            <div class="card border-0 shadow-sm p-4">

                <h3 class="fw-bold mb-3">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                    Añadir un comentario
                </h3>

                <form action="{{ route('comentario.store') }}"
                      method="POST">
                    @csrf

                    {{-- Identificador de la noticia --}}
                    <input type="hidden"
                           name="noticia_id"
                           value="{{ $noticia->id }}">

                    {{-- Mensaje --}}
                    <div class="mb-3">
                        <label for="mensaje" class="form-label fw-semibold">
                            Tu comentario
                        </label>

                        <textarea name="mensaje"
                                  id="mensaje"
                                  rows="4"
                                  class="form-control @error('mensaje') is-invalid @enderror"
                                  placeholder="Escribe aquí tu opinión..."
                                  required>{{ old('mensaje') }}</textarea>

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
                            <option value="">Selecciona una valoración</option>

                            <option value="pos"
                                {{ old('valoracion') === 'pos' ? 'selected' : '' }}>
                                Positiva
                            </option>

                            <option value="neg"
                                {{ old('valoracion') === 'neg' ? 'selected' : '' }}>
                                Negativa
                            </option>
                        </select>

                        @error('valoracion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i>
                        Publicar comentario
                    </button>

                </form>

            </div>

        </section>

    </div>

    {{-- BARRA LATERAL --}}
    <div class="col-md-4">

        <div class="position-sticky" style="top: 2rem;">

            <div class="p-4 mb-3 bg-light rounded">
                <h4 class="fst-italic">Sobre Banditore</h4>

                <p class="mb-0">
                    Un espacio para compartir noticias, opiniones
                    y debatir sobre la actualidad.
                </p>
            </div>

            <div class="p-4">
                <h4 class="fst-italic">Normas de participación</h4>

                <ul class="mb-0">
                    <li>Las noticias se pueden editar durante los primeros 20 minutos.</li>
                    <li>Los comentarios se pueden modificar o borrar durante los primeros 5 minutos.</li>
                    <li>Una noticia con comentarios no se puede borrar.</li>
                </ul>
            </div>

        </div>

    </div>

</div>
@endsection

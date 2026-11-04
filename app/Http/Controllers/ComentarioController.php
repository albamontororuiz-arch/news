<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Noticia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ComentarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validamos los datos recibidos del formulario
        $datos = $request->validate([
            'noticia_id' => 'required|integer|exists:noticia,id',
            'mensaje' => 'required|string',
            'valoracion' => 'required|in:pos,neg',
        ]);

        // Creamos el comentario en la base de datos
        Comentario::create($datos);

        // Volvemos a la noticia donde se publicó el comentario
        return redirect()
            ->route('noticia.show', $datos['noticia_id'])
            ->with('success', 'Comentario publicado correctamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View|RedirectResponse
    {
        // Buscamos el comentario por su identificador
        $comentario = Comentario::findOrFail($id);

        // Comprobamos si todavía se puede modificar
        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()
                ->route('noticia.show', $comentario->noticia_id)
                ->with('error', 'Han pasado los 5 minutos. Ya no puedes editar este comentario.');
        }

        // Mostramos el formulario de edición
        return view('comentario.edit', compact('comentario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // Buscamos el comentario
        $comentario = Comentario::findOrFail($id);

        // Volvemos a comprobar el límite de 5 minutos
        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()
                ->route('noticia.show', $comentario->noticia_id)
                ->with('error', 'Han pasado los 5 minutos. Ya no puedes modificar este comentario.');
        }

        // Validamos los datos
        $datos = $request->validate([
            'mensaje' => 'required|string',
            'valoracion' => 'required|in:pos,neg',
        ]);

        // Actualizamos el comentario
        $comentario->update($datos);

        // Volvemos a la noticia
        return redirect()
            ->route('noticia.show', $comentario->noticia_id)
            ->with('success', 'Comentario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        // Buscamos el comentario
        $comentario = Comentario::findOrFail($id);

        // Comprobamos si todavía se puede borrar
        if (!$comentario->sePuedeModificarOBorrar()) {
            return redirect()
                ->route('noticia.show', $comentario->noticia_id)
                ->with('error', 'Han pasado los 5 minutos. Ya no puedes eliminar este comentario.');
        }

        // Guardamos el identificador de la noticia
        $noticiaId = $comentario->noticia_id;

        // Eliminamos el comentario
        $comentario->delete();

        // Volvemos a la noticia
        return redirect()
            ->route('noticia.show', $noticiaId)
            ->with('success', 'Comentario eliminado correctamente.');
    }
}

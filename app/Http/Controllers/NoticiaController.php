<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class NoticiaController extends Controller
{
    public function index(): View
    {
        $noticias = Noticia::withCount('comentarios')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $totalComentarios = Comentario::count();

        return view('noticia.index', compact('noticias', 'totalComentarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('noticia.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos recibidos
        $datos = $request->validate([
            'titulo' => 'required|string|min:3|max:200',
            'texto' => 'nullable|string|required_without:archivo_md',
            'archivo_md' => 'nullable|file|max:2048',
        ]);

        // 2. Comprobar si se ha subido un archivo
        if ($request->hasFile('archivo_md')) {

            $archivo = $request->file('archivo_md');

            // Comprobar que sea un archivo MD o TXT
            $extension = strtolower($archivo->getClientOriginalExtension());

            if (!in_array($extension, ['md', 'txt'])) {
                return back()
                    ->withErrors([
                        'archivo_md' => 'Solo se permiten archivos MD o TXT.'
                    ])
                    ->withInput();
            }

            // Leer el contenido del archivo
            $datos['texto'] = file_get_contents($archivo->getRealPath());
        }

        // 3. Crear la noticia en la base de datos
        Noticia::create([
            'titulo' => $datos['titulo'],
            'texto' => $datos['texto'],
        ]);

        // 4. Volver a la portada
        return redirect()
            ->route('noticia.index')
            ->with('success', 'Noticia publicada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        // Recuperamos la noticia junto con sus comentarios
        $noticia = Noticia::with('comentarios')->findOrFail($id);

        // Enviamos la noticia a la vista
        return view('noticia.show', compact('noticia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View|RedirectResponse
    {
        $noticia = Noticia::findOrFail($id);

        // Comprobar si todavía está dentro de los 20 minutos
        if (!$noticia->sePuedeEditar()) {
            return redirect()->route('noticia.show', $noticia)
                ->with('error', 'Han pasado los 20 minutos. Ya no puedes editar esta noticia.');
        }

        return view('noticia.edit', compact('noticia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $noticia = Noticia::findOrFail($id);

        // Comprobar de nuevo el límite de 20 minutos
        if (!$noticia->sePuedeEditar()) {
            return redirect()->route('noticia.show', $noticia)
                ->with('error', 'Han pasado los 20 minutos. Ya no puedes modificar esta noticia.');
        }

        // Validar los datos
        $datos = $request->validate([
            'titulo' => 'required|string|min:3|max:200',
            'texto' => 'nullable|string|required_without:archivo_md',
            'archivo_md' => 'nullable|file|max:2048',
        ]);

        // Si se ha seleccionado un archivo MD o TXT
        if ($request->hasFile('archivo_md')) {
            $archivo = $request->file('archivo_md');
            $extension = strtolower($archivo->getClientOriginalExtension());

            if (!in_array($extension, ['md', 'txt'])) {
                return back()->withErrors([
                    'archivo_md' => 'Solo se permiten archivos MD o TXT.'
                ])->withInput();
            }

            $datos['texto'] = file_get_contents($archivo->getRealPath());
        }

        // Actualizar la noticia
        $noticia->update([
            'titulo' => $datos['titulo'],
            'texto' => $datos['texto'],
        ]);

        return redirect()->route('noticia.show', $noticia)
            ->with('success', 'Noticia actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $noticia = Noticia::findOrFail($id);

        // Comprobar si la noticia tiene comentarios
        if (!$noticia->sePuedeBorrar()) {
            return redirect()->route('noticia.show', $noticia)
                ->with('error', 'No puedes eliminar esta noticia porque tiene comentarios.');
        }

        // Eliminar la noticia
        $noticia->delete();

        return redirect()->route('noticia.index')
            ->with('success', 'Noticia eliminada correctamente.');
    }
}

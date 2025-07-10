<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DinasController extends Controller
{
    public function index()
    {
        $dinas = Dinas::withCount('reservations')->orderBy('name')->paginate(10);
        return view('admin.dinas.index', compact('dinas'));
    }

    public function create()
    {
        return view('admin.dinas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:dinas,name',
        ]);
        Dinas::create($request->only('name'));
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil ditambahkan.');
    }

    public function edit(Dinas $dina)
    {
        return view('admin.dinas.edit', compact('dina'));
    }

    public function update(Request $request, Dinas $dina)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('dinas')->ignore($dina->id)],
        ]);
        $dina->update($request->only('name'));
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil diperbarui.');
    }

    public function destroy(Dinas $dina)
    {
        if ($dina->reservations()->exists()) {
            return redirect()->route('admin.dinas.index')->with('error', 'Instansi/Dinas tidak dapat dihapus karena sudah digunakan dalam data reservasi.');
        }
        $dina->delete();
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil dihapus.');
    }
}
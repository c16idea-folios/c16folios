<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\NoticeType;
use Illuminate\Http\Request;

class NoticeTypeController extends Controller
{
    public function index()
    {
        $acts = Act::orderBy('order', 'asc')->get();
        return view('administrator.notice_type', compact('acts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'act_id' => 'nullable|exists:acts,id',
            'type' => 'nullable|string|max:255',
            'days' => 'nullable|integer|min:0',
            'foreigners' => 'required|in:yes,no',
            'observations' => 'nullable|string',
        ]);

        NoticeType::create($validated);
        return redirect()->route('notice_type.admin')->with('success', 'Elemento creado correctamente.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'act_id' => 'nullable|exists:acts,id',
            'type' => 'nullable|string|max:255',
            'days' => 'nullable|integer|min:0',
            'foreigners' => 'required|in:yes,no',
            'observations' => 'nullable|string',
        ]);

        $noticeType = NoticeType::find($request->input('id'));
        $noticeType->update($validated);

        return redirect()->route('notice_type.admin')->with('success', 'Elemento actualizado correctamente.');
    }

    public function destroy(Request $request)
    {
        $noticeType = NoticeType::find($request->input('id'));
        if (!$noticeType) {
            return redirect()->back()->withErrors(['error' => 'El elemento ya no existe.']);
        }

        $noticeType->delete();
        return redirect()->route('notice_type.admin')->with('success', 'Elemento eliminado correctamente.');
    }

    public function dataTable(Request $request)
    {
        $item = new NoticeType();
        $items = $item->getDataTable($request);
        return response()->json($items);
    }
}

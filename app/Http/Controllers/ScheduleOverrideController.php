<?php

namespace App\Http\Controllers;

use App\Models\ScheduleOverride;
use Illuminate\Http\Request;

class ScheduleOverrideController extends Controller
{
    public function upsert(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'is_closed'   => 'nullable|boolean',
            'open_time'   => 'nullable|date_format:H:i,H:i:s|required_unless:is_closed,1',
            'break_start' => 'nullable|date_format:H:i,H:i:s',
            'break_end'   => 'nullable|date_format:H:i,H:i:s|required_with:break_start',
            'close_time'  => 'nullable|date_format:H:i,H:i:s|required_unless:is_closed,1',
            'notes'       => 'nullable|string|max:120',
        ]);

        $isClosed = (bool) $request->input('is_closed', false);

        ScheduleOverride::updateOrCreate(
            ['date' => $request->date],
            [
                'is_closed'   => $isClosed,
                'open_time'   => $isClosed ? null : $request->open_time,
                'break_start' => $isClosed ? null : $request->break_start,
                'break_end'   => $isClosed ? null : $request->break_end,
                'close_time'  => $isClosed ? null : $request->close_time,
                'notes'       => $request->notes,
            ]
        );

        return redirect()->back()->with('success', 'Horário do dia atualizado!');
    }

    public function destroy(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        ScheduleOverride::where('date', $request->date)->delete();

        return redirect()->back()->with('success', 'Horário padrão restaurado!');
    }
}

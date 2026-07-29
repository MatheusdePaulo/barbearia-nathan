<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScheduleOverride extends Model
{
    protected $fillable = [
        'date',
        'is_closed',
        'open_time',
        'break_start',
        'break_end',
        'close_time',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];

    /**
     * Retorna todos os slots (strings 'HH:MM') para uma data.
     * Respeita override se existir, senão usa regras padrão.
     */
    public static function getSlotsForDate(string $date): array
    {
        $override = static::where('date', $date)->first();
        $carbonDate = Carbon::parse($date);
        $dow = $carbonDate->dayOfWeek; // 0=Dom, 1=Seg, 6=Sab

        if ($override) {
            if ($override->is_closed) return [];

            return static::buildSlots(
                $override->open_time,
                $override->break_start,
                $override->break_end,
                $override->close_time
            );
        }

        // Regras padrão
        if ($dow === 0 || $dow === 1) return []; // Dom e Seg = fechado

        if ($dow === 6) { // Sábado
            return static::buildSlots('07:30', null, null, '17:00');
        }

        if ($dow === 3) { // Quarta
            return static::buildSlots('08:30', '11:30', '14:00', '17:30');
        }

        return static::buildSlots('08:30', '11:30', '14:00', '19:00');
    }

    /**
     * Retorna o override ativo para uma data, ou null se usar padrão.
     */
    public static function forDate(string $date): ?self
    {
        return static::where('date', $date)->first();
    }

    /**
     * Descrição legível do horário de um dia (para exibir na agenda).
     */
    public static function descriptionForDate(string $date): string
    {
        $override = static::where('date', $date)->first();
        $carbonDate = Carbon::parse($date);
        $dow = $carbonDate->dayOfWeek;

        if ($override) {
            if ($override->is_closed) return 'Fechado (customizado)';
            $open  = substr($override->open_time, 0, 5);
            $close = substr($override->close_time, 0, 5);
            $desc = "{$open} – {$close}";
            if ($override->break_start && $override->break_end) {
                $desc .= " | Almoço " . substr($override->break_start, 0, 5) . "–" . substr($override->break_end, 0, 5);
            }
            if ($override->notes) $desc .= " ({$override->notes})";
            return $desc . ' ✏️';
        }

        if ($dow === 0 || $dow === 1) return 'Fechado';
        if ($dow === 6) return '07:30 – 17:00 (sem almoço)';
        if ($dow === 3) return '08:30 – 17:30 | Almoço 11:30–14:00';
        return '08:30 – 19:00 | Almoço 11:30–14:00';
    }

    private static function buildSlots(string $open, ?string $breakStart, ?string $breakEnd, string $close): array
    {
        $slots = [];

        if ($breakStart && $breakEnd) {
            $cur  = Carbon::parse($open);
            $mEnd = Carbon::parse($breakStart);
            while ($cur->lte($mEnd)) {
                $slots[] = $cur->format('H:i');
                $cur->addMinutes(30);
            }

            $cur = Carbon::parse($breakEnd);
            $end = Carbon::parse($close);
            while ($cur->lte($end)) {
                $slots[] = $cur->format('H:i');
                $cur->addMinutes(30);
            }
        } else {
            $cur = Carbon::parse($open);
            $end = Carbon::parse($close);
            while ($cur->lte($end)) {
                $slots[] = $cur->format('H:i');
                $cur->addMinutes(30);
            }
        }

        return $slots;
    }
}

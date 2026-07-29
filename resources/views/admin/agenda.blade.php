@extends('layouts.admin')
 
@section('content')

    <style>
        /* Scroll Customizado Elite Barber Nathan */
        .custom-scroll-agenda::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scroll-agenda::-webkit-scrollbar-track { background: #0A0A0A; }
        .custom-scroll-agenda::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }
 
        @media (min-width: 1024px) {
            .table-viewport { max-height: 420px; overflow-y: auto; position: relative; }
            .sticky-header th { position: sticky; top: 0; background-color: #121212; z-index: 20; }
            .main-container { height: 100vh; overflow: hidden; }
        }
 
        .btn-agendamento-elite {
            background-color: #D4AF37; color: #000; border: 2px solid #fff;
            border-radius: 1.2rem; position: relative; font-weight: 900;
            letter-spacing: 0.1em; transition: all 0.3s ease; cursor: pointer;
        }
        .btn-agendamento-elite:hover:not(:disabled) { transform: scale(1.02); box-shadow: 0 0 20px rgba(212,175,55,0.4); }
        .btn-agendamento-elite:disabled { opacity: 0.5; cursor: not-allowed; grayscale: 1; }
 
        .plus-icon-circle {
            background: #000; color: #D4AF37; width: 22px; height: 22px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 12px; position: absolute; left: 15px;
        }
 
        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
        .row-finished { opacity: 0.3; filter: grayscale(1); }
 
        .day-closed { color: #3f3f46 !important; text-decoration: line-through; cursor: not-allowed !important; }
    </style>
 
    <div class="flex flex-col main-container bg-[#050505] text-white">
        @if(session('success'))
            <div class="bg-green-500/20 border-b border-green-500 text-green-500 px-6 py-3 text-xs font-black uppercase italic tracking-widest animate-pulse">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/20 border-b border-red-500 text-red-500 px-6 py-3 text-xs font-black uppercase italic tracking-widest">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            </div>
        @endif
 
        <header class="h-auto lg:h-20 border-b border-zinc-800 flex flex-col lg:flex-row items-center justify-between px-6 lg:px-10 py-4 lg:py-0 bg-[#0A0A0A]/50 backdrop-blur-xl shrink-0 gap-4">
            <div class="flex items-center gap-4 bg-zinc-900 px-4 py-2 rounded-xl border border-zinc-800 w-full lg:w-auto">
                <i class="fas fa-calendar-alt text-[#D4AF37]"></i>
                <span class="italic font-black uppercase text-xs tracking-widest">Controle Operacional</span>
            </div>
            <div class="flex items-center gap-4 border-l-0 lg:border-l border-zinc-800 pl-0 lg:pl-6 w-full lg:w-auto justify-end">
                <div class="text-right">
                    <p class="text-xs font-black uppercase tracking-widest text-white leading-none mb-1">{{ auth()->user()->name }}</p>
                    <p class="text-[9px] text-[#D4AF37] font-bold uppercase tracking-widest leading-none">Administrador</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=D4AF37&color=000&bold=true" class="w-10 h-10 rounded-xl border border-zinc-800 shadow-xl">
            </div>
        </header>
 
        <div class="flex-1 flex flex-col lg:flex-row overflow-y-auto lg:overflow-hidden">
            <div class="w-full lg:w-80 bg-[#0A0A0A] border-b lg:border-b-0 lg:border-r border-zinc-800 p-6 lg:p-8 flex flex-col gap-8 shrink-0">
 
                <button onclick="document.getElementById('modalAvulso').showModal()"
                        @if($estaFechado) disabled title="Barbearia fechada hoje" @endif
                        class="btn-agendamento-elite w-full py-6 italic uppercase flex items-center justify-center shadow-lg">
                    <span class="plus-icon-circle"><i class="fas fa-plus"></i></span>
                    <div class="text-center leading-tight">Agendamento<br>Avulso</div>
                </button>

                {{-- PAINEL DE HORÁRIO DO DIA --}}
                <div class="bg-[#121212] p-5 rounded-[2rem] border border-zinc-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Horário do Dia</p>
                        @if($scheduleOverride)
                            <span class="text-[8px] font-black uppercase bg-[#D4AF37]/20 text-[#D4AF37] px-2 py-0.5 rounded-full">Customizado</span>
                        @endif
                    </div>
                    <p class="text-xs font-bold text-white leading-relaxed">{{ $scheduleDescription }}</p>
                    <div class="flex gap-2 pt-1">
                        <button onclick="document.getElementById('modalHorario').showModal()"
                                class="flex-1 py-2.5 bg-zinc-800 border border-zinc-700 text-zinc-300 rounded-xl text-[9px] font-black uppercase hover:border-[#D4AF37] hover:text-[#D4AF37] transition-all">
                            <i class="fas fa-pen mr-1"></i> Editar
                        </button>
                        @if($scheduleOverride)
                            <form action="{{ route('admin.schedule.destroy') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="date" value="{{ $dataSelecionada }}">
                                <button type="submit"
                                        onclick="return confirm('Restaurar horário padrão para este dia?')"
                                        class="w-full py-2.5 bg-zinc-800 border border-zinc-700 text-red-500 rounded-xl text-[9px] font-black uppercase hover:border-red-500 transition-all">
                                    <i class="fas fa-undo mr-1"></i> Restaurar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
 
                <div class="bg-[#121212] p-6 rounded-[2.5rem] border border-zinc-800 shadow-xl">
                    @php
                        $calBase       = \Carbon\Carbon::parse($dataSelecionada);
                        $mesAnterior   = $calBase->copy()->subMonth()->format('Y-m-d');
                        $proximoMes    = $calBase->copy()->addMonth()->format('Y-m-d');
                    @endphp
                    <div class="flex items-center justify-between mb-6">
                        <a href="?date={{ $mesAnterior }}"
                           class="w-7 h-7 flex items-center justify-center rounded-lg text-zinc-500 hover:bg-zinc-800 hover:text-[#D4AF37] transition-all">
                            <i class="fas fa-chevron-left text-[10px]"></i>
                        </a>
                        <span class="font-black italic uppercase text-[11px] tracking-widest text-zinc-400">
                            {{ $calBase->translatedFormat('F, Y') }}
                        </span>
                        <a href="?date={{ $proximoMes }}"
                           class="w-7 h-7 flex items-center justify-center rounded-lg text-zinc-500 hover:bg-zinc-800 hover:text-[#D4AF37] transition-all">
                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center;">
                        @foreach(['D', 'S', 'T', 'Q', 'Q', 'S', 'S'] as $diaSemana)
                            <span class="text-[9px] font-black text-zinc-700 uppercase mb-2">{{ $diaSemana }}</span>
                        @endforeach
 
                        @php
                            $dataBase = $calBase;
                            $primeiroDiaDoMes = $dataBase->copy()->startOfMonth();
                            $diasNoMes = $primeiroDiaDoMes->daysInMonth;
                            $pularDias = $primeiroDiaDoMes->dayOfWeek;
                        @endphp
 
                        @for ($i = 0; $i < $pularDias; $i++)
                            <span></span>
                        @endfor
 
                        @for ($dia = 1; $dia <= $diasNoMes; $dia++)
                            @php
                                $dataIteracao = $dataBase->copy()->day($dia);
                                $fechado = empty(\App\Models\ScheduleOverride::getSlotsForDate($dataIteracao->format('Y-m-d')));
                                $ehHojeNoCalendario = ($dia == $dataBase->day);
                                $diaSemana = $dataIteracao->dayOfWeek;
                                $fechadoPermanente = ($diaSemana === 0 || $diaSemana === 1);
                            @endphp
                            <span @if(!$fechadoPermanente) onclick="window.location.href='?date={{ $dataIteracao->format('Y-m-d') }}'" @endif
                            class="flex items-center justify-center text-[10px] w-7 h-7 rounded-lg transition-all
                                  {{ $fechadoPermanente ? 'day-closed opacity-20' : 'cursor-pointer hover:bg-zinc-800' }}
                                  {{ ($fechado && !$fechadoPermanente) ? 'text-red-500/60 line-through' : '' }}
                                  {{ $ehHojeNoCalendario ? 'bg-[#D4AF37] text-black font-black shadow-lg' : ($fechadoPermanente ? '' : 'text-zinc-600') }}">
                                {{ $dia }}
                            </span>
                        @endfor
                    </div>
                    @if($estaFechado)
                        <p class="text-[9px] text-red-500 font-black uppercase italic mt-4 text-center animate-pulse">Estabelecimento Fechado</p>
                    @endif
                </div>
            </div>
 
            <div class="flex-1 p-4 lg:p-10 space-y-8 bg-[#050505] overflow-y-auto custom-scroll-agenda">
                @if($estaFechado)
                    <div class="bg-zinc-900 border border-zinc-800 p-10 rounded-[2.5rem] text-center">
                        <i class="fas fa-store-slash text-4xl text-zinc-700 mb-4"></i>
                        <h2 class="text-xl font-black italic uppercase text-zinc-500">Sem atividades hoje</h2>
                        <p class="text-xs text-zinc-600 uppercase font-bold tracking-widest mt-2">A barbearia não abre aos domingos e segundas.</p>
                    </div>
                @endif
                @if(!$estaFechado)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                        <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl">
                            <p class="text-zinc-500 text-[9px] font-black uppercase italic mb-1">Agendamentos do Dia</p>
                            <h3 class="text-3xl font-black italic">{{ str_pad(count($reservas), 2, '0', STR_PAD_LEFT) }}</h3>
                        </div>
                        <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 border-l-2 border-[#D4AF37] shadow-xl">
                            <p class="text-[#D4AF37] text-[9px] font-black uppercase italic mb-1">Receita Prevista (Dia)</p>
                            <h3 class="text-2xl font-black italic font-mono">R$ {{ number_format($faturamentoDia, 2, ',', '.') }}</h3>
                        </div>
                    </div>
 
                    <div class="bg-[#121212] border border-zinc-800 rounded-[2.5rem] overflow-hidden shadow-2xl flex flex-col mb-10">
                        <div class="px-6 lg:px-10 py-6 border-b border-zinc-800 bg-zinc-900/10 flex justify-between items-center">
                            <h3 class="font-black italic uppercase tracking-[0.2em] text-[10px] text-white">Reservas Confirmadas</h3>
                            <div class="text-[9px] font-black text-zinc-600 uppercase">{{ count($reservas) }} Registros</div>
                        </div>
 
                        <div class="table-viewport overflow-x-auto custom-scroll-agenda">
                            <table class="w-full text-left border-collapse min-w-[750px]">
                                <thead class="sticky-header">
                                <tr class="text-zinc-600 uppercase tracking-[0.2em] text-[9px]">
                                    <th class="py-6 px-6 lg:px-10 font-black text-[#D4AF37]">Horário</th>
                                    <th class="py-6 font-black uppercase">Cliente</th>
                                    <th class="py-6 font-black text-center uppercase">Serviço</th>
                                    <th class="py-6 font-black text-center uppercase">Valores</th>
                                    <th class="py-6 px-6 lg:px-10 font-black text-right uppercase">Ações</th>
                                </tr>
                                </thead>
                                <tbody class="text-zinc-300 text-[11px]">
                                @forelse($reservas as $reserva)
                                    <tr class="border-b border-zinc-800/30 hover:bg-zinc-900/10 transition-all {{ in_array($reserva->status, ['finished', 'canceled', 'expired']) ? 'row-finished' : '' }}">
                                        <td class="py-6 px-6 lg:px-10 font-mono text-[#D4AF37] font-black italic text-xs">{{ $reserva->time }}</td>
                                        <td class="py-6 font-black uppercase italic">{{ $reserva->user->name ?? $reserva->client_name }}</td>
                                        <td class="py-6 text-center text-zinc-500 font-bold uppercase tracking-tighter whitespace-nowrap">{{ $reserva->service_label }}</td>
 
                                        <td class="py-6 text-center whitespace-nowrap">
                                            @if($reserva->status == 'pending')
                                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-zinc-800 text-zinc-500 border border-zinc-700/50 animate-pulse">
                                                    ⏳ Aguardando Pix...
                                                </span>
                                            @elseif($reserva->user_id === null)
                                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-zinc-800 text-zinc-400 border border-zinc-700">
                                                    💵 Balcão Presencial
                                                </span>
                                            @else
                                                @php
                                                    $valorPago = (float)$reserva->deposit_amount;
                                                    $precoServico = (float)($reserva->service->price ?? 20.00);
                                                @endphp
 
                                                @if($reserva->status == 'confirmed' || $reserva->status == 'finished')
                                                    @if($valorPago == 0.10 || $reserva->deposit_amount == '0.10' || $valorPago >= $precoServico)
                                                        <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-green-500/10 text-green-400 border border-green-500/20">
                                                            ✅ Pagamento Total
                                                        </span>
                                                    @elseif($valorPago == 5.00)
                                                        <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                            ⚠️ Sinal Pago (R$ 5,00)
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-green-500/10 text-green-400 border border-green-500/20">
                                                            ✅ Pagamento Total
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase bg-zinc-800 text-zinc-400 border border-zinc-700">
                                                        💵 Balcão Presencial
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
 
                                        <td class="py-6 px-6 lg:px-10 text-right">
                                            <div class="flex justify-end gap-2">
                                                @if($reserva->status == 'confirmed' || $reserva->status == 'pending')
                                                    @php
                                                        $telefoneReserva = preg_replace('/[^0-9]/', '', $reserva->user->whatsapp ?? '');
                                                        $msgLembrete = "Olá, " . ($reserva->user->name ?? $reserva->client_name) . "! 💈 Lembrete da Barber Nathan: seu horário para " . $reserva->service->name . " está confirmado para hoje às " . $reserva->time . ". Te esperamos!";
                                                        $urlLembrete = "https://wa.me/55" . $telefoneReserva . "?text=" . urlencode($msgLembrete);
                                                    @endphp
 
                                                    <a href="{{ $urlLembrete }}" target="_blank" title="Lembrete WhatsApp" class="w-8 h-8 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-black transition-all">
                                                        <i class="fab fa-whatsapp text-[12px]"></i>
                                                    </a>
 
                                                    <form action="{{ route('admin.appointments.updateStatus', $reserva->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="finished">
                                                        <button type="submit" title="Finalizar" class="w-8 h-8 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-black transition-all"><i class="fas fa-check text-[10px]"></i></button>
                                                    </form>
                                                    <form action="{{ route('admin.appointments.updateStatus', $reserva->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="canceled">
                                                        <button type="submit" title="Faltou" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all"><i class="fas fa-times text-[10px]"></i></button>
                                                    </form>
                                                @else
                                                    @php
                                                        $label = match($reserva->status) {
                                                            'finished' => ['text' => 'Finalizado', 'class' => 'text-zinc-600'],
                                                            'canceled' => ['text' => 'Faltou', 'class' => 'text-red-900'],
                                                            'expired'  => ['text' => 'Pix Expirado', 'class' => 'text-orange-800'],
                                                            default    => ['text' => $reserva->status, 'class' => 'text-zinc-600'],
                                                        };
                                                    @endphp
                                                    <span class="text-[8px] font-black uppercase italic {{ $label['class'] }}">{{ $label['text'] }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="py-20 text-center text-zinc-700 font-black italic">Nenhum agendamento para este dia</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
 
    <dialog id="modalAvulso" class="bg-[#0A0A0A] border border-zinc-800 rounded-[2.5rem] p-0 text-white w-[95%] max-w-lg shadow-[0_0_100px_rgba(0,0,0,1)] outline-none backdrop:bg-black/90">
        <div class="px-8 py-6 border-b border-zinc-800 bg-zinc-900/50 flex justify-between items-center">
            <h3 class="text-xl font-black italic uppercase tracking-widest text-[#D4AF37]">Novo Agendamento</h3>
            <button type="button" onclick="document.getElementById('modalAvulso').close()" class="text-zinc-600 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.appointments.avulso') }}" method="POST" class="p-6 lg:p-8 space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Data</label>
                <input type="date" name="date" id="inputDate" value="{{ $dataSelecionada }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div id="formContent">
                <div class="space-y-2 mb-6">
                    <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Cliente</label>
                    <input type="text" name="client_name" placeholder="Ex: Cliente Avulso" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none italic font-bold uppercase">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Serviço</label>
                        <select name="service_id" id="serviceSelect" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none">
                            @foreach(\App\Models\Service::all() as $s)
                                <option value="{{ $s->id }}" data-duration="{{ $s->is_combo ? 60 : 30 }}">
                                    {{ $s->name }} ({{ $s->is_combo ? '1h' : '30min' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Horário</label>
                        <select name="time" id="timeSelect" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none font-mono"></select>
                    </div>
                </div>
                <div class="pt-8 flex gap-4">
                    <button type="button" onclick="document.getElementById('modalAvulso').close()" class="flex-1 bg-zinc-900 text-zinc-500 py-4 rounded-2xl font-black uppercase text-[10px]">Voltar</button>
                    <button type="submit" id="btnSubmitAvulso" class="flex-[2] bg-[#D4AF37] text-black py-4 rounded-2xl font-black uppercase italic tracking-widest text-[11px] shadow-lg">Finalizar</button>
                </div>
            </div>
            <div id="closedMessage" class="hidden text-center py-10">
                <i class="fas fa-ban text-red-500 text-3xl mb-4"></i>
                <p class="text-xs font-black uppercase italic text-zinc-400">Barbearia fechada neste dia</p>
            </div>
        </form>
    </dialog>
 
    {{-- MODAL EDIÇÃO DE HORÁRIO --}}
    <dialog id="modalHorario" class="bg-[#0A0A0A] border border-zinc-800 rounded-[2.5rem] p-0 text-white w-[95%] max-w-lg shadow-[0_0_100px_rgba(0,0,0,1)] outline-none backdrop:bg-black/90">
        <div class="px-8 py-6 border-b border-zinc-800 bg-zinc-900/50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black italic uppercase tracking-widest text-[#D4AF37]">Horário do Dia</h3>
                <p class="text-[10px] text-zinc-500 font-bold mt-0.5">{{ \Carbon\Carbon::parse($dataSelecionada)->translatedFormat('l, d \d\e F') }}</p>
            </div>
            <button type="button" onclick="document.getElementById('modalHorario').close()" class="text-zinc-600 hover:text-white"><i class="fas fa-times"></i></button>
        </div>

        <form action="{{ route('admin.schedule.upsert') }}" method="POST" class="p-6 lg:p-8 space-y-6"
              x-data="{ isClosed: {{ $scheduleOverride?->is_closed ? 'true' : 'false' }}, hasBreak: {{ ($scheduleOverride?->break_start) ? 'true' : 'false' }} }"
              @submit="if (!hasBreak) { $el.querySelector('[name=break_start]').value = ''; $el.querySelector('[name=break_end]').value = ''; }">
            @csrf
            <input type="hidden" name="date" value="{{ $dataSelecionada }}">

            {{-- Fechar o dia --}}
            <label class="flex items-center gap-4 p-4 bg-zinc-900 rounded-2xl border border-zinc-800 cursor-pointer hover:border-red-500/40 transition-all">
                <input type="checkbox" name="is_closed" value="1" x-model="isClosed" class="hidden peer">
                <div class="w-10 h-6 rounded-full transition-all relative flex-shrink-0"
                     :class="isClosed ? 'bg-red-500' : 'bg-zinc-700'">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-all"
                         :class="isClosed ? 'left-5' : 'left-1'"></div>
                </div>
                <div>
                    <p class="text-sm font-black text-white uppercase">Fechar este dia</p>
                    <p class="text-[10px] text-zinc-500">Feriado, folga ou evento especial</p>
                </div>
            </label>

            {{-- Campos de horário (ocultos quando fechado) --}}
            <div x-show="!isClosed" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Abertura</label>
                        <input type="time" name="open_time" step="1800"
                               value="{{ substr($scheduleOverride?->open_time ?? '08:30', 0, 5) }}"
                               class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none font-mono">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Fechamento</label>
                        <input type="time" name="close_time" step="1800"
                               value="{{ substr($scheduleOverride?->close_time ?? '19:00', 0, 5) }}"
                               class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none font-mono">
                    </div>
                </div>

                {{-- Intervalo de almoço --}}
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="w-8 h-5 rounded-full relative flex-shrink-0 transition-all"
                             :class="hasBreak ? 'bg-[#D4AF37]' : 'bg-zinc-700'"
                             @click="hasBreak = !hasBreak">
                            <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-all"
                                 :class="hasBreak ? 'left-3.5' : 'left-0.5'"></div>
                        </div>
                        <span class="text-[11px] font-black uppercase text-zinc-400">Intervalo de Almoço</span>
                    </label>

                    <div x-show="hasBreak" class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Início Almoço</label>
                            <input type="time" name="break_start" step="1800"
                                   value="{{ substr($scheduleOverride?->break_start ?? '11:30', 0, 5) }}"
                                   class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none font-mono">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Retorno Almoço</label>
                            <input type="time" name="break_end" step="1800"
                                   value="{{ substr($scheduleOverride?->break_end ?? '14:00', 0, 5) }}"
                                   class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none font-mono">
                        </div>
                    </div>
                </div>

                {{-- Observação --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Observação (opcional)</label>
                    <input type="text" name="notes" maxlength="120"
                           value="{{ $scheduleOverride?->notes }}"
                           placeholder="Ex: Feriado, saída mais cedo..."
                           class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none italic">
                </div>
            </div>

            <div class="pt-2 flex gap-4">
                <button type="button" onclick="document.getElementById('modalHorario').close()"
                        class="flex-1 bg-zinc-900 text-zinc-500 py-4 rounded-2xl font-black uppercase text-[10px]">Cancelar</button>
                <button type="submit"
                        class="flex-[2] bg-[#D4AF37] text-black py-4 rounded-2xl font-black uppercase italic tracking-widest text-[11px] shadow-lg">
                    <i class="fas fa-save mr-2"></i> Salvar Horário
                </button>
            </div>
        </form>
    </dialog>

    <script>
        const ocupadosInfo = [
            @foreach($reservas as $res)
                @if($res->status == 'confirmed' || $res->status == 'pending')
                    { time: "{{ $res->time }}", duration: {{ $res->service?->is_combo ? 60 : 30 }} },
                @endif
            @endforeach
        ];
 
        const serviceSelect = document.getElementById('serviceSelect');
        const timeSelect = document.getElementById('timeSelect');
        const inputDate = document.getElementById('inputDate');
        const btnSubmit = document.getElementById('btnSubmitAvulso');
        const formContent = document.getElementById('formContent');
        const closedMessage = document.getElementById('closedMessage');
 
        function updateTimes() {
            const dateParts = inputDate.value.split('-');
            const date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
            const dayOfWeek = date.getDay();
 
            if (dayOfWeek === 0 || dayOfWeek === 1) {
                formContent.classList.add('hidden');
                closedMessage.classList.remove('hidden');
                return;
            } else {
                formContent.classList.remove('hidden');
                closedMessage.classList.add('hidden');
            }
 
            const hoje = new Date();
            hoje.setHours(0,0,0,0);
            const dataSelecionada = new Date(date);
            dataSelecionada.setHours(0,0,0,0);
 
            if (dataSelecionada < hoje) {
                timeSelect.innerHTML = '<option value="" disabled selected>DATA PASSADA</option>';
                btnSubmit.disabled = true;
                return;
            }
            btnSubmit.disabled = false;
 
            const isSaturday = dayOfWeek === 6;
            const isWednesday = dayOfWeek === 3;
            let slots = [];

            if (isSaturday) {
                // Sábado: 07:30 até 17:00 sem pausa para almoço
                let c = new Date(0,0,0,7,30);
                while(c <= new Date(0,0,0,17,0)) { slots.push(c.toTimeString().substring(0,5)); c.setMinutes(c.getMinutes() + 30); }
            } else if (isWednesday) {
                // Quarta-feira: encerra às 17:30
                let m = new Date(0,0,0,8,30);
                while(m <= new Date(0,0,0,11,30)) { slots.push(m.toTimeString().substring(0,5)); m.setMinutes(m.getMinutes() + 30); }
                let t = new Date(0,0,0,14,0);
                while(t <= new Date(0,0,0,17,30)) { slots.push(t.toTimeString().substring(0,5)); t.setMinutes(t.getMinutes() + 30); }
            } else {
                // Dias normais: manhã 08:30-11:30, tarde 14:00-19:00
                let m = new Date(0,0,0,8,30);
                while(m <= new Date(0,0,0,11,30)) { slots.push(m.toTimeString().substring(0,5)); m.setMinutes(m.getMinutes() + 30); }
                let t = new Date(0,0,0,14,0);
                while(t <= new Date(0,0,0,19,0)) { slots.push(t.toTimeString().substring(0,5)); t.setMinutes(t.getMinutes() + 30); }
            }
 
            let blocked = [];
            ocupadosInfo.forEach(o => {
                blocked.push(o.time);
                if(o.duration > 30) {
                    let parts = o.time.split(':');
                    let next = new Date(0,0,0, parts[0], parts[1]);
                    next.setMinutes(next.getMinutes() + 30);
                    blocked.push(next.toTimeString().substring(0,5));
                }
            });
 
            const agora = new Date();
            const dataEhHoje = dataSelecionada.getTime() === hoje.getTime();
 
            timeSelect.innerHTML = slots.map(s => {
                const isBlocked = blocked.includes(s);
                let isPast = (dataEhHoje && new Date().setHours(...s.split(':')) < agora);
                const disabled = isBlocked || isPast;
                return `<option value="${s}" ${disabled ? 'disabled class="text-zinc-700"' : ''}>${s} ${isBlocked ? '(OCUPADO)' : (isPast ? '(PASSADO)' : '')}</option>`;
            }).join('');
        }
 
        inputDate.addEventListener('change', () => window.location.href = `?date=${inputDate.value}`);
        serviceSelect.addEventListener('change', updateTimes);
        updateTimes();

        // Recarrega a página silenciosamente a cada 60s apenas quando não há modal aberto e
        // o usuário não está interagindo com nenhum input — preserva o estado da UI
        let reloadTimer = null;

        function scheduleReload() {
            clearTimeout(reloadTimer);
            reloadTimer = setTimeout(() => {
                const modal = document.getElementById('modalAvulso');
                const hasOpenModal = modal && modal.open;
                const hasFocus = document.activeElement && document.activeElement.tagName !== 'BODY';
                if (!hasOpenModal && !hasFocus) {
                    window.location.reload();
                } else {
                    scheduleReload();
                }
            }, 60000);
        }

        scheduleReload();

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) scheduleReload();
        });
    </script>
@endsection
@extends('layouts.admin')

@section('content')
    <style>
        /* Scroll Customizado Elite Barber Nathan */
        .custom-scroll-agenda::-webkit-scrollbar { width: 4px; }
        .custom-scroll-agenda::-webkit-scrollbar-track { background: #0A0A0A; }
        .custom-scroll-agenda::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }

        /* Viewport Controlada */
        @media (min-width: 1024px) {
            .table-viewport { max-height: 420px; overflow-y: auto; position: relative; }
            .sticky-header th { position: sticky; top: 0; background-color: #121212; z-index: 20; }
            .main-container { height: 100vh; overflow: hidden; }
        }

        /* Botão Premium conforme o seu print */
        .btn-agendamento-elite {
            background-color: #D4AF37; color: #000; border: 2px solid #fff;
            border-radius: 1.2rem; position: relative; font-weight: 900;
            letter-spacing: 0.1em; transition: all 0.3s ease; cursor: pointer;
        }
        .btn-agendamento-elite:hover { transform: scale(1.02); box-shadow: 0 0 20px rgba(212,175,55,0.4); }

        .plus-icon-circle {
            background: #000; color: #D4AF37; width: 22px; height: 22px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; font-size: 12px; position: absolute; left: 15px;
        }

        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
        .row-finished { opacity: 0.3; filter: grayscale(1); }
    </style>

    <div class="flex flex-col main-container bg-[#050505] text-white">
        <header class="h-auto lg:h-20 border-b border-zinc-800 flex flex-col lg:flex-row items-center justify-between px-6 lg:px-10 py-4 lg:py-0 bg-[#0A0A0A]/50 backdrop-blur-xl shrink-0 gap-4">
            <div class="flex items-center gap-4 bg-zinc-900 px-4 py-2 rounded-xl border border-zinc-800 w-full lg:w-auto">
                <i class="fas fa-calendar-alt text-[#D4AF37]"></i>
                <span class="italic font-black uppercase text-xs tracking-widest">Controle Operacional</span>
            </div>
            <div class="flex items-center gap-4 border-l-0 lg:border-l border-zinc-800 pl-0 lg:pl-6 w-full lg:w-auto justify-end">
                <div class="text-right">
                    <p class="text-xs font-black uppercase tracking-widest text-white leading-none mb-1">Matheus de Paulo</p>
                    <p class="text-[9px] text-[#D4AF37] font-bold uppercase tracking-widest leading-none">Administrador</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=Matheus+de+Paulo&background=D4AF37&color=000&bold=true" class="w-10 h-10 rounded-xl border border-zinc-800 shadow-xl">
            </div>
        </header>

        <div class="flex-1 flex flex-col lg:flex-row overflow-y-auto lg:overflow-hidden">
            <div class="w-full lg:w-80 bg-[#0A0A0A] border-b lg:border-b-0 lg:border-r border-zinc-800 p-6 lg:p-8 flex flex-col gap-8 shrink-0">
                <button onclick="document.getElementById('modalAvulso').showModal()" class="btn-agendamento-elite w-full py-6 italic uppercase flex items-center justify-center shadow-lg">
                    <span class="plus-icon-circle"><i class="fas fa-plus"></i></span>
                    <div class="text-center leading-tight">Agendamento<br>Avulso</div>
                </button>

                <div class="bg-[#121212] p-6 rounded-[2.5rem] border border-zinc-800 shadow-xl hidden lg:block">
                    <div class="text-center font-black italic uppercase text-[11px] mb-6 tracking-widest text-zinc-400">{{ \Carbon\Carbon::parse($dataSelecionada)->translatedFormat('F, Y') }}</div>
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center;">
                        @php $hojeNum = date('d', strtotime($dataSelecionada)); @endphp
                        @for ($i = 1; $i <= 31; $i++)
                            <span onclick="window.location.href='?date={{ now()->format('Y-m-') }}{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}'"
                                  class="flex items-center justify-center text-[10px] w-7 h-7 rounded-lg cursor-pointer transition-all {{ $i == $hojeNum ? 'bg-[#D4AF37] text-black font-black shadow-lg' : 'text-zinc-600 hover:bg-zinc-800' }}">{{ $i }}</span>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="flex-1 p-4 lg:p-10 space-y-8 bg-[#050505] overflow-y-auto custom-scroll-agenda">
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

                <div class="lg:hidden">
                    <div class="bg-[#121212] p-6 rounded-3xl border border-zinc-800 shadow-xl">
                        <div class="text-center font-black italic uppercase text-[11px] mb-6 text-zinc-400">{{ \Carbon\Carbon::parse($dataSelecionada)->translatedFormat('F, Y') }}</div>
                        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; text-align: center;">
                            @for ($i = 1; $i <= 31; $i++)
                                <span onclick="window.location.href='?date={{ now()->format('Y-m-') }}{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}'"
                                      class="flex items-center justify-center text-[10px] w-8 h-8 rounded-lg cursor-pointer {{ $i == $hojeNum ? 'bg-[#D4AF37] text-black font-black' : 'text-zinc-600' }}">{{ $i }}</span>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="bg-[#121212] border border-zinc-800 rounded-[2.5rem] overflow-hidden shadow-2xl flex flex-col mb-10">
                    <div class="px-6 lg:px-10 py-6 border-b border-zinc-800 bg-zinc-900/10 flex justify-between items-center">
                        <h3 class="font-black italic uppercase tracking-[0.2em] text-[10px] text-white">Reservas Confirmadas</h3>
                        <div class="text-[9px] font-black text-zinc-600 uppercase">{{ count($reservas) }} Registros</div>
                    </div>

                    <div class="table-viewport custom-scroll-agenda">
                        <table class="hidden lg:table w-full text-left border-collapse">
                            <thead class="sticky-header">
                            <tr class="text-zinc-600 uppercase tracking-[0.2em] text-[9px]">
                                <th class="py-6 px-10 font-black text-[#D4AF37]">Horário</th>
                                <th class="py-6 font-black uppercase">Cliente</th>
                                <th class="py-6 font-black text-center uppercase">Serviço</th>
                                <th class="py-6 px-10 font-black text-right uppercase">Ações</th>
                            </tr>
                            </thead>
                            <tbody class="text-zinc-300 text-[11px]">
                            @forelse($reservas as $reserva)
                                <tr class="border-b border-zinc-800/30 hover:bg-zinc-900/10 transition-all {{ in_array($reserva->status, ['finished', 'canceled']) ? 'row-finished' : '' }}">
                                    <td class="py-6 px-10 font-mono text-[#D4AF37] font-black italic text-xs">{{ $reserva->time }}</td>
                                    <td class="py-6 font-black uppercase italic">{{ $reserva->user->name ?? $reserva->client_name }}</td>
                                    <td class="py-6 text-center text-zinc-500 font-bold uppercase tracking-tighter">{{ $reserva->service->name }}</td>
                                    <td class="py-6 px-10 text-right">
                                        <div class="flex justify-end gap-2">
                                            @if($reserva->status == 'confirmed' || $reserva->status == 'pending')
                                                <form action="{{ route('admin.appointments.updateStatus', $reserva->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="finished">
                                                    <button type="submit" class="w-8 h-8 rounded-lg bg-green-500/10 text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-black transition-all"><i class="fas fa-check text-[10px]"></i></button>
                                                </form>
                                                <form action="{{ route('admin.appointments.updateStatus', $reserva->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="canceled">
                                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all"><i class="fas fa-times text-[10px]"></i></button>
                                                </form>
                                            @else
                                                <span class="text-[8px] font-black uppercase italic {{ $reserva->status == 'finished' ? 'text-zinc-600' : 'text-red-900' }}">{{ $reserva->status == 'finished' ? 'Finalizado' : 'Faltou' }}</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-20 text-center text-zinc-700 font-black italic">Nenhum agendamento</td></tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="lg:hidden p-4 space-y-4">
                            @forelse($reservas as $reserva)
                                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-4 {{ in_array($reserva->status, ['finished', 'canceled']) ? 'row-finished' : '' }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <span class="text-[#D4AF37] font-mono font-black italic text-sm block leading-none mb-1">{{ $reserva->time }}</span>
                                            <h4 class="font-black uppercase italic text-xs text-white">{{ $reserva->user->name ?? $reserva->client_name }}</h4>
                                        </div>
                                        <div class="flex gap-2">
                                            @if($reserva->status == 'confirmed' || $reserva->status == 'pending')
                                                <form action="{{ route('admin.appointments.updateStatus', $reserva->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="finished">
                                                    <button type="submit" class="w-10 h-10 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center border border-green-500/20"><i class="fas fa-check"></i></button>
                                                </form>
                                                <form action="{{ route('admin.appointments.updateStatus', $reserva->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="canceled">
                                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center border border-red-500/20"><i class="fas fa-times"></i></button>
                                                </form>
                                            @else
                                                <span class="text-[9px] font-black uppercase px-3 py-1 bg-zinc-800 rounded-lg text-zinc-500 italic">{{ $reserva->status == 'finished' ? 'Concluído' : 'Faltou' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="pt-3 border-t border-zinc-800/50">
                                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest"><i class="fas fa-cut mr-2 text-[8px]"></i>{{ $reserva->service->name }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="py-10 text-center text-zinc-700 italic font-black uppercase text-[10px]">Vazio</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <dialog id="modalAvulso" class="bg-[#0A0A0A] border border-zinc-800 rounded-[2.5rem] p-0 text-white w-[95%] max-w-lg shadow-[0_0_100px_rgba(0,0,0,1)] outline-none backdrop:bg-black/90">
        <div class="px-8 py-6 border-b border-zinc-800 bg-zinc-900/50 flex justify-between items-center">
            <h3 class="text-xl font-black italic uppercase tracking-widest text-[#D4AF37]">Novo Agendamento</h3>
            <button onclick="document.getElementById('modalAvulso').close()" class="text-zinc-600 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.appointments.avulso') }}" method="POST" class="p-6 lg:p-8 space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Data</label>
                <input type="date" name="date" id="inputDate" value="{{ $dataSelecionada }}" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Cliente</label>
                <input type="text" name="client_name" placeholder="Ex: Cliente Avulso" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none italic font-bold uppercase">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Serviço</label>
                    <select name="service_id" id="serviceSelect" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none">
                        @foreach(\App\Models\Service::all() as $s)
                            <option value="{{ $s->id }}" data-duration="{{ (strpos($s->name, 'Combo') !== false || strpos($s->name, 'Progressiva') !== false) ? 60 : 30 }}">
                                {{ $s->name }} ({{ (strpos($s->name, 'Combo') !== false || strpos($s->name, 'Progressiva') !== false) ? '1h' : '30min' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-zinc-500 ml-2">Horário</label>
                    <select name="time" id="timeSelect" required class="w-full bg-zinc-900 border border-zinc-800 rounded-2xl p-4 text-sm text-white focus:border-[#D4AF37] outline-none font-mono"></select>
                </div>
            </div>
            <div class="pt-4 flex gap-4">
                <button type="button" onclick="document.getElementById('modalAvulso').close()" class="flex-1 bg-zinc-900 text-zinc-500 py-4 rounded-2xl font-black uppercase text-[10px]">Voltar</button>
                <button type="submit" class="flex-[2] bg-[#D4AF37] text-black py-4 rounded-2xl font-black uppercase italic tracking-widest text-[11px] shadow-lg">Finalizar</button>
            </div>
        </form>
    </dialog>

    <script>
        const ocupadosInfo = [
                @foreach($reservas as $res)
            { time: "{{ $res->time }}", duration: {{ (strpos($res->service->name, 'Combo') !== false || strpos($res->service->name, 'Progressiva') !== false) ? 60 : 30 }} },
            @endforeach
        ];
        const serviceSelect = document.getElementById('serviceSelect');
        const timeSelect = document.getElementById('timeSelect');
        const inputDate = document.getElementById('inputDate');

        function updateTimes() {
            const dateStr = inputDate.value;
            const date = new Date(dateStr + 'T00:00:00');
            const isSaturday = date.getDay() === 6;
            let slots = [];
            if (isSaturday) {
                let c = new Date(0,0,0,8,30);
                while(c <= new Date(0,0,0,17,0)) { slots.push(c.toTimeString().substring(0,5)); c.setMinutes(c.getMinutes() + 30); }
            } else {
                let m = new Date(0,0,0,8,30);
                while(m <= new Date(0,0,0,11,0)) { slots.push(m.toTimeString().substring(0,5)); m.setMinutes(m.getMinutes() + 30); }
                let t = new Date(0,0,0,14,0);
                while(t <= new Date(0,0,0,18,0)) { slots.push(t.toTimeString().substring(0,5)); t.setMinutes(t.getMinutes() + 30); }
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
            timeSelect.innerHTML = slots.map(s => {
                const isBlocked = blocked.includes(s);
                return `<option value="${s}" ${isBlocked ? 'disabled class="text-zinc-700"' : ''}>${s} ${isBlocked ? '(OCUPADO)' : ''}</option>`;
            }).join('');
        }
        inputDate.addEventListener('change', () => window.location.href = `?date=${inputDate.value}`);
        serviceSelect.addEventListener('change', updateTimes);
        updateTimes();
    </script>
@endsection

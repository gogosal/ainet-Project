<div style="padding:1.5rem;">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80;padding:0.75rem;border-radius:6px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="color:#e2e8f0;font-size:1.5rem;font-weight:700;margin:0;">Clientes</h1>
            <p style="color:#94a3b8;font-size:0.85rem;margin:0.25rem 0 0;">Gestão de clientes registados</p>
        </div>
    </div>

    {{-- Search + Filter --}}
    <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Pesquisar por nome ou email..."
                style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:280px;box-sizing:border-box;font-size:0.9rem;outline:none;"
            />
            <div style="display:flex;gap:0.5rem;">
                <button
                    wire:click="$set('filter','all')"
                    style="border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;border:1px solid #1e1e30;{{ $filter === 'all' ? 'background:#7c3aed;color:white;border-color:#7c3aed;' : 'background:#1a1a2e;color:#94a3b8;' }}"
                >Todos</button>
                <button
                    wire:click="$set('filter','active')"
                    style="border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;border:1px solid #1e1e30;{{ $filter === 'active' ? 'background:#7c3aed;color:white;border-color:#7c3aed;' : 'background:#1a1a2e;color:#94a3b8;' }}"
                >Ativos</button>
                <button
                    wire:click="$set('filter','blocked')"
                    style="border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;border:1px solid #1e1e30;{{ $filter === 'blocked' ? 'background:#7c3aed;color:white;border-color:#7c3aed;' : 'background:#1a1a2e;color:#94a3b8;' }}"
                >Bloqueados</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#0d0d1a;">
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Nome</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Email</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Género</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">NIF</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Estado</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr style="{{ $user->blocked ? 'background:rgba(239,68,68,0.04);' : '' }}">
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#e2e8f0;font-size:0.85rem;">
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                @if ($user->photo_url)
                                    <img src="{{ $user->photo_url }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div style="width:28px;height:28px;border-radius:50%;background:#1a1a2e;display:flex;align-items:center;justify-content:center;color:#7c3aed;font-size:0.75rem;font-weight:700;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                {{ $user->name }}
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#94a3b8;font-size:0.85rem;">{{ $user->email }}</td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#94a3b8;font-size:0.85rem;">
                            @if ($user->gender === 'M')
                                Masculino
                            @elseif ($user->gender === 'F')
                                Feminino
                            @else
                                —
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#94a3b8;font-size:0.85rem;">{{ $user->customer?->nif ?? '—' }}</td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;font-size:0.85rem;">
                            @if ($user->blocked)
                                <span style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);border-radius:20px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Bloqueado</span>
                            @else
                                <span style="background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.2);border-radius:20px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Ativo</span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;font-size:0.85rem;">
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                <button
                                    wire:click="toggleBlock({{ $user->id }})"
                                    wire:confirm="{{ $user->blocked ? 'Desbloquear este cliente?' : 'Bloquear este cliente?' }}"
                                    style="{{ $user->blocked ? 'background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.2);' : 'background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);' }}border-radius:6px;padding:0.35rem 0.75rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                >{{ $user->blocked ? 'Desbloquear' : 'Bloquear' }}</button>
                                <button
                                    wire:click="deleteCustomer({{ $user->id }})"
                                    wire:confirm="Tem a certeza que quer eliminar este cliente? Esta ação não pode ser revertida."
                                    style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);border-radius:6px;padding:0.35rem 0.75rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                >Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.9rem;">Nenhum cliente encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginator --}}
    @if ($users->hasPages())
        <div style="margin-top:1rem;">
            {{ $users->links() }}
        </div>
    @endif

</div>

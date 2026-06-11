<div style="padding:1.5rem;">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div style="background:rgba(34,197,94,.08);border:1px solid rgba(22,163,74,.25);color:#16a34a;padding:0.75rem;border-radius:1px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="color:#1a1a1a;font-size:1.5rem;font-weight:700;margin:0;">Clientes</h1>
            <p style="color:#888;font-size:0.85rem;margin:0.25rem 0 0;">Gestão de clientes registados</p>
        </div>
    </div>

    {{-- Search + Filter --}}
    <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Pesquisar por nome ou email..."
                style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:280px;box-sizing:border-box;font-size:0.9rem;outline:none;"
            />
            <div style="display:flex;gap:0.5rem;">
                <button
                    wire:click="$set('filter','all')"
                    style="border-radius:1px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;border:1px solid #e0ddd8;{{ $filter === 'all' ? 'background:#7c6fa0;color:white;border-color:#7c6fa0;' : 'background:#ffffff;color:#888;' }}"
                >Todos</button>
                <button
                    wire:click="$set('filter','active')"
                    style="border-radius:1px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;border:1px solid #e0ddd8;{{ $filter === 'active' ? 'background:#7c6fa0;color:white;border-color:#7c6fa0;' : 'background:#ffffff;color:#888;' }}"
                >Ativos</button>
                <button
                    wire:click="$set('filter','blocked')"
                    style="border-radius:1px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;border:1px solid #e0ddd8;{{ $filter === 'blocked' ? 'background:#7c6fa0;color:white;border-color:#7c6fa0;' : 'background:#ffffff;color:#888;' }}"
                >Bloqueados</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9f8f6;">
                    <th style="padding:0.75rem 1rem;text-align:left;color:#888;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Nome</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#888;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Email</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#888;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Género</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#888;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">NIF</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#888;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Estado</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#888;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr style="{{ $user->blocked ? 'background:rgba(239,68,68,0.04);' : '' }}">
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #e0ddd8;color:#1a1a1a;font-size:0.85rem;">
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                @if ($user->photo_url)
                                    @php $photoSrc = str_contains($user->photo_url, '/') ? asset('storage/' . $user->photo_url) : asset('storage/photos/' . $user->photo_url); @endphp
                                    <img src="{{ $photoSrc }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div style="width:28px;height:28px;border-radius:50%;background:#ffffff;display:flex;align-items:center;justify-content:center;color:#7c6fa0;font-size:0.75rem;font-weight:700;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                {{ $user->name }}
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #e0ddd8;color:#888;font-size:0.85rem;">{{ $user->email }}</td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #e0ddd8;color:#888;font-size:0.85rem;">
                            @if ($user->gender === 'M')
                                Masculino
                            @elseif ($user->gender === 'F')
                                Feminino
                            @else
                                —
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #e0ddd8;color:#888;font-size:0.85rem;">{{ $user->customer?->nif ?? '—' }}</td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #e0ddd8;font-size:0.85rem;">
                            @if ($user->blocked)
                                <span style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);border-radius:1px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Bloqueado</span>
                            @else
                                <span style="background:rgba(34,197,94,0.15);color:#16a34a;border:1px solid rgba(34,197,94,0.2);border-radius:1px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Ativo</span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #e0ddd8;font-size:0.85rem;">
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                <button
                                    wire:click="toggleBlock({{ $user->id }})"
                                    wire:confirm="{{ $user->blocked ? 'Desbloquear este cliente?' : 'Bloquear este cliente?' }}"
                                    style="{{ $user->blocked ? 'background:rgba(34,197,94,0.15);color:#16a34a;border:1px solid rgba(34,197,94,0.2);' : 'background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);' }}border-radius:1px;padding:0.35rem 0.75rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                >{{ $user->blocked ? 'Desbloquear' : 'Bloquear' }}</button>
                                <button
                                    wire:click="deleteCustomer({{ $user->id }})"
                                    wire:confirm="Tem a certeza que quer eliminar este cliente? Esta ação não pode ser revertida."
                                    style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);border-radius:1px;padding:0.35rem 0.75rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                >Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:2rem;text-align:center;color:#888;font-size:0.9rem;">Nenhum cliente encontrado.</td>
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

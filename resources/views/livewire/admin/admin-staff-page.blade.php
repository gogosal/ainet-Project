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
            <h1 style="color:#e2e8f0;font-size:1.5rem;font-weight:700;margin:0;">Colaboradores</h1>
            <p style="color:#94a3b8;font-size:0.85rem;margin:0.25rem 0 0;">Gestão de funcionários e administradores</p>
        </div>
        <button
            wire:click="openCreate"
            style="background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;"
        >+ Novo colaborador</button>
    </div>

    {{-- Table --}}
    <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#0d0d1a;">
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Nome</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Email</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Tipo</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Género</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($staff as $member)
                    <tr>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#e2e8f0;font-size:0.85rem;">
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                @if ($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div style="width:28px;height:28px;border-radius:50%;background:#1a1a2e;display:flex;align-items:center;justify-content:center;color:#a78bfa;font-size:0.75rem;font-weight:700;">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                                {{ $member->name }}
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#94a3b8;font-size:0.85rem;">{{ $member->email }}</td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;font-size:0.85rem;">
                            @if ($member->user_type === 'A')
                                <span style="background:rgba(124,58,237,0.2);color:#a78bfa;border:1px solid rgba(124,58,237,0.3);border-radius:20px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Admin</span>
                            @else
                                <span style="background:rgba(56,189,248,0.15);color:#7dd3fc;border:1px solid rgba(56,189,248,0.2);border-radius:20px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Funcionário</span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#94a3b8;font-size:0.85rem;">
                            @if ($member->gender === 'M') Masculino
                            @elseif ($member->gender === 'F') Feminino
                            @else —
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;font-size:0.85rem;">
                            <div style="display:flex;gap:0.5rem;">
                                <button
                                    wire:click="openEdit({{ $member->id }})"
                                    style="background:rgba(124,58,237,0.15);color:#a78bfa;border:1px solid rgba(124,58,237,0.2);border-radius:6px;padding:0.35rem 0.75rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                >Editar</button>
                                <button
                                    wire:click="confirmDelete({{ $member->id }})"
                                    style="background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.2);border-radius:6px;padding:0.35rem 0.75rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                >Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.9rem;">Nenhum colaborador encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;">
            <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:2rem;width:100%;max-width:480px;">
                <h2 style="color:#e2e8f0;font-size:1.1rem;font-weight:700;margin:0 0 1.5rem;">
                    {{ $editingId ? 'Editar colaborador' : 'Novo colaborador' }}
                </h2>

                <div style="display:flex;flex-direction:column;gap:1rem;">
                    {{-- Name --}}
                    <div>
                        <label style="display:block;color:#94a3b8;font-size:0.8rem;margin-bottom:0.4rem;">Nome *</label>
                        <input type="text" wire:model="modalName"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;"
                            placeholder="Nome completo" />
                        @error('modalName') <span style="color:#f87171;font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label style="display:block;color:#94a3b8;font-size:0.8rem;margin-bottom:0.4rem;">Email *</label>
                        <input type="email" wire:model="modalEmail"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;"
                            placeholder="email@exemplo.com" />
                        @error('modalEmail') <span style="color:#f87171;font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Type + Gender --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div>
                            <label style="display:block;color:#94a3b8;font-size:0.8rem;margin-bottom:0.4rem;">Tipo *</label>
                            <select wire:model="modalUserType"
                                style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;">
                                <option value="F">Funcionário</option>
                                <option value="A">Admin</option>
                            </select>
                            @error('modalUserType') <span style="color:#f87171;font-size:0.75rem;">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label style="display:block;color:#94a3b8;font-size:0.8rem;margin-bottom:0.4rem;">Género</label>
                            <select wire:model="modalGender"
                                style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;">
                                <option value="">— Não definido —</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                            </select>
                            @error('modalGender') <span style="color:#f87171;font-size:0.75rem;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label style="display:block;color:#94a3b8;font-size:0.8rem;margin-bottom:0.4rem;">
                            Palavra-passe {{ $editingId ? '(deixar em branco para não alterar)' : '*' }}
                        </label>
                        <input type="password" wire:model="modalPassword"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;"
                            placeholder="Mínimo 8 caracteres" />
                        @error('modalPassword') <span style="color:#f87171;font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password confirmation --}}
                    <div>
                        <label style="display:block;color:#94a3b8;font-size:0.8rem;margin-bottom:0.4rem;">Confirmar palavra-passe</label>
                        <input type="password" wire:model="modalPasswordConfirmation"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;"
                            placeholder="Repita a palavra-passe" />
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:1.5rem;">
                    <button wire:click="$set('showModal', false)"
                        style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        Cancelar
                    </button>
                    <button wire:click="save"
                        style="background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        {{ $editingId ? 'Guardar alterações' : 'Criar colaborador' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($deleteId)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;">
            <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:2rem;width:100%;max-width:400px;">
                <h2 style="color:#e2e8f0;font-size:1.1rem;font-weight:700;margin:0 0 0.75rem;">Confirmar eliminação</h2>
                <p style="color:#94a3b8;font-size:0.9rem;margin:0 0 1.5rem;">Tem a certeza que quer eliminar este colaborador? Esta ação não pode ser revertida.</p>
                <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                    <button wire:click="cancelDelete"
                        style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        Cancelar
                    </button>
                    <button wire:click="deleteStaff"
                        style="background:#dc2626;color:white;border:none;border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

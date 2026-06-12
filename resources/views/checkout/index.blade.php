@extends('layouts.app', ['title' => 'Finalizar compra'])
@section('content')

    <div class="mb-8">
        <h1 class="text-fs-dark text-[1.6rem] font-bold m-0 mb-1">Finalizar compra</h1>
        <p class="text-fs-muted text-[0.9rem] m-0">Confirma os teus dados e método de pagamento</p>
    </div>

    @if (empty($items))
        <div class="text-center py-16 px-8 bg-white border border-fs-border rounded-[2px]">
            <p class="text-fs-gray m-0 mb-4">O teu carrinho está vazio.</p>
            <a href="{{ route('catalog') }}"
                class="bg-fs-purple text-white no-underline rounded-lg px-6 py-2.5 text-[0.9rem] font-semibold">
                Ver catálogo →
            </a>
        </div>
    @else
        <div class="grid gap-6" style="grid-template-columns:1fr 360px;align-items:start;">

            {{-- Checkout form --}}
            <div class="bg-white border border-fs-border rounded-[2px] p-7" x-data="{ paymentType: '{{ old('payment_type', auth()->user()->customer?->default_payment_type ?? 'Visa') }}' }">

                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf

                    {{-- Payment error --}}
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-400 px-4 py-3 rounded-lg mb-5 text-[0.875rem]">
                            &#9888; {{ session('error') }}
                        </div>
                    @endif

                    {{-- Billing info --}}
                    <h3 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-4 pb-3 border-b border-fs-border">
                        Dados de faturação
                    </h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-fs-gray text-[0.78rem] font-medium mb-1.5">NIF *</label>
                            <input type="text" name="nif" maxlength="9" placeholder="123456789"
                                value="{{ old('nif', auth()->user()->customer?->nif) }}"
                                class="w-full bg-white border rounded-[6px] px-3 py-2.5 text-fs-dark text-[0.9rem] outline-none box-border
                                      {{ $errors->has('nif') ? 'border-red-400' : 'border-fs-border' }}
                                      focus:border-fs-purple">
                            @error('nif')
                                <p class="text-red-400 text-[0.73rem] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-fs-gray text-[0.78rem] font-medium mb-1.5">Nome</label>
                            <input type="text" value="{{ auth()->user()->name }}" readonly
                                class="w-full bg-[#0d0d1a] border border-fs-border rounded-[6px] px-3 py-2.5 text-fs-muted text-[0.9rem] outline-none box-border">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-fs-gray text-[0.78rem] font-medium mb-1.5">Morada de entrega *</label>
                        <textarea name="address" rows="2" placeholder="Rua, número, código postal, cidade"
                            class="w-full bg-white border rounded-[6px] px-3 py-2.5 text-fs-dark text-[0.9rem] outline-none box-border resize-y
                                     {{ $errors->has('address') ? 'border-red-400' : 'border-fs-border' }}
                                     focus:border-fs-purple">{{ old('address', auth()->user()->customer?->address) }}</textarea>
                        @error('address')
                            <p class="text-red-400 text-[0.73rem] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment --}}
                    <h3 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-4 pb-3 border-b border-fs-border">
                        Método de pagamento
                    </h3>

                    <div class="flex gap-3 mb-4">
                        <button type="button" @click="paymentType = 'Visa'"
                            :class="paymentType === 'Visa'
                                ?
                                'bg-fs-purple/20 text-fs-purple border-fs-purple' :
                                'bg-white text-fs-muted border-fs-border'"
                            class="flex-1 border rounded-lg px-2 py-2.5 text-[0.82rem] font-medium cursor-pointer transition-all duration-150">
                            💳 Visa
                        </button>
                        <button type="button" @click="paymentType = 'PayPal'"
                            :class="paymentType === 'PayPal'
                                ?
                                'bg-fs-purple/20 text-fs-purple border-fs-purple' :
                                'bg-white text-fs-muted border-fs-border'"
                            class="flex-1 border rounded-lg px-2 py-2.5 text-[0.82rem] font-medium cursor-pointer transition-all duration-150">
                            🅿 PayPal
                        </button>
                        <button type="button" @click="paymentType = 'MB WAY'"
                            :class="paymentType === 'MB WAY'
                                ?
                                'bg-fs-purple/20 text-fs-purple border-fs-purple' :
                                'bg-white text-fs-muted border-fs-border'"
                            class="flex-1 border rounded-lg px-2 py-2.5 text-[0.82rem] font-medium cursor-pointer transition-all duration-150">
                            📱 MB WAY
                        </button>
                    </div>

                    {{-- Hidden input for payment type --}}
                    <input type="hidden" name="payment_type" :value="paymentType">

                    <div class="mb-5">
                        <label class="block text-fs-gray text-[0.78rem] font-medium mb-1.5">
                            <span x-show="paymentType === 'Visa'">Número do cartão (16 dígitos) *</span>
                            <span x-show="paymentType === 'PayPal'">Email PayPal *</span>
                            <span x-show="paymentType === 'MB WAY'">Número de telemóvel (9 dígitos) *</span>
                        </label>
                        <input name="payment_ref" :type="paymentType === 'PayPal' ? 'email' : 'text'"
                            :placeholder="paymentType === 'Visa' ? '4XXXXXXXXXXXXXXX' : (paymentType === 'PayPal' ?
                                'email@paypal.com' : '9XXXXXXXX')"
                            value="{{ old('payment_ref', auth()->user()->customer?->default_payment_ref) }}"
                            class="w-full bg-white border rounded-[6px] px-3 py-2.5 text-fs-dark text-[0.9rem] outline-none box-border
                                  {{ $errors->has('payment_ref') ? 'border-red-400' : 'border-fs-border' }}
                                  focus:border-fs-purple">
                        @error('payment_ref')
                            <p class="text-red-400 text-[0.73rem] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="mb-6">
                        <label class="block text-fs-gray text-[0.78rem] font-medium mb-1.5">Notas (opcional)</label>
                        <textarea name="notes" rows="2" placeholder="Instruções especiais para a encomenda..."
                            class="w-full bg-white border border-fs-border rounded-[6px] px-3 py-2.5 text-fs-dark text-[0.9rem] outline-none box-border resize-y focus:border-fs-purple">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-fs-purple text-white border-none rounded-lg py-3.5 text-[0.95rem] font-bold cursor-pointer transition-colors duration-200 hover:bg-[#6b5f90]">
                        🔒 Confirmar e pagar €{{ number_format($total, 2) }}
                    </button>
                </form>
            </div>

            {{-- Order summary --}}
            <div class="bg-white border border-fs-border rounded-[2px] p-6 sticky top-20">
                <h3 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-4">
                    Resumo ({{ count($items) }} artigos)
                </h3>
                <div class="flex flex-col gap-2.5 mb-4">
                    @foreach ($items as $item)
                        <div class="flex items-center gap-3">
                            <x-tshirt-preview :colorCode="$item['color_code']" :imageUrl="$item['image'] ? $item['image']->image_url : null" size="40px" />
                            <div class="flex-1 min-w-0">
                                <p class="text-fs-gray text-[0.78rem] m-0 truncate">
                                    {{ $item['image']?->name }}
                                </p>
                                <p class="text-fs-muted text-[0.72rem] m-0">
                                    {{ $item['size'] }} · {{ $item['qty'] }}x
                                </p>
                            </div>
                            <span
                                class="text-[0.82rem] font-semibold {{ $item['has_discount'] ? 'text-[#4ade80]' : 'text-fs-purple' }}">
                                €{{ number_format($item['sub_total'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-fs-border pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-fs-dark font-semibold text-[0.95rem]">Total</span>
                        <span class="text-fs-purple font-bold text-[1.1rem]">€{{ number_format($total, 2) }}</span>
                    </div>
                    <p class="text-fs-muted text-[0.72rem] mt-2 mb-0">
                        Pagamento simulado — não são debitados valores reais.
                    </p>
                </div>
            </div>

        </div>
    @endif

@endsection

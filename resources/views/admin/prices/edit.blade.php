@extends('layouts.admin', ['title' => 'Preços'])

@section('content')

<div class="bg-white border border-fs-border rounded-[2px] p-7">
    <h2 class="text-fs-dark text-base font-bold m-0 mb-6 pb-4 border-b border-fs-border">
        Configuração de preços
    </h2>

    <form method="POST" action="{{ route('admin.prices.update') }}">
        @csrf

        {{-- 2x2 grid --}}
        <div class="grid grid-cols-2 gap-5 mb-5">

            {{-- Unit price catalog --}}
            <div>
                <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Preço unitário — catálogo (€)</label>
                <input type="number" name="unit_price_catalog" step="0.01" min="0"
                    value="{{ old('unit_price_catalog', $price->unit_price_catalog) }}"
                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" />
                @error('unit_price_catalog')
                    <div class="text-[#ef4444] text-[0.75rem] mt-[0.3rem]">{{ $message }}</div>
                @enderror
            </div>

            {{-- Unit price own --}}
            <div>
                <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Preço unitário — própria (€)</label>
                <input type="number" name="unit_price_own" step="0.01" min="0"
                    value="{{ old('unit_price_own', $price->unit_price_own) }}"
                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" />
                @error('unit_price_own')
                    <div class="text-[#ef4444] text-[0.75rem] mt-[0.3rem]">{{ $message }}</div>
                @enderror
            </div>

            {{-- Catalog discount price --}}
            <div>
                <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Preço com desconto — catálogo (€)</label>
                <input type="number" name="unit_price_catalog_discount" step="0.01" min="0"
                    value="{{ old('unit_price_catalog_discount', $price->unit_price_catalog_discount) }}"
                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" />
                @error('unit_price_catalog_discount')
                    <div class="text-[#ef4444] text-[0.75rem] mt-[0.3rem]">{{ $message }}</div>
                @enderror
            </div>

            {{-- Own discount price --}}
            <div>
                <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Preço com desconto — própria (€)</label>
                <input type="number" name="unit_price_own_discount" step="0.01" min="0"
                    value="{{ old('unit_price_own_discount', $price->unit_price_own_discount) }}"
                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" />
                @error('unit_price_own_discount')
                    <div class="text-[#ef4444] text-[0.75rem] mt-[0.3rem]">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Qty discount (full width) --}}
        <div class="mb-7">
            <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Quantidade mínima para desconto (unidades)</label>
            <input type="number" name="qty_discount" step="1" min="1"
                value="{{ old('qty_discount', $price->qty_discount) }}"
                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full max-w-[200px] text-[0.9rem] outline-none" />
            @error('qty_discount')
                <div class="text-[#ef4444] text-[0.75rem] mt-[0.3rem]">{{ $message }}</div>
            @enderror
            <p class="text-[#aaa] text-[0.78rem] mt-[0.4rem] m-0">A partir desta quantidade, os preços com desconto são aplicados automaticamente.</p>
        </div>

        {{-- Save --}}
        <div class="border-t border-fs-border pt-5">
            <button type="submit"
                class="bg-fs-purple text-white border-none rounded-[1px] px-6 py-[0.6rem] cursor-pointer text-[0.9rem] font-semibold transition-colors duration-150 hover:bg-[#6b5f8e]">
                Guardar preços
            </button>
        </div>
    </form>
</div>

@endsection

@extends('layouts.app')

@section('titulo', 'Meus pedidos | Nova Varonil')

@section('conteudo')

<section class="relative overflow-hidden bg-gradient-to-b from-mist to-white">
    <div class="container-x py-14 sm:py-20">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="eyebrow">Portal da ótica</p>
                <h1 class="mt-3 text-3xl font-extrabold text-brand-navy sm:text-4xl">Meus pedidos</h1>
            </div>
            <a href="{{ route('portal.pedidos.novo') }}" class="btn btn-primary">Novo pedido</a>
        </div>

        <div class="mt-10">
            @if ($pedidos->isEmpty())
                <div class="card max-w-xl">
                    <p class="text-sm leading-relaxed text-slate-600">Você ainda não fez nenhum pedido.</p>
                    <a href="{{ route('portal.pedidos.novo') }}" class="btn btn-primary mt-4 inline-flex">Fazer o primeiro pedido</a>
                </div>
            @else
                <div class="card !p-0 overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-mist text-xs font-extrabold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Pedido</th>
                                <th class="px-5 py-3">Cliente</th>
                                <th class="px-5 py-3">Lente</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Valor</th>
                                <th class="px-5 py-3">Data</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pedidos as $pedido)
                                <tr>
                                    <td class="px-5 py-4 font-semibold text-brand-navy">#{{ $pedido->id }}</td>
                                    <td class="px-5 py-4">{{ $pedido->cliente_nome }}</td>
                                    <td class="px-5 py-4">{{ $pedido->lente_nome ?? '—' }}</td>
                                    <td class="px-5 py-4">
                                        <span @class([
                                            'rounded-full px-3 py-1 text-xs font-bold',
                                            'bg-emerald-100 text-emerald-700' => $pedido->status === \App\Models\Pedido::STATUS_ENTREGUE,
                                            'bg-red-100 text-red-700' => $pedido->status === \App\Models\Pedido::STATUS_CANCELADO,
                                            'bg-amber-100 text-amber-700' => ! in_array($pedido->status, [\App\Models\Pedido::STATUS_ENTREGUE, \App\Models\Pedido::STATUS_CANCELADO]),
                                        ])>{{ $pedido->statusLabel() }}</span>
                                    </td>
                                    <td class="px-5 py-4">R$ {{ number_format((float) $pedido->preco_total, 2, ',', '.') }}</td>
                                    <td class="px-5 py-4">{{ $pedido->created_at->format('d/m/Y') }}</td>
                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('portal.pedidos.show', $pedido) }}" class="font-semibold text-brand-purple hover:underline">Ver</a>
                                        <a href="{{ route('portal.pedidos.pdf', $pedido) }}" target="_blank" class="ml-3 font-semibold text-slate-500 hover:underline">PDF</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $pedidos->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

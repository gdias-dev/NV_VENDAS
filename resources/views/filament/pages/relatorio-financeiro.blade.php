<x-filament-panels::page>

    <div class="grid gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 sm:grid-cols-3">
        <div>
            <label for="dataInicio" class="text-sm font-medium text-gray-700 dark:text-gray-200">De</label>
            <input type="date" id="dataInicio" wire:model.live="dataInicio"
                class="mt-1 block w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>
        <div>
            <label for="dataFim" class="text-sm font-medium text-gray-700 dark:text-gray-200">Até</label>
            <input type="date" id="dataFim" wire:model.live="dataFim"
                class="mt-1 block w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>
        <div>
            <label for="oticaId" class="text-sm font-medium text-gray-700 dark:text-gray-200">Ótica</label>
            <select id="oticaId" wire:model.live="oticaId"
                class="mt-1 block w-full rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Todas as óticas</option>
                @foreach ($this->oticas as $id => $nome)
                    <option value="{{ $id }}">{{ $nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @php $resumo = $this->resumo; @endphp

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pedidos no período</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $resumo['quantidade'] }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Faturado</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($resumo['faturado'], 2, ',', '.') }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Recebido</p>
            <p class="mt-1 text-2xl font-bold text-green-600">R$ {{ number_format($resumo['recebido'], 2, ',', '.') }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">A receber</p>
            <p class="mt-1 text-2xl font-bold text-amber-600">R$ {{ number_format($resumo['a_receber'], 2, ',', '.') }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Ticket médio</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($resumo['ticket_medio'], 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-base font-bold text-gray-900 dark:text-white">Faturamento por ótica</h2>
        <div class="mt-3 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2">Ótica</th>
                        <th class="px-4 py-2">Pedidos</th>
                        <th class="px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @forelse ($this->porOtica as $linha)
                        <tr>
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $linha->otica?->nome_fantasia ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $linha->quantidade }}</td>
                            <td class="px-4 py-2 font-semibold text-gray-900 dark:text-white">R$ {{ number_format((float) $linha->total, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500">Nenhum pedido faturado nesse período.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-filament-panels::page>

<div class="sm:-mx-6 lg:-mx-8 overflow-x-auto mt-3">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Card number
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Default
                    </th>
                    <th class="px-6 py-3 bg-gray-50" />
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($methods as $method)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <span class="uppercase">
                                    {{ $method->brand }}
                                </span>

                                <div class="inline ml-2">
                                    **** **** **** {{ $method->last_four }}
                                </div>

                                <div class="text-gray-500 text-xs">
                                    Expires on: {{ $method->month }}/{{ $method->year }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap text-center">
                                @if($method->default)
                                    <svg
                                        class="h-6 w-6 text-green-400 mx-auto"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24
                                                    "
                                    >
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <x-jet-secondary-button
                                        wire:loading.attr="disabled"
                                        wire:click="setAsDefault('{{ $method->id }}')"
                                    >
                                        Set as default
                                    </x-jet-secondary-button>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap text-center">
                                <x-jet-danger-button
                                    wire:loading.attr="disabled"
                                    wire:click="deletePaymentMethod('{{ $method->id }}')"
                                >
                                    Delete
                                </x-jet-danger-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

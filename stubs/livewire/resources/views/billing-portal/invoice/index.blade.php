@extends('layouts.billing-portal')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Billing Portal: Invoices
    </h2>
@endsection

@section('content')
    <div class="w-full sm:px-6 lg:px-8">
        <div class="-my-2 sm:-mx-6 lg:-mx-8 overflow-x-auto">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Plan
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 bg-gray-50" />
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        {{ $invoice->description }}
                                        <div class="text-sm text-gray-500">
                                            Issued: {{ \Illuminate\Support\Carbon::createFromTimestamp($invoice->created) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-center">
                                    <span
                                        class="{{ $invoice->paid ? 'bg-indigo-200 text-indigo-800' : 'bg-red-500 text-white' }} px-2 inline-flex text-xs leading-5 font-bold rounded-full capitalize"
                                    >
                                        {{ $invoice->status }}
                                    </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                        <a
                                            href="{{ $invoice->url }}"
                                            class="text-indigo-400 hover:text-indigo-500 font-semibold"
                                            target="_blank"
                                        >
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection




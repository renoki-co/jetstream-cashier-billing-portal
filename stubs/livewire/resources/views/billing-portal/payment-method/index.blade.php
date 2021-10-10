@extends('layouts.billing-portal')
@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Billing: Payment Methods
    </h2>
@endsection
@section('content')
    <div class="w-full sm:px-6 lg:px-8">
        <a
            href="{{ route('billing-portal.payment-method.create') }}"
            method="post"
            as="button"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
        >
            Add New Payment Method
        </a>

        @livewire('list-payment-methods')
    </div>
@endsection




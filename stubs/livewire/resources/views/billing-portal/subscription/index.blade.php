@extends('layouts.billing-portal')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Billing Portal: Subscriptions
    </h2>
@endsection

@section('content')
    @livewire('plans-slide')
@endsection


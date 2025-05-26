@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h1>Welcome to SIKAP</h1>
        <p>This is the dashboard where you can manage vehicles, office supplies, and employee information.</p>
        <code>Current Locale: {{ app()->getLocale() }}</code>
    </div>
@endsection

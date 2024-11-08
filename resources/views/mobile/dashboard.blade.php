@extends('layouts.mobile')

@section('content')
<div class="mobile-container">
    <h1 class="mb-4 text-2xl font-bold">Dashboard</h1>
    <div id="stats-container">
        <!-- Stats will be populated by JavaScript -->
    </div>
    <button id="collect-retribution" class="p-2 mt-4 text-white bg-blue-500 rounded">
        Collect Retribution
    </button>
</div>

<script>
    // This script will be replaced with more robust JS in the next steps
    document.getElementById('collect-retribution').addEventListener('click', () => {
        // Navigate to collection form
        window.location.href = '/mobile/collect';
    });
</script>
@endsection

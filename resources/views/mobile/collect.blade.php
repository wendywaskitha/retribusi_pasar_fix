@extends('layouts.mobile')

@section('content')
<div class="mobile-container">
    <h1 class="mb-4 text-2xl font-bold">Collect Retribution</h1>
    <form id="retribution-form">
        <div class="mb-4">
            <label for="vendor" class="block mb-2">Vendor</label>
            <select id="vendor" name="vendor" class="w-full p-2 border rounded" required>
                <!-- Options will be populated dynamically -->
            </select>
        </div>
        <div class="mb-4">
            <label for="amount" class="block mb-2">Amount</label>
            <input type="number" id="amount" name="amount" class="w-full p-2 border rounded" required>
        </div>
        <button type="submit" class="p-2 text-white bg-green-500 rounded">Submit</button>
    </form>
</div>

<script>
    // This script will be replaced with more robust JS in the next steps
    document.getElementById('retribution-form').addEventListener('submit', (e) => {
        e.preventDefault();
        // Handle form submission
        console.log('Form submitted');
    });
</script>
@endsection

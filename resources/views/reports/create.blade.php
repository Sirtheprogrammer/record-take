<x-app-layout>
    <x-slot name="header">
        <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">Create Daily Report</h2>
    </x-slot>

    @include('reports._form', ['report' => null, 'action' => route('reports.store'), 'method' => 'POST'])

</x-app-layout>

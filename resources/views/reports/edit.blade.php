<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
            <h2 style="font-size:0.95rem; font-weight:600; color:#e8eaf0;">
                Edit Report — {{ $report->report_date->format('d M Y') }}
                @if($report->isSubmitted())
                    <span style="font-size:0.65rem; background:rgba(74,222,128,0.12); color:#4ade80; border:1px solid rgba(74,222,128,0.25); padding:0.15rem 0.6rem; border-radius:3px; margin-left:0.5rem; vertical-align:middle;">SUBMITTED</span>
                @else
                    <span style="font-size:0.65rem; background:rgba(201,168,76,0.12); color:#c9a84c; border:1px solid rgba(201,168,76,0.25); padding:0.15rem 0.6rem; border-radius:3px; margin-left:0.5rem; vertical-align:middle;">DRAFT</span>
                @endif
            </h2>
        </div>
    </x-slot>

    @include('reports._form', ['report' => $report, 'action' => route('reports.update', $report), 'method' => 'PUT'])

</x-app-layout>

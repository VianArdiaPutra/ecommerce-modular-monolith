@php
    $statusLabels = ['pending' => 'Menunggu', 'paid' => 'Dibayar', 'cancelled' => 'Dibatalkan'];
@endphp
<span class="st st-{{ $status }}">{{ $statusLabels[$status] ?? $status }}</span>

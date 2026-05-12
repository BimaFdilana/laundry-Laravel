@if ($status == 'Done')
    <span class="label label-success">Selesai</span>
@elseif($status == 'Delivery')
    <span class="label label-info">Sudah Diambil</span>
@elseif($status == 'Process')
    <span class="label label-primary">Sedang Proses</span>
@elseif($status == 'Antrian')
    <span class="label label-warning">Antrian</span>
@endif

@if ($status == 'Success')
    <span class="label label-success">Sudah Dibayar</span>
@elseif($status == 'Pending')
    <span class="label label-info">Belum Dibayar</span>
@endif

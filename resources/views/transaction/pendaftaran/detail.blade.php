<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{!! $page->title !!}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body p-0">
            <table class="table table-sm mb-0">
                @foreach ($datas as $data)
                    <tr>
                        <th class="w-25 text-right">{{ $data->title }}</th>
                        <th class="w-1">:</th>
                        <td class="w-74 @if ($data->bold) font-weight-bold @endif">
                            @if (str_contains($data->value, 'Belum'))
                                {{ $data->value }}
                            @else
                                <a target="_blank"
                                    href="{{ asset('assets/proposal/' . $data->value) }}">{{ $data->value }}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Keluar</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        unblockUI();
    });
</script>

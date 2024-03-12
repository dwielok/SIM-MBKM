<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{!! $page->title !!}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body p-0">
            <div class="form-message text-center"></div>
            <table class="table table-sm mb-0">
                @foreach ($datas as $data)
                    <tr>
                        <th class="w-25 text-right">{{ $data->title }}</th>
                        <th class="w-1">:</th>
                        <td class="w-74 @if ($data->bold) font-weight-bold @endif">
                            @if (str_contains($data->value, 'Belum'))
                                {{ $data->value }}
                            @else
                                @php
                                    switch ($data->dokumen->dokumen_magang_status) {
                                        case '1':
                                            $status = '<span class="ml-2 badge badge-success">Disetujui</span>';
                                            break;
                                        case '0':
                                            $status = '<span class="ml-2 badge badge-danger">Ditolak</span>';
                                            break;
                                        default:
                                            $status = '';
                                            break;
                                    }

                                    if ($data->type == 'p') {
                                        $aset = 'proposal';
                                    } else {
                                        $aset = 'suratbalasan';
                                    }
                                @endphp
                                <a class="mr-2" target="_blank"
                                    href="{{ asset('assets/' . $aset . '/' . $data->value) }}">{{ $data->value }}</a>
                                {!! $status !!}
                                @if ($data->dokumen->dokumen_magang_status != '1' && $data->dokumen->dokumen_magang_status != '0')
                                    <button class="btn btn-danger btn-sm" id="tolak-dok-{{ $data->type }}"
                                        @if (!$data->aksi) disabled @endif
                                        data-idproposal="{{ $data->dokumen->dokumen_magang_id }}">Tolak</button>
                                    <button class="btn btn-success btn-sm" id="acc-dok-{{ $data->type }}"
                                        @if (!$data->aksi) disabled @endif
                                        data-idproposal="{{ $data->dokumen->dokumen_magang_id }}">Setujui</button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                @if ($surat_balasan)
                    <tr>
                        <th class="w-25 text-right">Status Surat Balasan</th>
                        <th class="w-1">:</th>
                        <td class="w-74">
                            @php
                                switch ($surat_balasan->dokumen_magang_tipe) {
                                    case '1':
                                        $status = '<span class="badge badge-success">Diterima</span>';
                                        break;
                                    case '0':
                                        $status = '<span class="badge badge-danger">Ditolak</span>';
                                        break;
                                    default:
                                        $status = '';
                                        break;
                                }
                            @endphp
                            {!! $status !!}
                        </td>
                    </tr>
                @endif
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

        $("#acc-dok-p").click(function(e) {
            $('.form-message').html('');
            let blc = '#modal-master';
            blockUI(blc);
            $.ajax({
                url: "{{ url('transaksi/pendaftaran/confirm_proposal') }}",
                dataType: 'json',
                type: 'post',
                data: {
                    id: $(this).data('idproposal'),
                    status: 3
                },
                success: function(data) {
                    unblockUI(blc);
                    setFormMessage('.form-message', data);
                    if (data.stat) {
                        dataMaster.draw(false);
                    }
                    closeModal($modal, data);
                }
            });
            return false;
        });

        $("#acc-dok-sb").click(function(e) {
            $('.form-message').html('');
            let blc = '#modal-master';
            blockUI(blc);
            $.ajax({
                url: "{{ url('transaksi/pendaftaran/confirm_sb') }}",
                dataType: 'json',
                type: 'post',
                data: {
                    id: $(this).data('idproposal'),
                    status: 1
                },
                success: function(data) {
                    unblockUI(blc);
                    setFormMessage('.form-message', data);
                    if (data.stat) {
                        dataMaster.draw(false);
                    }
                    closeModal($modal, data);
                }
            });
            return false;
        });

        $("#tolak-dok-p").click(function(e) {
            $('.form-message').html('');
            let blc = '#modal-master';
            blockUI(blc);
            $.ajax({
                url: "{{ url('transaksi/pendaftaran/confirm_proposal') }}",
                dataType: 'json',
                type: 'post',
                data: {
                    id: $(this).data('idproposal'),
                    status: 2
                },
                success: function(data) {
                    unblockUI(blc);
                    setFormMessage('.form-message', data);
                    dataMaster.draw(false);
                    closeModal($modal, data);
                }
            });
            return false;
        });

        $("#tolak-dok-sb").click(function(e) {
            $('.form-message').html('');
            let blc = '#modal-master';
            blockUI(blc);
            $.ajax({
                url: "{{ url('transaksi/pendaftaran/confirm_sb') }}",
                dataType: 'json',
                type: 'post',
                data: {
                    id: $(this).data('idproposal'),
                    status: 2
                },
                success: function(data) {
                    unblockUI(blc);
                    setFormMessage('.form-message', data);
                    if (data.stat) {
                        dataMaster.draw(false);
                    }
                    closeModal($modal, data);
                }
            });
            return false;
        });
    });
</script>

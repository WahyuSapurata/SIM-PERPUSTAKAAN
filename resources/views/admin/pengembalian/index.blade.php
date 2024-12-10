@extends('layouts.layout')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">

                <div class="card">
                    <div class="card-body p-0">
                        <div class="container">
                            <div class="py-5 table-responsive">
                                <table id="kt_table_data"
                                    class="table table-striped table-rounded border border-gray-300 table-row-bordered table-row-gray-300">
                                    <thead class="text-center">
                                        <tr class="fw-bolder fs-6 text-gray-800">
                                            <th>No</th>
                                            <th>Peminjam</th>
                                            <th>Nim</th>
                                            <th>Jurusan</th>
                                            <th>Judul Buku</th>
                                            <th>Tanggal Peminjaman</th>
                                            <th>Batas Peminjaman</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!--end::Container-->
    </div>
@endsection
@section('script')
    <script>
        let control = new Control();

        $(document).on('click', '.button-update', function(e) {
            e.preventDefault();
            control.submitFormMultipartData('/admin/update-peminjaman/' + $(this).attr('data-uuid'),
                'Update',
                'Pengembalian Buku', 'POST');
        })

        $(document).on('keyup', '#search_', function(e) {
            e.preventDefault();
            control.searchTable(this.value);
        })

        const initDatatable = async () => {
            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('#kt_table_data')) {
                $('#kt_table_data').DataTable().clear().destroy();
            }

            // Initialize DataTable
            $('#kt_table_data').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'asc']
                ],
                processing: true,
                ajax: '/admin/get-peminjaman',
                columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    data: 'peminjam',
                    className: 'text-center',
                }, {
                    data: 'nim',
                    className: 'text-center',
                }, {
                    data: 'jurusan',
                    className: 'text-center',
                }, {
                    data: 'buku',
                    className: 'text-center',
                }, {
                    data: 'tanggal_pinjam',
                    className: 'text-center',
                }, {
                    data: 'tanggal_pengembalian',
                    className: 'text-center',
                }, {
                    data: 'status',
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        let result;
                        var tanggalSekarang = '{{ Carbon\Carbon::now()->format('d-m-Y') }}';
                        var tanggalPengembalian = row.tanggal_pengembalian;
                        if (tanggalPengembalian >= tanggalSekarang) {
                            result =
                                `
                                    <div class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success p-2 py-1">
                                        ${data}
                                    </div>
                                `;
                        } else {
                            result =
                                `
                                    <div class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning p-2 py-1">
                                        Denda
                                    </div>
                                `;
                        }
                        return result;
                    }

                }, {
                    data: 'uuid',
                }],
                columnDefs: [{
                    targets: -1,
                    title: 'Aksi',
                    width: '8rem',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `
                            <a href="javascript:;" type="button" data-uuid="${data}" data-kt-drawer-show="true" data-kt-drawer-target="#side_form" class="btn btn-primary button-update btn-icon btn-sm">
                                <svg id="svg-button" xmlns="http://www.w3.org/2000/svg" width="2em" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--> <style>
                                    #svg-button {
                                        fill: white
                                    }
                                </style> <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                            </a>
                        `;
                    },
                }],
                rowCallback: function(row, data, index) {
                    var api = this.api();
                    var startIndex = api.context[0]._iDisplayStart;
                    var rowIndex = startIndex + index + 1;
                    $('td', row).eq(0).html(rowIndex);
                },
            });
        };

        $(function() {
            initDatatable();
        });
    </script>
@endsection

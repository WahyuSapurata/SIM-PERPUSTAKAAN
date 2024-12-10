@extends('layouts.layout')
@section('button')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <button class="btn btn-info btn-sm" id="button-side-form">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" id="svg-button"
                    viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <style>
                        #svg-button {
                            fill: #ffffff
                        }
                    </style>
                    <path
                        d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM217.4 376.9L117.5 269.8c-3.5-3.8-5.5-8.7-5.5-13.8s2-10.1 5.5-13.8l99.9-107.1c4.2-4.5 10.1-7.1 16.3-7.1c12.3 0 22.3 10 22.3 22.3l0 57.7 96 0c17.7 0 32 14.3 32 32l0 32c0 17.7-14.3 32-32 32l-96 0 0 57.7c0 12.3-10 22.3-22.3 22.3c-6.2 0-12.1-2.6-16.3-7.1z" />
                </svg>
                Kembali</button>
            <!--end::Title-->
        </div>
        <!--end::Page title-->
    </div>
@endsection
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">

                <div class="card">
                    <div class="card-body p-0">
                        <!--begin::Card body-->
                        <div class="card-body hover-scroll-overlay-y">
                            <form class="form-data" enctype="multipart/form-data">

                                <input type="hidden" name="id">
                                <input type="hidden" name="uuid">

                                <div class="mb-10">
                                    <label class="form-label">Kategori</label>
                                    <select name="uuid_kategori" class="form-select" data-control="select2"
                                        id="from_select_kategori" data-placeholder="Pilih jenis inputan">
                                    </select>
                                    <small class="text-danger uuid_kategori_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Judul</label>
                                    <input type="text" id="judul" class="form-control" name="judul">
                                    <small class="text-danger judul_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Sinopsis</label>
                                    <textarea id="sinopsis" name="sinopsis"></textarea>
                                    <small class="text-danger sinopsis_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Pengarang</label>
                                    <input type="text" id="pengarang" class="form-control" name="pengarang">
                                    <small class="text-danger pengarang_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Tahun Terbit</label>
                                    <input type="text" id="tahun_terbit" class="form-control" name="tahun_terbit">
                                    <small class="text-danger tahun_terbit_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Penerbit</label>
                                    <input type="text" id="penerbit" class="form-control" name="penerbit">
                                    <small class="text-danger penerbit_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" id="lokasi" class="form-control" name="lokasi">
                                    <small class="text-danger lokasi_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Stok</label>
                                    <input type="number" id="stok" class="form-control" name="stok">
                                    <small class="text-danger stok_error"></small>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Foto</label>
                                    <input class="form-control" accept="image/*" type="file" name="foto"
                                        id="foto">
                                    <small class="text-danger foto_error"></small>

                                    <div class="mt-5" id="logoInfoContainer"></div>

                                </div>

                                <div class="separator separator-dashed mt-8 mb-5"></div>
                                <div class="d-flex gap-5">
                                    <button type="submit"
                                        class="btn btn-success btn-sm btn-submit d-flex align-items-center"><i
                                            class="bi bi-file-earmark-diff"></i> Update</button>
                                </div>
                            </form>
                        </div>
                        <!--end::Card body-->
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

        var currentPath = window.location.pathname;
        var pathParts = currentPath.split('/'); // Membagi path menggunakan karakter '/'
        var lastPart = pathParts[pathParts.length - 1]; // Mengambil elemen terakhir dari array

        $(document).on('click', '#button-side-form', function() {
            window.history.back();
        })

        $(document).on('submit', ".form-data", function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                type: 'POST',
                url: '/admin/manajemen-buku/update-buku/' + lastPart,
                data: new FormData($(".form-data")[0]),
                contentType: false,
                processData: false,
                success: function(response) {
                    $(".text-danger").html("");
                    if (response.success == true) {
                        swal
                            .fire({
                                text: `Buku berhasil di Edit`,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500,
                            })
                            .then(function() {
                                window.location.href = '/admin/manajemen-buku/buku';
                            });
                    } else {
                        $("form")[0].reset();
                        $("#from_select").val(null).trigger("change");
                        // $(".form-select").val(null).trigger("change");
                        swal.fire({
                            title: response.message,
                            text: response.data,
                            icon: "warning",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }
                },
                error: function(xhr) {
                    $(".text-danger").html("");
                    $.each(xhr.responseJSON["errors"], function(key, value) {
                        $(`.${key}_error`).html(value);
                    });
                },
            });
        });

        $(function() {
            control.push_select2('/admin/manajemen-buku/get-kategori', '#from_select_kategori');

            $.ajax({
                url: '/admin/manajemen-buku/show-buku/' + lastPart,
                method: "GET",
                success: function(res) {
                    if (res.success) {
                        $.each(res.data, function(x, y) {
                            if ($("input[name='" + x + "']").is(":radio")) {
                                $("input[name='" + x + "'][value='" + y + "']").prop(
                                    "checked",
                                    true
                                );
                            } else if ($("input[name='" + x + "']").attr("type") === "file") {
                                // Jika input adalah tipe file
                                // Tambahkan logika di sini untuk menangani input file
                                // Misalnya, menampilkan nama file atau melakukan pengolahan tambahan
                                const fileName = y; // Nama file yang diunggah
                                const fileInfoContainer = $(
                                    "#fileInfoContainer"
                                ); // Ganti dengan ID atau kelas sesuai kebutuhan
                                fileInfoContainer.html(
                                    `<a href="/foto_buku/${fileName}" target="_blank" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-2 py-1">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 5px; color: red;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                            </svg>
                                            Lihat File
                                        </div>
                                    </a>`
                                );

                                const logoInfoContainer = $('#logoInfoContainer');
                                logoInfoContainer.html(
                                    `<img id="img-foto" src="/foto_buku/${fileName}" style="max-width:50%;">`
                                );

                            } else {
                                $("input[name='" + x + "']").val(y);
                                $("select[name='" + x + "']").val(y);
                                $("textarea[name='" + x + "']").val(y);
                                $("select[name='" + x + "']").trigger("change");
                            }
                        });

                        // Mendapatkan konten yang akan ditampilkan di editor TinyMCE
                        var sinopsis = res.data.sinopsis;

                        // Set sinopsis ke dalam textarea dengan id "sinopsis"
                        $("#sinopsis").val(sinopsis);

                    }
                },
                error: function(xhr) {
                    alert("Gagal mengambil data.");
                },
            });

            // Konfigurasi untuk TinyMCE
            var options = {
                selector: "#sinopsis",
                height: "480"
            };

            // Menginisialisasi TinyMCE dengan opsi yang telah ditentukan
            tinymce.init(options);
        });
    </script>
@endsection

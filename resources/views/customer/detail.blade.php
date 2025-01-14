@extends('layouts.master')

@section('title', 'Detail Pelanggan')

@section('css-library')
    <link rel="stylesheet" href="@asset('assets/modules/datatables/datatables.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="@route('customers')" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Detail Pelanggan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="@route('dashboard')">Beranda</a></div>
                <div class="breadcrumb-item active"><a href="@route('customers')">Pelanggan</a></div>
                <div class="breadcrumb-item">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget" @if(Agent::isMobile()) style="margin-left:-30px;margin-right:-30px;margin-top:0;" @else style="margin-top:0;" @endif>
                        <div class="profile-widget-header">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Terakhir Pesan</div>
                                    <div class="profile-widget-item-value">{{ ($customer->orders->count() == 0 ? 'Belum pernah pesan' : $customer->orders()->latest('created_at')->first()->created_at->diffForHumans()) }}</div>
                                </div>
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Servis</div>
                                    <div class="profile-widget-item-value">{{ $customer->orders->count() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="profile-widget-name">
                                {{ $customer->name }}
                                <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div> {{ $customer->nik }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    No. HP : {{ $customer->phone }}
                                </div>
                                <div class="col-12">
                                    Tanggal Lahir : {{ $customer->date_of_birth->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-7">
                    <div class="card" @if(Agent::isMobile()) style="margin-left:-30px;margin-right:-30px;" @endif>
                        <div class="card-header">
                            <h4>Riwayat Servis</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="customer-order-list">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="10">
                                                #
                                            </th>
                                            <th>ID Servis</th>
                                            <th>Pelanggan</th>
                                            <th>Jenis</th>
                                            <th>Merek</th>
                                            <th>Warna</th>
                                            <th>Keluhan</th>
                                            <th>Kelengkapan</th>
                                            <th>Harga</th>
                                            <th>Komentar Teknisi</th>
                                            <th>Status</th>
                                            <th>Ditambahkan oleh</th>
                                            <th>Ditambahkan</th>
                                            <th width="150">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @can ('order.update')
        <div class="modal fade" role="dialog" id="modal-update-order">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Perbarui Servis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-update-order">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="" id="update-order-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-6" tooltip="Pelanggan tidak dapat diubah">
                                    <label>Pelanggan</label>
                                    <input type="text" class="form-control" name="customer_id" id="update-order-customer-id" readonly>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Jenis</label>
                                    <select name="type" id="update-order-type" class="form-control" required>
                                        <option value="handphone">HANDPHONE</option>
                                        <option value="laptop">LAPTOP</option>
                                        <option value="printer">PRINTER</option>
                                        <option value="komputer">KOMPUTER</option>
                                        <option value="powerbank">POWERBANK</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Merek</label>
                                    <input type="text" class="form-control" name="merk" id="update-order-merk" style="text-transform:uppercase" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Warna</label>
                                    <input type="text" class="form-control" name="color" id="update-order-color" style="text-transform:uppercase" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Keluhan</label>
                                    <textarea name="complaint" id="update-order-complaint" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Kelengkapan</label>
                                    <textarea name="completeness" id="update-order-completeness" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-update-order">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can ('order.cost')
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-change-cost-order">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Harga Servis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-change-cost-order">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="" id="change-cost-order-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Harga</label>
                                    <input type="text" class="form-control" name="cost" id="change-cost-order-input" onkeyup="setRupiah(this.value, 'change-cost-order-input');" required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-change-cost-order">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can ('order.comment')
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-change-comment-order">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Komentar Servis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-change-comment-order">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="" id="change-comment-order-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Komentar</label>
                                    <textarea name="comment" id="change-comment-order-input" cols="30" rows="20" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-change-comment-order">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can ('order.status')
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-change-status-order">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Status Servis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-change-status-order">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="" id="change-status-order-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Status</label>
                                    <select name="status" id="change-status-order-input" class="form-control" required>
                                        <option value="1">Proses</option>
                                        <option value="2">Batal</option>
                                        <option value="3">Sudah Jadi/Bisa Diambil</option>
                                        <option value="4">Sudah Diambil</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-change-status-order">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('js-library')
    <script src="@asset('assets/modules/datatables/datatables.min.js')"></script>
    <script src="@asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')"></script>
    <script src="@asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js')"></script>
    <script src="@asset('assets/modules/jquery-ui/jquery-ui.min.js')"></script>
@endsection

@section('js-script')
    <script>
        $(function () {
            "use strict";

            getCustomerOrders();

            @can ('order.create')
                $("#form-add-order").on("submit", function(e) {
                    e.preventDefault();

                    addOrder();
                });
            @endcan

            @can ('order.update')
                $("#form-update-order").on("submit", function(e) {
                    e.preventDefault();

                    updateOrder();
                });
            @endcan

            @can ('order.cost')
                $("#form-change-cost-order").on("submit", function (e) {
                    e.preventDefault();

                    changeCostOrder();
                });
            @endcan

            @can ('order.comment')
                $("#form-change-comment-order").on("submit", function (e) {
                    e.preventDefault();

                    changeCommentOrder();
                });
            @endcan

            @can ('order.status')
                $("#form-change-status-order").on("submit", function (e) {
                    e.preventDefault();

                    changeStatusOrder();
                });
            @endcan
        });

        async function getCustomerOrders()
        {
            $("#customer-order-list").dataTable({
                processing: true,
                serverSide: true,
                ajax: "@route('customers.orders', ['customer' => $customer])",
                destroy: true,
                columns: [
                    { data: 'DT_RowIndex' },
                    { data: 'id' },
                    { data: 'customer_name' },
                    { data: 'type' },
                    { data: 'merk' },
                    { data: 'color' },
                    { data: 'complaint' },
                    { data: 'completeness' },
                    { data: 'cost' },
                    { data: 'comment' },
                    { data: 'status' },
                    { data: 'user_name' },
                    { data: 'created_at' },
                    { data: 'action' },
                ],
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    targets: [6],
                    createdCell: function(cell) {
                        var $cell = $(cell);

                        if($cell.text().length > 100){
                            $(cell).contents().wrapAll("<div class='content'></div>");
                            var $content = $cell.find(".content");

                            $(cell).append($("<a href='javascript:void(0);'>Lihat Selengkapnya</a>"));
                            $btn = $(cell).find("a");

                            $content.css({
                                "height": "50px",
                                "overflow": "hidden"
                            });
                            $cell.data("isLess", true);

                            $btn.click(function () {
                                var isLess = $cell.data("isLess");
                                $content.css("height", isLess ? "auto" : "50px");
                                $(this).text(isLess ? "Lebih Sedikit" : "Lihat Selengkapnya");
                                $cell.data("isLess", !isLess);
                            });
                        }
                    }
                }],
                autoWidth: true,
            });
        }

        @can ('order.update')
            async function getUpdateOrder(obj)
            {
                var id = $(obj).data('id');

                $('#modal-update-order').modal('show');
                $('#form-update-order')[0].reset();

                $.ajax({
                    url: "@route('orders.show')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "id": id,
                        "_method": "POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend() {
                        $("#btn-update-order").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("textarea").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-update-order").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("textarea").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        $('#update-order-id').val(result['data']['id']);
                        $('#update-order-customer-id').val(result['data']['customer']['nik'] +' - '+result['data']['customer']['name']);
                        $('#update-order-type').val(result['data']['type']);
                        $('#update-order-merk').val(result['data']['merk']);
                        $('#update-order-color').val(result['data']['color']);
                        $('#update-order-complaint').val(result['data']['complaint']);
                        $('#update-order-completeness').val(result['data']['completeness']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            async function updateOrder()
            {
                var formData = $("#form-update-order").serialize();

                $.ajax({
                    url: "@route('orders')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-update-order").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("textarea").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-update-order").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("textarea").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-order")[0].reset();
                            $('#modal-update-order').modal('hide');
                            getCustomerOrders();
                        }

                        notification(result['status'], result['message']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can ('order.cost')
            async function getChangeCostOrder(object)
            {
                var id = $(object).data('id');

                $('#modal-change-cost-order').modal('show');
                $('#form-change-cost-order')[0].reset();

                $.ajax({
                    url: "@route('orders.show')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "id": id,
                        "_method": "POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend() {
                        $("#btn-change-cost-order").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-change-cost-order").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        $('#change-cost-order-id').val(result['data']['id']);
                        setRupiah(result['data']['cost'], 'change-cost-order-input')
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            async function changeCostOrder()
            {
                var formData = $("#form-change-cost-order").serialize();

                $.ajax({
                    url: "@route('orders.cost')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-change-cost-order").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-change-cost-order").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-change-cost-order")[0].reset();
                            $('#modal-change-cost-order').modal('hide');
                            getCustomerOrders();
                        }

                        notification(result['status'], result['message']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can ('order.comment')
            async function getChangeCommentOrder(object)
            {
                var id = $(object).data('id');

                $('#modal-change-comment-order').modal('show');
                $('#form-change-comment-order')[0].reset();

                $.ajax({
                    url: "@route('orders.show')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "id": id,
                        "_method": "POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend() {
                        $("#btn-change-comment-order").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-change-comment-order").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        $('#change-comment-order-id').val(result['data']['id']);
                        $('#change-comment-order-input').val(result['data']['comment'])
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            async function changeCommentOrder()
            {
                var formData = $("#form-change-comment-order").serialize();

                $.ajax({
                    url: "@route('orders.comment')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-change-comment-order").addClass('btn-progress');
                        $("textarea").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-change-comment-order").removeClass('btn-progress');
                        $("textarea").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-change-comment-order")[0].reset();
                            $('#modal-change-comment-order').modal('hide');
                            getCustomerOrders();
                        }

                        notification(result['status'], result['message']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can ('order.status')
            async function getChangeStatusOrder(object)
            {
                var id = $(object).data('id');

                $('#modal-change-status-order').modal('show');
                $('#form-change-status-order')[0].reset();

                $.ajax({
                    url: "@route('orders.show')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "id": id,
                        "_method": "POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend() {
                        $("#btn-change-status-order").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-change-status-order").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        $('#change-status-order-id').val(result['data']['id']);
                        $('#change-status-order-input').val(result['data']['status']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            async function changeStatusOrder()
            {
                var formData = $("#form-change-status-order").serialize();

                $.ajax({
                    url: "@route('orders.status')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-change-status-order").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-change-status-order").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-change-status-order")[0].reset();
                            $('#modal-change-status-order').modal('hide');
                            getCustomerOrders();
                        }

                        notification(result['status'], result['message']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can ('order.delete')
            async function deleteOrder(object)
            {
                var id = $(object).data('id');
                Swal.fire({
                    title: 'Anda yakin menghapus servis?',
                    text: 'Semua data yang berhubungan dengan servis akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus!',
                    showLoaderOnConfirm:true,
                    preConfirm: () => {
                        ajax =  $.ajax({
                                    url: "@route('orders')",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        "id": id,
                                        "_method": "DELETE",
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success(result) {
                                        if(result['status'] == 'success'){
                                            getCustomerOrders();
                                        }
                                        swalNotification(result['status'], result['message']);
                                    }
                                });

                        return ajax;
                    }
                })
                .then((result) => {
                    if (result.value) {
                        notification(result.value.status, result.value.message);
                    }
                });
            }
        @endcan
    </script>
@endsection

@extends('layouts-lte.main')
@section('title')
    <title>AdminLTE 3 | Dashboard</title>
@endsection
@section('styling')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Savings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Savings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="card mr-3 ml-3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example1" class="table table-sm table-bordered" style="width: 100%">
                    <thead>
                        <tr class="text-center" style="background-color: #212529; color: #ffff">
                            <th>Period</th>
                            <th>Name</th>
                            <th>Animal</th>
                            <th>Qty</th>
                            <th>Nominal</th>
                            <th style="width: 50px;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dataSavings" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="tagModal" class="mr-1"></span>Savings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="submitSavings" enctype="multipart/form-data" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group row">
                            <div class="col-2">
                                <label for="period">Period</label>
                            </div>
                            <div class="col-10">
                                <input type="text" class="form-control form-control-sm" id="period" name="period">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label for="name">Name</label>
                            </div>
                            <div class="col-10">
                                <select name="name" id="name" class="form-control form-control-sm">
                                    <option value="">--- SELECT ---</option>
                                    @foreach ($dataUser as $item)
                                        <option value="{{ $item->id }}">{{ $item->name . ' | ' . $item->whatsapp }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label for="animal">Animal</label>
                            </div>
                            <div class="col-10">
                                <select name="animal" id="animal" class="form-control form-control-sm">
                                    <option value="">--- SELECT ---</option>
                                    @foreach ($dataAnimal as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label for="qty">Qty</label>
                            </div>
                            <div class="col-10">
                                <input type="number" class="form-control form-control-sm" id="qty" name="qty">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label for="nominal">Nominal</label>
                            </div>
                            <div class="col-10">
                                <input type="number" class="form-control form-control-sm" id="nominal" name="nominal">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2">
                                <label for="address">Address</label>
                            </div>
                            <div class="col-10">
                                <textarea rows="3" type="text" class="form-control form-control-sm" id="address" name="address"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSubmit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="deleted"></div>
@endsection
@section('footer')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        let idEdit = null

        function resetModal() {
            $('#period').val('')
            $('#name').val('').trigger('change')
            $('#animal').val('').trigger('change')
            $('#qty').val('')
            $('#nominal').val('')
            $('#address').val('')
            $('#address').text('')
        }
        $("#example1").DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ['10', '25', '50', '100', 'All']
            ],
            buttons: ['pageLength', {
                text: 'Export',
                extend: 'collection',
                className: 'custom-html-collection ml-2 mr-2',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print'
                ]
            }, "colvis", {
                text: 'Create',
                className: 'mr-2 ml-2',
                action: function(e, dt, button, config) {
                    idEdit = null
                    resetModal()
                    $('#tagModal').text('Create')
                    $('#dataSavings').modal('show');
                }
            }],
            ajax: "{{ route('savings.index') }}",
            columns: [{
                    data: 'period',
                    name: 'period'
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'animal_name',
                    name: 'animal_name'
                },
                {
                    data: 'qty',
                    name: 'qty'
                },
                {
                    data: 'nominal',
                    name: 'nominal'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
        $('#submitSavings').submit(function(e) {
            $('.error_message').each(function(k, v) {
                v.remove()
            })
            e.preventDefault();
            var formData = new FormData(this);
            let linkUrl = "{{ route('savings.store') }}"
            if (idEdit != null) {
                formData.append('_method', 'PATCH');
                linkUrl = "{{ url('/savings') }}/" + idEdit
            }

            $.ajax({
                url: linkUrl,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    // $('body .lds-spinner').css('display', 'flex')
                },
                success: function(d) {
                    logDisplay(d)
                }
            })

        })
        $('#example1 tbody').on('click', '.view', function() {
            let edit_id = $(this).data("target");
            $.ajax({
                type: 'GET',
                url: '{{ url('/savings') }}/' +
                    edit_id,
                dataType: 'json',
                data: {
                    edit_id: edit_id,
                },
                beforeSend: function() {},
                success: function(data) {
                    idEdit = data.id
                    resetModal()
                    $('#tagModal').text('Edit')
                    $('#period').val(data.period)
                    $('#name').val(data.user_id).trigger('change')
                    $('#animal').val(data.animal_id).trigger('change')
                    $('#qty').val(parseFloat(data.qty))
                    $('#nominal').val(parseFloat(data.nominal))
                    $('#address').val(data.address)
                    $('#address').text(data.address)
                    $('#dataSavings').modal('show')
                }
            });
        });
        $('#example1 tbody').on('click', '.delete', function() {
            $('.deleted').empty()
            let delete_name = $(this).data("target");
            let delete_id = $(this).attr("id");
            $('.deleted').append('<form id="formDeleted' + delete_id + '" action="{{ url('/savings') }}/' +
                delete_id +
                '" method="POST" style="display: none;">@method('delete')@csrf</form>');
            if (confirm("Delete Savings " + delete_name + "?")) {
                document.getElementById('formDeleted' + delete_id).submit();
            }
        });

        function logDisplay(data) {
            $('.error_message').each(function(k, v) {
                v.remove()
            })
            switch (data.res) {
                case 200:
                    window.location.href = "{{ route('savings.index') }}";
                case 409:

                default:
                    break;
            }

            if (data.errors) {
                $.map(data.errors, (v, k) => {
                    let log = '<small style="color: red" class="error_message">' + v[0] + '</small>'
                    $('#' + k).parent().append(log)
                })
                alert('Harap isi data berikut');
            }
        }
    </script>
@endsection

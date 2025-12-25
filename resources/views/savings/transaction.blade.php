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
                    <h1 class="m-0">Data Saving Transaction</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">Saving Transaction</li>
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
                            <th>Date</th>
                            {{-- <th>Period</th> --}}
                            <th>Name</th>
                            <th>Animal</th>
                            <th>Qty</th>
                            <th>Amount</th>
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
                    <h5 class="modal-title" id="exampleModalLabel"><span id="tagModal" class="mr-1"></span>Saving
                        Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="submitSavings" enctype="multipart/form-data" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group row">
                            <div class="col-2">
                                <label for="date">Date</label>
                            </div>
                            <div class="col-10">
                                <input type="date" class="form-control form-control-sm" id="date" name="date">
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
                                        <option value="{{ $item->id }}">
                                            {{ $item->period . ' | ' . $item->user->name . ' | ' . (float) $item->qty . ' | ' . $item->animal->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                <label for="evidence">Evidence</label>
                            </div>
                            <div class="col-9">
                                <input type="file" id="evidence" name="evidence">
                            </div>
                            <div class="col-1">
                                <i class="fa fa-eye" style="cursor: pointer" id="showFile"></i>
                            </div>
                        </div>
                        <a id="getTarget" target="_blank">Link Tabungan</a>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSubmit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalShowEvidence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Evidence</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="docFrame" width="600" height="400"></iframe>
                </div>
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
            $('#date').val('')
            $('#name').val('').trigger('change')
            $('#nominal').val('')
            $('#evidence').val('')
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
                    $('#showFile').hide()
                    $('#dataSavings').modal('show');
                }
            }],
            ajax: "{{ route('saving-transaction.index') }}",
            columns: [{
                    data: 'date',
                    name: 'date'
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
                    data: 'amount',
                    name: 'amount'
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
            let linkUrl = "{{ route('saving-transaction.store') }}"
            if (idEdit != null) {
                formData.append('_method', 'PATCH');
                linkUrl = "{{ url('/saving-transaction') }}/" + idEdit
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
                url: '{{ url('/saving-transaction') }}/' +
                    edit_id,
                dataType: 'json',
                data: {
                    edit_id: edit_id,
                },
                beforeSend: function() {},
                success: function(data) {
                    idEdit = data[0].id
                    resetModal()
                    $('#tagModal').text('Edit')
                    $('#date').val(data[0].date)
                    $('#name').val(data[0].saving_id).trigger('change')
                    $('#nominal').val(parseFloat(data[0].amount))
                    $('#getTarget').attr('href', '/tabungan-qurban/' + btoa(data[1]));
                    $('#showFile').show()
                    $('#dataSavings').modal('show')
                }
            });
        });
        $('#showFile').on('click', function() {
            $('#docFrame').attr(
                'src',
                "{{ url('/evidence') }}/" + idEdit
            );

            $('#modalShowEvidence').modal('show');
        });
        $('#example1 tbody').on('click', '.delete', function() {
            $('.deleted').empty()
            let delete_name = $(this).data("target");
            let delete_id = $(this).attr("id");
            $('.deleted').append('<form id="formDeleted' + delete_id +
                '" action="{{ url('/saving-transaction') }}/' +
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
                    window.location.href = "{{ route('saving-transaction.index') }}";
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

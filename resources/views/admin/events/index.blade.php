@extends('admin')

@section("breadcrumbs")
	@include("components.navigations.breadcrumbs",[
		"breadcrumbs" => [
			[
				"name" => "Laman Utama",
				"link" => "admin.dashboard",
			],

			[
				"name" => "Senarai Program",
				"link" => "admin.events.index",
			],
    	]
	])
@endsection

<!-- overwrite current sidebar state-->
@section("sidebar")
    @include("components.navigations.sidebar", ["active" =>"events"])
@endsection

@section("body")
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-xs-6 text-left pb-3 pt-2">
			<h4 class="text-white pt-2"><i class="fa fa-list"></i> Senarai Program</h4>
		</div>
		<div class="col-md-6 col-xs-6 text-right pb-3">
			<a href="{{route('admin.events.create')}}"><button type="button" class="btn btn-success ">Tambah Maklumat</button></a>
			{{-- <h3 class="text-white pb-3"><i class="fas fa-tags"></i> All Products</h3> --}}
		</div>
	</div>


	<div class="col-md-12 jumbotron text-center p5">
	 	<div class="table-wrapper pb-4">
           @include("components.datatable.datatable", [
                    "id" => "users-table",
                    "checkbox" => "disabled",
                    "thead" => [
                        "title" => ["No","Tarikh","Nama Program","Keterangan","Kemaskini/Hapus"],
                        "class" => "th-md",
                        "icons" => "",
                    ]
                ])
        </div>

        <hr class="my-0">
	</div>


	<input type="hidden" name="_deleteID" id="_deleteID">
        @include('components.modals.warning',[
            'id' => 'warning-modal',
            'title'=>'Perhatian!',
            'body' => 'Adakah anda yakin untuk hapuskan maklumat?',
            'btn_type' => 'button',
            'btn_title' => 'Teruskan'
        ])

        <!-- Central Modal Medium Info -->
    <div class="modal fade" id="sucess-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <p class="heading lead" id="info-modal-title"></p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-check fa-4x mb-3 animated rotateIn"></i>
                    <p id="info-modal-body"></p>
                </div>
            </div>

            <!--Footer-->
            <div class="modal-footer justify-content-center">
                <a type="button" class="btn btn-outline-primary waves-effect" data-dismiss="modal">Tutup</a>    
            </div>
        </div>
        <!--/.Content-->
    </div>
    </div>
    <!-- Central Modal Medium Info-->
</div>
@endsection

@section("scripts")
	<script type="text/javascript">

		function delete_data(id) {
            
            var parent_id = $('#_deleteID').val(id);

        }

		$(function(){
			//datatable
	        var table = $('#users-table').DataTable({
	            processing: true,
	            serverSide: true,
	            ajax: '{{route('admin.event.datatable.index')}}',
	            pageLength: 5,
	            columns: [
	                { data: 'event_id', name: 'event_id' },
	                { data: 'date', name: 'date' , searchable: true},
	                { data: 'event_name', name: 'event_name', searchable: true },
	                { data: 'event_description', name: 'event_description', searchable: true },
	                { data: 'btn_edit_delete', name: 'btn_edit_delete', searchable: false },
	            ],

	            // dom: "<'#'>", // horizobtal scrollable datatable

	        });


	        $('#warning-modal-btn').on( "click", function() {

                var event_id = $('#_deleteID').val();
                var csrf_token = '{{csrf_token()}}';

                $.ajax({
                    url: "{{route('admin.event.delete')}}",
                    method : 'post',
                    data: {
                        "_token" : csrf_token,
                        "event_id"  : event_id,
                    },
                    success: function(object){
                        console.log("status returned from server:"+object);

                        if (object.status == 'success') {
                            $('#info-modal-title').html('Berjaya');
                            $('#info-modal-body').html(object.message);
                            $('#warning-modal').modal('hide');

                            table.ajax.reload();

                            $('#sucess-modal').modal('show');
                        }else{
                            $('#info-modal-title').html('Informasi');
                            $('#info-modal-body').html('Server Error, Sila cuba lagi');
                            $('#warning-modal').modal('hide');
                            $('#sucess-modal').modal('show');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown){
                        
                    }
                });
            });

		})

	</script>
@append
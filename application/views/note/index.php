<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Catatan</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
</head>
<body>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h2>Catatan</h2>
			<div id="notif"></div>
			<button id="btnAddNote" class="btn btn-success pull-right" data-toggle="modal" data-target="#addNote">Buat Catatan</button>
		</div>
		<div class="col-md-8 col-md-offset-2">
			<div class="table-responsive">
				<table id="table-note" class="table table-stripped">
					<thead>
						<tr>
							<th>Status</th>
							<th>Catatan</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addNote" tabindex="-1" role="dialog" aria-labelledby="title_add">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="title_add">Tambah Catatan</h4>
		      	</div>
		      	<div class="modal-body">
		      		<form id="form-add-note" action="<?php echo base_url().'note/add' ?>" method="POST">
		      			<input type="hidden" name="id" value="">
		      			<div class="form-group">
		      				<label class="control-label">Isi Catatan <span class="text-danger">*</span></label>
		      				<textarea class="form-control" name="content"></textarea>
		      			</div>
		      		</form>
		      	</div>
		      	<div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			        <button id="save-note" type="button" class="btn btn-success">Simpan</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa
	" crossorigin="anonymous"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			// $('#table-note').DataTable();

			let base_url = '<?php echo base_url() ?>';
			console.log(base_url);
			$.ajax({
	            url: base_url + '/note/get_all' ,
	            type: 'GET',
	            dataType: 'JSON',
	            success: function(data) {
	                if(data.error_no==0){
	                	$('#table-note tbody').html(data.notes);
	                }else{
	                	$('#table-note tbody').html('<tr><td colspan="3" class="text-center">Data kosong</td></tr>');
	                }
	            }
	        });

	        $('#save-note').on('click', function(){
	        	$.ajax({
		            url: $('#form-add-note').attr('action'),
		            type: 'POST',
		            data: $('#form-add-note').serialize(),
		            dataType: 'JSON',
		            success: function(data) {
						if(data.error_no==0){
		            		if(data.notes!=''){
		            			$('#table-note tbody').html(data.notes);
		            		}else{
		            			$('#table-note tbody').html('<tr><td colspan="3" class="text-center">Data kosong</td></tr>');
		            		}
		            	}

						$('#addNote').modal('toggle');
						$('#notif').html(data.msg);
		            }
		        });
	        });

	        $(document).on('change', 'input[name="checkbox_note"]', function () {
	            let id = $(this).attr('id');
	        	let status = 'unchecked';
	        	if($(this).prop('checked') == true) status = 'checked';

				$.ajax({
		            url: base_url + 'note/check',
		            type: 'POST',
		            data: {
		            	id: id,
		            	status: status
		            },
		            dataType: 'JSON',
		            success: function(data) {
		            	if(data.error_no==0) $('#table-note tbody').html(data.notes);
						$('#notif').html(data.msg);
		            }
		        });
			});

	        $('#btnAddNote').on('click', function(){
	        	$('#title_add').text('Tambah Catatan');
				$('#form-add-note input[name="id"]').val('');
	        	$('textarea[name="content"]').val('');
	        });
		});

		function editNote(id){
			$('#title_add').text('Edit Catatan');
			$('#form-add-note input[name="id"]').val(id);
			$('#form-add-note textarea[name="content"]').val($('#content_'+id).html());
		}

        function deleteNote(id){
			let base_url = '<?php echo base_url() ?>';
        	$.ajax({
	            url: base_url + 'note/delete',
	            type: 'POST',
	            data: {
	            	id: id
	            },
	            dataType: 'JSON',
	            success: function(data) {
	            	if(data.error_no==0){
	            		if(data.notes!=''){
	            			$('#table-note tbody').html(data.notes);
	            		}else{
	            			$('#table-note tbody').html('<tr><td colspan="3" class="text-center">Data kosong</td></tr>');
	            		}
	            	}
					$('#notif').html(data.msg);
	            }
	        });
		}

	</script>
</body>
</html>
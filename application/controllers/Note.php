<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Note extends CI_Controller {
	function __construct(){
        parent::__construct();
		$this->load->model('note_model');
    }

	public function index(){
		$this->load->view('note/index');
	}

	public function get_all_note(){
		$notes = $this->note_model->get_all_note();
		$notes = $notes->result_array();

		if(!empty($notes)){
			$html = '';

			$i = 0;
			foreach ($notes as $note) {
				if($note['status']=='unchecked'){
					$html .= '<tr><td><input type="checkbox" name="checkbox_note" id="'.$this->enc->encode($note['id']).'"></td><td id="content_'.$note['id'].'">'.$note['content'].'</td><td width="200px"><button data-toggle="modal" data-target="#addNote" onclick="editNote(\''.$note['id'].'\')" class="btn btn-info">Edit</button> <a href="javascript:;" onclick="deleteNote(\''.$this->enc->encode($note['id']).'\')" class="btn btn-danger">Hapus</a></td></tr>';
				}else{
					$html .= '<tr><td><input type="checkbox" name="checkbox_note" id="'.$this->enc->encode($note['id']).'" checked="checked"></td><td><s id="content_'.$note['id'].'">'.$note['content'].'</s></td><td width="200px"><button data-toggle="modal" data-target="#addNote" onclick="editNote(\''.$note['id'].'\')" class="btn btn-info">Edit</button> <a href="javascript:;" onclick="deleteNote(\''.$this->enc->encode($note['id']).'\')" class="btn btn-danger">Hapus</a></td></tr>';
				}

				$i++;
			}

			$data['error_no'] = 0;
			$data['notes'] = $html;
		}else{
			$data['error_no'] = 1;
			$data['error_msg'] = 'Data not found';
		}

		return $data;
	}

	public function get_all(){
		$data = $this->get_all_note();
		echo json_encode($data);
	}

	public function add(){
		$this->form_validation->set_rules('content', 'Isi catatan', 'required|trim');

		if($this->form_validation->run()==false){
            $data['error_no'] = 1;
            $data['msg'] = '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> '.form_error('content').'</div>';;
        }else{
        	$id = $this->input->post('id');
        	$content = $this->input->post('content');

        	if(!empty($id)){
        		$add = $this->note_model->update_note($id, $content);
        	}else{
        		$data_input['content'] = $content;
        		$data_input['date'] = date('Y-m-d H:i:s');
        		$add = $this->note_model->add_note($data_input);
        	}

        	if($add){
        		$notes = $this->get_all_note();

	    		if(!empty($notes['notes'])){
		    		$data['notes'] = $notes['notes'];
		    	}else{
		    		$data['notes'] = '';
		    	}

        		$data['error_no'] = 0;
        		$data['msg'] = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Berhasil menambah catatan!</div>';
        	}else{
        		$data['error_no'] = 1;
				$data['msg'] = 'Gagal menambahkan catatan';
        	}

        }

		echo json_encode($data);
	}

	public function delete(){
    	$id = $this->enc->decode($this->input->post('id'));

    	if(isset($id)){
    		$delete = $this->note_model->delete_note($id);

    		if($delete){
	    		$notes = $this->get_all_note();

	    		if(!empty($notes['notes'])){
		    		$data['notes'] = $notes['notes'];
		    	}else{
		    		$data['notes'] = '';
		    	}

				$data['error_no'] = 0;
	    		$data['msg'] = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Berhasil menghapus catatan!</div>';
    		}else{
    			$data['error_no'] = 1;
				$data['msg'] = 'Gagal menghapus catatan';
    		}
    	}else{
    		$data['error_no'] = 1;
			$data['msg'] = 'Gagal menghapus catatan';
    	}

		echo json_encode($data);
	}

	public function check(){
    	$id = $this->enc->decode($this->input->post('id'));
    	$status = $this->input->post('status');

    	if(isset($id) && isset($status)){
    		$update = $this->note_model->check_note($id, $status);

    		if($update){
    			$status_display = $status=='checked' ? 'menandai' : 'menghilangkan tanda';
    			$data['error_no'] = 0;
	    		$data['msg'] = '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Berhasil '.$status_display.' catatan!</div>';

	    		$notes = $this->get_all_note();
	    		$data['notes'] = $notes['notes'];
    		}else{
    			$data['error_no'] = 1;
				$data['msg'] = 'Gagal menghapus catatan';
    		}
    	}else{
    		$data['error_no'] = 1;
			$data['msg'] = 'Gagal menghapus catatan';
    	}

		echo json_encode($data);
	}

}

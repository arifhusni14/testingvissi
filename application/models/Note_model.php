<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Note_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

    public function get_all_note(){
        $this->db->select('*');
        $this->db->from('note');
        $this->db->where('deleted', 0);
        $this->db->order_by('date', 'desc');
        return $this->db->get();
    }

    public function add_note($data_input){
        $this->db->insert('note', $data_input);
        return $this->db->insert_id();
    }

    public function update_note($id, $content){
        $this->db->set('content', $content);
        $this->db->where('id', $id);
        $update = $this->db->update('note');
        return $update;
    }

    public function delete_note($id){
        $this->db->set('deleted', 1);
        $this->db->where('id', $id);
        $update = $this->db->update('note');
        return $update;
    }

    public function check_note($id, $status){
        $this->db->set('status', $status);
        $this->db->where('id', $id);
        $update = $this->db->update('note');
        return $update;
    }

}
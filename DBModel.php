<?php
namespace App\Models;
use CodeIgniter\Model;


class DBModel extends Model
{
	// protected $DBGroup = 'default';
	// private $db;

	public function __construct()
    {
        parent::__construct();
        // $this->db = \Config\Database::connect();
        // $this->db = db_connect();
    }


	public function getCondData($table,$condition, $orderby="'id ASC'"){
		

		$query = $this->db
					->table($table)
					->select('*')
					->where($condition)
					->orderBy($orderby)
					->get()					
					;
		
		return $query->getResult();
	}

	public function getAllData($table, $orderby = "'id ASC'")
	{
		// $this->db->orderBy($orderby);
		$query =  $this->db
					->table($table)
					->select('*')
					->orderBy($orderby)
					->get();

		return $query->getResult();
	}

	public function getListData($table, $colname, $listarray)
	{
		$query = $this->db
				->table($table)
				->select('*')
				->whereIn($colname, $listarray)
				->get()
				->getResult()
				;
		//echo $this->db->getLastQuery(); 
		return $query;
	}

	public function getActiveData($table, $orderby = "'id ASC'")
	{
		// $this->db->where('active="Y"');
		$active_cond = ['active' => 'Y'];
		$query = $this->db
					->table($table)
					->select('*')
					->where($active_cond)
					->orderBy($orderby)
					->get()
					->getResult()
					;
		
		// echo $this->db->getLastQuery();  
		return $query;
	}

	public function insertData($table, $data)
	{
		//print_r($data);
		if ($this->db
			->table($table)
			->set($data)
			->insert()
		) {
			//echo $this->db->getLastQuery();  
			// return $this->db->insertID();
			return $this->db->insertID();
		} else {
			return false;
		}
	}


	public function updateData($table, $data, $where_cond)
	{
		$query = $this->db
					->table($table)
					->where($where_cond)
					->set($data)
					->update()
					;
		//  echo $this->db->getLastQuery();  
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	public function deleteData($table, $where_cond)
	{
		// $this->db->where($where_cond);
		$query = $this->db
				 ->table($table)
				 ->where($where_cond)
				 ->delete()
				 ;
		echo $this->db->getLastQuery();  
		if ($query) {
			return true;
		} else {
			return false;
		}
	}


	public function rowsCount($table)
	{
		$nrows = $this->db
				 ->table($table)
				 ->countAllResults();					
		//echo $this->db->getLastQuery();
		return $nrows;
	}

	public function rowsCondCount($table, $cond)
	{
		// $this->db->where($cond);
		return $this->db
					->table($table)
					->where($cond)
					->countAllResults();
	}


	public function fetch_records($tablnam, $srchcond = '', $pagconfg = '', $orderby = "'id DESC'")
	{
		//print_r($srchcond);
		$query = $this->db->table($tablnam);
		//$this->db->where('active="Y"');

		if ($srchcond != '') {
			if (count($srchcond) > 0) {
				$query = $this->db->like($srchcond);
			}
		}

		if ($pagconfg != '') {
			if ($pagconfg['srtcol'] != '') {
				$query = $this->db->orderBy($pagconfg['srtcol'].' '. $pagconfg['srtord']);
			} else {
				$query = $this->db->orderBy($orderby);
			}
		}

		if ($pagconfg != '') {
			if (count($pagconfg) > 0) {
				$query = $this->db
							->limit($pagconfg['per_page'], $pagconfg['per_page'] * $pagconfg['curpage'])
							->get();
			}
		}
		// $query = $this->db->get();

		//echo $this->db->getLastQuery();
		return $query->getResult();
	}

	public function userQuery($dbquery, $result_return = true, $result_type = 'result')
	{
		if ($result_return == true) {
			$query = $this->db->query($dbquery);
			if ($result_type == 'result') {
				return $query->getResult();
			} elseif ($result_type == 'array') {
				return $query->getResult('array');
			} else {
				return ($this->db->error());
			}
		} else {
			if ($this->db->query($dbquery)) {
				return true;
			} else {
				return  $this->db->error();
			}
		}
	}

	public function getRow($table, $condition)
	{
		$query = $this->db
					->table($table)
					->where($condition)
					->get()
					;
		// $this->db->get($table);
		//echo $this->db->getLastQuery();
		$row = $query->getRow();
		if ($row)
			return $row;
		else
			return false;
	}
}
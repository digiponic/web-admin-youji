<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminTbAlamatPelangganController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "tb_alamat_pelanggan";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Jenis","name"=>"keterangan"];
			$this->col[] = ["label"=>"Provinsi","name"=>"kode_provinsi","join"=>"tb_provinsi,keterangan"];
			$this->col[] = ["label"=>"Kota","name"=>"kode_kota","join"=>"tb_kota,keterangan"];
			$this->col[] = ["label"=>"Kecamatan","name"=>"kode_kecamatan","join"=>"tb_kecamatan,keterangan"];
			$this->col[] = ["label"=>"Alamat","name"=>"alamat"];
			$this->col[] = ["label"=>"Utama","name"=>"utama","callback"=>function($row){
				return ($row->utama == 1) ? 'Ya' : 'Tidak';
			}];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'','name'=>'id_customer','type'=>'hidden'];
			$this->form[] = ['label'=>'Jenis','name'=>'keterangan','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','placeholder'=>'Cth: Rumah, Kantor, Sekolah'];
			$this->form[] = ['label'=>'Provinsi','name'=>'kode_provinsi','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'tb_provinsi,keterangan','datatable_where'=>'is_visible = 1'];
			$this->form[] = ['label'=>'Kota','name'=>'kode_kota','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'tb_kota,keterangan','datatable_where'=>'is_visible = 1','parent_select'=>'kode_provinsi'];
			$this->form[] = ['label'=>'Kecamatan','name'=>'kode_kecamatan','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'tb_kecamatan,keterangan','datatable_where'=>'is_visible = 1','parent_select'=>'kode_kota'];
			$this->form[] = ['label'=>'Kodepos','name'=>'kodepos','type'=>'number','width'=>'col-sm-2','placeholder'=>'Cth: 65153'];
			$this->form[] = ['label'=>'RT','name'=>'rt','type'=>'text','validation'=>'min:1|max:3','width'=>'col-sm-2','placeholder'=>'Cth: 002'];
			$this->form[] = ['label'=>'RW','name'=>'rw','type'=>'text','validation'=>'min:1|max:3','width'=>'col-sm-2','placeholder'=>'Cth: 004'];
			$this->form[] = ['label'=>'Alamat','name'=>'alamat','type'=>'textarea','validation'=>'required','width'=>'col-sm-10','placeholder'=>'Cth: Jln. Perusahaan No 27 Karanglo (Sebelah kiri pom bensin karanglo)'];
			$this->form[] = ['label'=>'Alamat Utama','name'=>'utama','type'=>'radio','validation'=>'required','width'=>'col-sm-10','dataenum'=>'1|Ya;0|Tidak','value'=>'0'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Customer","name"=>"id_customer","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"customer,id"];
			//$this->form[] = ["label"=>"Jenis Alamat","name"=>"jenis_alamat","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Alamat","name"=>"alamat","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"RT","name"=>"RT","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Kode Provinsi","name"=>"kode_provinsi","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Kode Kota","name"=>"kode_kota","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Kode Kecamatan","name"=>"kode_kecamatan","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Default","name"=>"default","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here
			if($postdata['utama'] == 1){
				$exist = DB::table('tb_alamat_pelanggan')
							->where('id_customer', $postdata['id_customer'])
							->select('id')
							->get();
				$ids = array();
				foreach ($exist as $value) {
					array_push($ids, $value->id);
				}
				
				DB::table('tb_alamat_pelanggan')->whereIn('id',$ids)->update(['utama'=>0]);
			}
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here
			
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
			if($postdata['utama'] == 1){
				$exist = DB::table('tb_alamat_pelanggan')
							->where('id_customer', $postdata['id_customer'])
							->select('id')
							->get();
				$ids = array();
				foreach ($exist as $value) {
					array_push($ids, $value->id);
				}
				
				DB::table('tb_alamat_pelanggan')->whereIn('id',$ids)->update(['utama'=>0]);
			}
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :) 


	}
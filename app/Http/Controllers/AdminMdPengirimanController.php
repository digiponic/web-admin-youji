<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminMdPengirimanController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "tanggal,desc";
			$this->global_privilege = false;
			$this->button_table_action = false;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "tb_penjualan";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Kurir","name"=>"id_kurir",'join'=>'tb_kurir,keterangan'];
			$this->col[] = ["label"=>"Kode","name"=>"kode"];
			$this->col[] = ["label"=>"Pengiriman","name"=>"tanggal","callback_php"=>'date("d-m-Y | H:i", strtotime($row->tanggal))','visible'=>false];			
			$this->col[] = ["label"=>"Tanggal","name"=>"created_at","callback"=>function($row){
				$str = '<p class="txt_pesan">Pesan : '.date("d-m-Y | H:i", strtotime($row->created_at)).'</p>';
				$str .= '<p class="txt_kirim">Kirim : '.date("d-m-Y | H:i", strtotime($row->tanggal)).'</p>';
				return $str;
			}];
			$this->col[] = ["label"=>"Pelanggan","name"=>"id_customer",'join'=>'tb_customer,name'];
			$this->col[] = ["label"=>"Catatan","name"=>"keterangan"];
			$this->col[] = ["label"=>"Alamat","name"=>"alamat_detail"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			// $this->form[] = ['label'=>'Kode','name'=>'kode','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Keterangan','name'=>'keterangan','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Tanggal','name'=>'tanggal','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Subtotal','name'=>'subtotal','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Pajak','name'=>'pajak','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Diskon Tipe','name'=>'diskon_tipe','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Diskon','name'=>'diskon','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Users Id','name'=>'users_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'users,id'];
			// $this->form[] = ['label'=>'Customer Id','name'=>'customer_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'customer,id'];
			// $this->form[] = ['label'=>'Status','name'=>'status','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Metode Pembayaran','name'=>'metode_pembayaran','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Cabang','name'=>'id_cabang','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'cabang,id'];
			// $this->form[] = ['label'=>'Platform','name'=>'platform','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Kode","name"=>"kode","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Keterangan","name"=>"keterangan","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Tanggal","name"=>"tanggal","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"Subtotal","name"=>"subtotal","type"=>"money","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Pajak","name"=>"pajak","type"=>"money","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Diskon Tipe","name"=>"diskon_tipe","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Diskon","name"=>"diskon","type"=>"money","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Grand Total","name"=>"grand_total","type"=>"money","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Users Id","name"=>"users_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"users,id"];
			//$this->form[] = ["label"=>"Customer Id","name"=>"customer_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"customer,id"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Metode Pembayaran","name"=>"metode_pembayaran","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Cabang","name"=>"id_cabang","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"cabang,id"];
			//$this->form[] = ["label"=>"Platform","name"=>"platform","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
			$kurir = DB::table('tb_kurir')->whereNull('deleted_at')->get();
			foreach ($kurir as $k) {
				$str = 'Set '.$k->keterangan;
				$slug = str_slug($str,'_');
				$this->button_selected[] = ['label'=>$str,'icon'=>'fa fa-check','name'=>$slug];
			}			
	                
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
	        | @color  = Color Bootstrap.
	        | @data_toggle  = If using modal.
	        | @data_target  = If using modal.
	        | 
	        */
	        $this->index_button = array();
			// $this->index_button[] = ['label'=>'Atur Kurir','url'=>'#','color'=>'danger',"icon"=>"fa fa-print",'data_toggle'=>'modal','data_target'=>'#myModal'];


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
			$this->pre_index_html = '
				<div id="myModal" class="modal fade" role="dialog">
					<div class="modal-dialog">

						<!-- Modal content-->
						<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Modal Header</h4>
						</div>
						<div class="modal-body">
							<p>Some text in the modal.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
						</div>

					</div>
				</div>
			';
	        
	        
	        
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
			$this->style_css = "
				// .txt_pesan{ color: #27ae60;	}
				.txt_kirim{ color: #e84118;	}
			";
	        
	        
	        
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
		
		// public function getIndex()
		// {
		// 	if(!CRUDBooster::isView()) CRUDBooster::denyAccess();
			
		// 	$query = "SELECT
		// 					tb_penjualan.id,
		// 					tb_penjualan.kode,
		// 					tb_penjualan.tanggal,
		// 					tb_customer.`name` AS pelanggan,
		// 					tb_customer.phone AS telepon,
		// 					tb_customer.address AS alamat,
		// 					tb_general.keterangan AS metode_pembayaran,
		// 					tb_kecamatan.keterangan AS kecamatan,
		// 					tb_kota.keterangan AS kota
		// 				FROM
		// 					tb_penjualan
		// 				INNER JOIN tb_customer ON tb_penjualan.customer_id = tb_customer.id
		// 				INNER JOIN tb_general ON tb_penjualan.metode_pembayaran = tb_general.id
		// 				INNER JOIN tb_kota ON tb_customer.kode_kota = tb_kota.id
		// 				INNER JOIN tb_kecamatan ON tb_customer.kode_kecamatan = tb_kecamatan.id
		// 				";
		// 	$data = [];
		// 	$data['page_title'] = 'Pengiriman';
		// 	$data['result'] = DB::select($query);
				
		// 	$this->cbView('index_pengiriman',$data);			
		// }

		public function getTerkirim($id)
		{
			
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
			$kurir = DB::table('tb_kurir')->whereNull('deleted_at')->get();
	        foreach ($kurir as $k) {
				$str = 'Set '.$k->keterangan;
				$slug = str_slug($str,'_');
				if($button_name == $slug){
					DB::table('tb_penjualan')->whereIn('id',$id_selected)->update(['id_kurir'=>$k->id]);
				}
			}
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
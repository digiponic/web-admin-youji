<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminTbProdukTransferController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "tb_produk_transfer";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Tanggal","name"=>"tanggal"];
			$this->col[] = ["label"=>"Gudang Asal","name"=>"gudang_asal","join"=>"tb_general,keterangan"];
			$this->col[] = ["label"=>"Gudang Tujuan","name"=>"gudang_tujuan","join"=>"tb_general,keterangan"];
			$this->col[] = ["label"=>"Keterangan","name"=>"keterangan"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			$tanggal = date('Y-m-d H:i:s');

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Tanggal','name'=>'tanggal','type'=>'datetime','value'=>$tanggal,'validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Gudang Asal','name'=>'gudang_asal','type'=>'select2','validation'=>'min:1|max:255','width'=>'col-sm-5','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 8'];
			$this->form[] = ['label'=>'Gudang Tujuan','name'=>'gudang_tujuan','type'=>'select2','validation'=>'min:1|max:255','width'=>'col-sm-5','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 8'];
			$columns[] = ['label'=>'Produk','name'=>'kode_produk','required'=>true,'type'=>'datamodal','datamodal_table'=>'tb_produk','datamodal_columns'=>'keterangan,harga,stok,satuan_keterangan,gudang_keterangan','datamodal_columns_alias'=>'Produk,Harga,Stok,Satuan,Gudang','datamodal_select_to'=>'harga:harga,satuan_keterangan:satuan_keterangan, gudang_keterangan:gudang_keterangan','datamodal_where'=>'stok != 0', 'datamodal_size'=>'large'];
			$columns[] = ['label'=>'Jumlah Transfer','name'=>'jumlah_transfer','required'=>true,'type'=>'text'];
			$this->form[] = ['label'=>'Detil Produk','name'=>'produk_detail','type'=>'child','width'=>'col-sm-10','table'=>'tb_produk_detail','foreign_key'=>'kode_produk','columns'=>$columns];
			$this->form[] = ['label'=>'Keterangan','name'=>'keterangan','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-5'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Tanggal','name'=>'tanggal','value'=>$tanggal,'type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-5'];
			//$this->form[] = ['label'=>'Gudang Asal','name'=>'gudang_asal','type'=>'select2','validation'=>'min:1|max:255','width'=>'col-sm-5','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 8'];
			//$this->form[] = ['label'=>'Gudang Tujuan','name'=>'gudang_tujuan','type'=>'select2','validation'=>'min:1|max:255','width'=>'col-sm-5','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 8'];
			//$columns[] = ['label'=>'Produk','name'=>'kode_produk','required'=>true,'type'=>'datamodal','datamodal_table'=>'tb_produk','datamodal_columns'=>'keterangan,harga,stok,satuan_keterangan','datamodal_columns_alias'=>'Produk,Harga,Stok,Satuan','datamodal_select_to'=>'harga:harga,satuan_keterangan:satuan_keterangan','datamodal_where'=>'stok != 0', 'datamodal_size'=>'large'];
			//$columns[] = ['label'=>'Jumlah Transfer','name'=>'jumlah','required'=>true,'type'=>'text'];
			//$this->form[] = ['label'=>'Detil Produk','name'=>'produk_detail','type'=>'child','columns'=>$columns,'table'=>'tb_produk_detail','foreign_key'=>'kode_produk'];
			//
			////$this->form[] = ['label'=>'Jumlah Transfer','name'=>'jumlah_transfer','type'=>'number','validation'=>'integer|min:0','width'=>'col-sm-5','readonly'=>true];
			////$this->form[] = ['label'=>'Keterangan','name'=>'keterangan','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-5'];
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
			$produk_transfer = DB::table('tb_produk_transfer')->where('id',$id)->first();
			$produk_transfer_detail = DB::table('tb_produk_transfer_detail')->where('id_transfer',$id)->get();

			foreach($produk_transfer_detail as $pd) {
				$produk = DB::table('tb_produk')->where('id',$pd->id_produk)->first();
				$array = array(
					'id_transfer'		=> $produk_transfer->id,
					'kode_produk'		=> $produk->kode,
					'nama_produk'		=> $produk->keterangan,
					'satuan'			=> $produk->satuan,
					'jumlah_transfer'	=> $produk_transfer->$jumlah_transfer,
					'gudang_asal'		=> $produk_transfer->$gudang_asal,					
					'gudang_tujuan'		=> $produk_transfer->$gudang_tujuan,					
					);
				$produk_stok = array(
					'kode_produk'	=> $pd->id_produk,
					'stok_masuk'	=> 0,
					'stok_keluar'	=> $pd->kuantitas,
					'keterangan'	=> 'Produk telah di transfer dari gudang'.$produk_transfer->gudang_asal.'ke'.$produk_transfer->gudang_tujuan
				);

				DB::table('tb_produk_transfer_detail')->where('id',$pd->id)->update($array);
				DB::table('tb_produk_stok')->insert($produk_stok);
				DB::table('tb_produk')->where('id',$pd->id_produk)->update(['stok'=> abs($produk->stok - $pd->kuantitas)]);
			}				


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
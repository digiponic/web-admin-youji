<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Mike42\Escpos\Printer;
	use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
	use Mike42\Escpos\CapabilityProfile;

	class AdminTbPembayaranController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "1000";
			$this->orderby = "tanggal,desc";
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
			$this->button_export = true;
			$this->table = "tb_pembayaran";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Tanggal","name"=>"tanggal"];
			$this->col[] = ["label"=>"Faktur","name"=>"faktur"];
			$this->col[] = ["label"=>"Rekening","name"=>"rekening","join"=>"tb_pelanggan,rekening"];
			$this->col[] = ["label"=>"Pelanggan","name"=>"rekening","join"=>"tb_pelanggan,nama"];
			$this->col[] = ["label"=>"Debit Air","name"=>"debit_air"];
			$this->col[] = ["label"=>"Denda","name"=>"denda"];
			$this->col[] = ["label"=>"Bayar","name"=>"bayar"];
			$this->col[] = ["label"=>"Admin","name"=>"admin"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Tanggal','name'=>'tanggal','type'=>'date','validation'=>'required|date','width'=>'col-sm-10','readonly'=>'true'];
			$this->form[] = ['label'=>'Faktur','name'=>'faktur','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>'true'];
			$this->form[] = ['label'=>'Rekening','name'=>'rekening','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'tb_pelanggan','datamodal_columns'=>'rekening,nama,alamat','datamodal_size'=>'small','datamodal_columns_alias_name'=>'Rekening,Nama,Alamat'];
			$this->form[] = ['label'=>'Debit Air','name'=>'debit_air','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			$this->form[] = ['label'=>'Denda','name'=>'denda','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			$this->form[] = ['label'=>'Bayar','name'=>'bayar','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			$this->form[] = ['label'=>'Bulan','name'=>'bulan','type'=>'number','validation'=>'required','width'=>'col-sm-9'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Tanggal','name'=>'tanggal','type'=>'date','validation'=>'required|date','width'=>'col-sm-10','readonly'=>'true'];
			//$this->form[] = ['label'=>'Faktur','name'=>'faktur','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>'true'];
			//$this->form[] = ['label'=>'Rekening','name'=>'rekening','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'tb_pelanggan','datamodal_columns'=>'rekening,nama,alamat','datamodal_size'=>'small','datamodal_columns_alias_name'=>'Rekening,Nama,Alamat'];
			//$this->form[] = ['label'=>'Debit Air','name'=>'debit_air','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			//$this->form[] = ['label'=>'Denda','name'=>'denda','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			//$this->form[] = ['label'=>'Bayar','name'=>'bayar','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			//$this->form[] = ['label'=>'Bulan','name'=>'bulan','type'=>'number','validation'=>'required','width'=>'col-sm-9'];
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
			$this->addaction[] = ['url'=> url("/").'/apipamdesa/api/pembayaran/[id]', 'icon'=>'fa fa-print','color'=>'danger print','title'=>'Print'];

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
	        $this->script_js = null;


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
	        $this->load_js[] = asset("js/recta.js");
	        $this->load_js[] = asset("js/print.js");



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

		 public function getAdd(){
		 	$data['page_title'] = 'Tambah Pembayaran';
			$data['bulan'] = array(
				'1' => 'Januari',
				'2' => 'Februari',
				'3' => 'Maret',
				'4' => 'April',
				'5' => 'Mei',
				'6' => 'Juni',
				'7' => 'Juli',
				'8' => 'Agustus',
				'9' => 'September',
				'10' => 'Oktober',
				'11' => 'November',
				'12' => 'Desember',
			);
			$data['pelanggan'] = DB::table('tb_pelanggan')->whereRaw('deleted_at is null')->get();
			$this->cbView('pembayaran_add',$data);
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
			  date_default_timezone_set("Asia/Bangkok");
			  $postdata['tanggal'] = date('Y-m-d');
			  $postdata['faktur'] = 'FK'.date('dmY').'-'.$postdata['rekening'];
			  $postdata['admin'] = CRUDBooster::myName();
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
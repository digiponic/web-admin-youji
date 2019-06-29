<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminTbPembelianController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "tb_pembelian";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Tanggal","name"=>"tanggal","callback"=>function($row){
				return date('d-m-Y | H:i:s',strtotime($row->tanggal));
			}];
			$this->col[] = ["label"=>"Kode","name"=>"kode"];
			$this->col[] = ["label"=>"Supplier","name"=>"supplier","join"=>"tb_supplier,nama"];
			$this->col[] = ["label"=>"Status","name"=>"status", "join"=>"tb_general,keterangan"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			$kode = DB::table('tb_pembelian')->max('id') + 1;
			$kode = 'PMB/'.date('dmy').'/'.str_pad($kode, 5, 0, STR_PAD_LEFT);

			$tanggal = date('Y-m-d H:i:s');

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Kode','name'=>'kode','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>'true','value'=>$kode];
			$this->form[] = ['label'=>'Tanggal','name'=>'tanggal','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10','value'=>$tanggal];
			$this->form[] = ['label'=>'Supplier','name'=>'supplier','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'tb_supplier,nama','datatable_where'=>'deleted_at is null'];
			
			$columns[] = ['label'=>'Gudang','name'=>'gudang_keterangan','type'=>'text','readonly'=>true];
			$columns[] = ['label'=>'Produk','name'=>'id_produk','required'=>true,'type'=>'datamodal','datamodal_table'=>'tb_produk','datamodal_columns'=>'keterangan,stok_bahan,satuan_bahan_keterangan,gudang_keterangan','datamodal_columns_alias'=>'Produk,Stok Bahan,Satuan,Gudang','datamodal_select_to'=>'satuan_bahan_keterangan:satuan_bahan_keterangan,gudang_keterangan:gudang_keterangan','datamodal_size'=>'large','datamodal_where'=>'deleted_at is null'];
			$columns[] = ['label'=>'Kuantitas','name'=>'kuantitas','type'=>'number','required'=>true];
			$columns[] = ['label'=>'Satuan','name'=>'satuan_bahan_keterangan','type'=>'text','readonly'=>true];
			$columns[] = ['label'=>'Harga','name'=>'harga','type'=>'number','required'=>true,];
			$columns[] = ['label'=>'Tipe Diskon','name'=>'diskon_tipe','type'=>'radio','dataenum'=>'Nominal;Persen', 'value'=>'Nominal'];
			$columns[] = ['label'=>'Diskon','name'=>'diskon','type'=>'number'];
			$columns[] = ['label'=>'Sub Total','name'=>'subtotal','type'=>'number','formula'=>"[kuantitas] * [harga]","readonly"=>true];
			$columns[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'number',"readonly"=>true];
			$this->form[] = ['label'=>'Detil Pembelian','name'=>'pembelian_detail','type'=>'child','columns'=>$columns,'table'=>'tb_pembelian_detail','foreign_key'=>'id_pembelian'];

			$this->form[] = ['label'=>'Subtotal','name'=>'subtotal','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			$this->form[] = ['label'=>'Pajak (%)','name'=>'pajak','type'=>'number','width'=>'col-sm-10','value'=>'0'];
			$this->form[] = ['label'=>'Tipe Diskon','name'=>'diskon_tipe','type'=>'radio','dataenum'=>'Nominal;Persen', 'value'=>'Nominal'];
			$this->form[] = ['label'=>'Diskon','name'=>'diskon','type'=>'number','width'=>'col-sm-10','value'=>0];
			$this->form[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			$this->form[] = ['label'=>'Keterangan','name'=>'keterangan','type'=>'text','width'=>'col-sm-10'];
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
	        | @color 	   	= Default is primary. (primary, warning, succecss, info)
	        | @id 	   		= Id of action
	        | @title 	   	= Title of action
	        | @onclick 	   = OnClick JS of action
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        |
	        */
			$this->addaction = array();
			$this->addaction[] = ['title'=>'Pembelian Dibatalkan','icon'=>'fa fa-ban','color'=>'danger','url'=>CRUDBooster::mainpath('set-batal').'/[id]','showIf'=>'[status] == 39'];
			$this->addaction[] = ['title'=>'Pembelian Lunas','icon'=>'fa fa-check','color'=>'success','url'=>CRUDBooster::mainpath('set-terima').'/[id]','showIf'=>'[status] == 39'];
			// $this->addaction[] = ['title'=>'Pembelian Belum Lunas','icon'=>'fa fa-money','color'=>'warning','url'=>CRUDBooster::mainpath('set-belum-lunas').'/[id]','showIf'=>'[status] == 39'];
					

	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
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
			$this->index_statistic[] = ['label'=>'Pembelian dibatalkan','count' => DB::table('tb_pembelian')->where('status',40)->count(),'icon'=>'fa fa-ban','color'=>'primary'];
			$this->index_statistic[] = ['label'=>'Pembelian belum lunas','count' => DB::table('tb_pembelian')->where('status',39)->count(),'icon'=>'fa fa-close','color'=>'danger'];
			$this->index_statistic[] = ['label'=>'Pembelian sudah lunas','count' => DB::table('tb_pembelian')->where('status',28)->count(),'icon'=>'fa fa-check','color'=>'success'];
			$this->index_statistic[] = ['label'=>'Jumlah Pembelian','count' => DB::table('tb_pembelian')->count(),'icon'=>'fa fa-shopping-bag','color'=>'warning'];


	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
			$this->script_js = "

				function formatRupiah(angka, prefix){
					var number_string = angka.replace(/[^,\d]/g, '').toString(),
					split   		= number_string.split(','),
					sisa     		= split[0].length % 3,
					rupiah     		= split[0].substr(0, sisa),
					ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

					// tambahkan titik jika yang di input sudah menjadi angka ribuan
					if(ribuan){
						separator = sisa ? '.' : '';
						rupiah += separator + ribuan.join('.');
					}

					rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
					return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
				}

				function resetForm(){
					$('input[name=child-diskon_tipe][value=\"Nominal\"]').prop('checked',true);
					$('#detilpembelianharga, #detilpembeliankuantitas, #detilpembeliandiskon, #detilpembeliansubtotal').val(0);							
				}

				$(function(){
					resetForm();
					$('#btn-add-table-detilpembelian').click(function(){
						resetForm();
					});

					setInterval(function() {
						var harga = $('#detilpembelianharga').val();
						var diskon_tipe = $('input[name=diskon_tipe]:checked').val();
						var diskon_produk = $('#detilpembeliandiskon').val();
						var subtotal_produk = $('#detilpembeliansubtotal').val();
						var grand_total_produk = 0;

						if(diskon_tipe == 'Nominal'){
							grand_total_produk = subtotal_produk - diskon_produk;
						}else{
							var diskon_produk_ = (diskon_produk / 100) * subtotal_produk;
							grand_total_produk = subtotal_produk - diskon_produk_;
						}

						$('#detilpembeliangrand_total').val(grand_total_produk);
					
						var total = 0;
						$('#table-detilpembelian tbody .grand_total').each(function() {
							// var formated = formatRupiah($(this).text());
							// $(this).text(formated);

							// var original = ($(this).text()).replace('.','');
							// total += parseInt(original);
							total += parseInt($(this).text());
						});

						// $('#table-detilpembelian tbody tr').each(function() {
						// 	var harga = formatRupiah($(this).find('td').eq(2).text());
						// 	$(this).find('td').eq(3).text(harga);

						// 	var kuantitas = formatRupiah($(this).find('td').eq(3).text());
						// 	$(this).find('td').eq(4).text(kuantitas);

						// 	var subtotal = formatRupiah($(this).find('td').eq(7).text());
						// 	$(this).find('td').eq(7).text(subtotal);

						// 	var grand_total = formatRupiah($(this).find('td').eq(8).text());
						// 	$(this).find('td').eq(8).text(grand_total);

						// 	var grand_total_ = (grand_total).replace('.','');
						// 	if(grand_total_ == '')
						// 		grand_total_ = 0;
						// 	total += parseInt(grand_total_);
						// });

						$('#grand_total').val(total);
						var subtotal = 0;
						subtotal += total;
						$('#subtotal').val(subtotal); 

						var pajak = $('#pajak').val()
						var diskon_tipe = $('input[name=diskon_tipe]:checked').val();
						var diskon_keseluruhan = $('#diskon').val();
						var subtotal = 	$('#subtotal').val();
						var grand_total_keseluruhan = 0;

						if(diskon_tipe =='Nominal'){
							grand_total_keseluruhan = subtotal - diskon_keseluruhan;
						}else{
							var diskon_keseluruhan_ = (diskon_keseluruhan/100) * subtotal;
							grand_total_keseluruhan = subtotal - diskon_keseluruhan_;
							
						}	
						var pajak_ = (pajak/100) * subtotal;
						grand_total_keseluruhan_pajak = grand_total_keseluruhan + pajak_;		
						$('#grand_total').val(grand_total_keseluruhan_pajak);
					},500);	
				});					
			";


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
			if(!CRUDBooster::isSuperAdmin()){
				$query->where('id_cabang',CRUDBooster::myCabang());
				$query->where('users_id',CRUDBooster::myId());				
			}
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
			$postdata['status'] = 39;		
			$postdata['created_user'] = CRUDBooster::myName();				
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
			$pembelian = DB::table('tb_pembelian')->where('id',$id)->first();
			$pembelian_detail = DB::table('tb_pembelian_detail')->where('id_pembelian',$id)->get();

			foreach($pembelian_detail as $pd) {
				$produk = DB::table('tb_produk')->where('id',$pd->id_produk)->first();
				$array = array(
					'kode_pembelian'	=> $pembelian->kode,
					'kode_produk'		=> $produk->kode,
					'nama_produk'		=> $produk->keterangan,
					'satuan_bahan'		=> $produk->satuan_bahan,
					'tanggal'			=> $pembelian->tanggal
				);
				$produk_stok = array(
					'tanggal'			=> $pembelian->tanggal,
					'kode_produk'		=> $pd->id_produk,
					'stok_masuk'		=> $pd->kuantitas,
					'stok_keluar'		=> 0,
					'keterangan'		=> 'Penambahan stok dari pembelian '.$pembelian->kode,
					'created_user'		=> CRUDBooster::myName()
				);
				DB::table('tb_pembelian_detail')->where('id',$pd->id)->update($array);
				DB::table('tb_produk_stok_bahan')->insert($produk_stok);
				DB::table('tb_produk')->where('id',$pd->id_produk)->update(['stok_bahan'=> abs($produk->stok_bahan + $pd->kuantitas)]);
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
			$postdata['updated_user'] = CRUDBooster::myName();
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
			DB::table('tb_pembelian')->where('id',$id)->update(['deleted_user'=>CRUDBooster::myName()]);
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

		public function getSetBelumLunas($id)
		{
			DB::table('tb_pembelian')->where('id',$id)->update(['status' => 39]);
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Status pesanan belum lunas !","info");
		}

		public function getSetTerima($id)
		{
			DB::table('tb_pembelian')->where('id', $id)->update(['status'=> 28]);
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Status pesanan telah lunas !","info");
		}

		public function getSetBatal($id)
		{
			$pembelian = DB::table('tb_pembelian')->where('id',$id)->first();
			$pembelian_detail = DB::table('tb_pembelian_detail')->where('id_pembelian', $id)->get();

			foreach($pembelian_detail as $pd) {
				$produk = DB::table('tb_produk')->where('id',$pd->id_produk)->first();
				$produk_stok = array(
					'tanggal'			=> $pembelian->tanggal,
					'kode_produk'		=> $pd->id_produk,
					'stok_masuk'		=> 0,
					'stok_keluar'		=> $pd->kuantitas,
					'keterangan'		=> 'Pembatalan stok dari pembelian '.$pembelian->kode,
					'created_user'		=> CRUDBooster::myName()
				);
				DB::table('tb_produk_stok_bahan')->insert($produk_stok);
				DB::table('tb_produk')->where('id',$pd->id_produk)->update(['stok_bahan'=> abs($produk->stok_bahan - $pd->kuantitas)]);
			}
			DB::table('tb_pembelian')->where('id', $id)->update(['status'=> 40]);
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Status pesanan telah di Batalkan !","info");
		}
	    //By the way, you can still create your own method in here... :)
	}
<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	use Mike42\Escpos\Printer;
	use Mike42\Escpos\EscposImage;
	use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

	class AdminTbPenjualanController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_export = true;
			$this->table = "tb_penjualan";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Plt","name"=>"platform"];
			$this->col[] = ["label"=>"Kode","name"=>"kode"];
			$this->col[] = ["label"=>"Pengiriman","name"=>"tanggal","callback_php"=>'date("d-m-Y | H:i", strtotime($row->tanggal))','visible'=>false];			
			$this->col[] = ["label"=>"Tanggal","name"=>"created_at","callback"=>function($row){
				$str = '<p class="txt_pesan">Pesan : '.date("d-m-Y | H:i", strtotime($row->created_at)).'</p>';
				$str .= '<p class="txt_kirim">Kirim : '.date("d-m-Y | H:i", strtotime($row->tanggal)).'</p>';
				return $str;
			}];
			$this->col[] = ["label"=>"Pelanggan","name"=>"id_customer","join"=>"tb_customer,name"];
			$this->col[] = ["label"=>"Grand Total","name"=>"grand_total","callback_php"=>'"Rp ".number_format($row->grand_total,0,",",".")'];
			$this->col[] = ["label"=>"Status","name"=>"status","join"=>"tb_general,keterangan"];
			$this->col[] = ["label"=>"Catatan","name"=>"keterangan","callback"=>function($row){
				return (empty($row->keterangan) ? '-' : $row->keterangan);
			}];
			$this->col[] = ["label"=>"Kurir","name"=>"id_kurir",'join'=>'tb_kurir,keterangan'];
			# END COLUMNS DO NOT REMOVE THIS LINE

			$kode = DB::table('tb_penjualan')->max('id') + 1;
			$kode = 'PNJ/'.date('dmy').'/'.str_pad($kode, 5, 0, STR_PAD_LEFT);

			$tanggal = date('Y-m-d H:i:s');

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Kode','name'=>'kode','type'=>'text','width'=>'col-sm-5','readonly'=>'true','value'=>$kode];
			$this->form[] = ['label'=>'Pengiriman','name'=>'tanggal','type'=>'datetime','validation'=>'date_format:Y-m-d H:i:s','width'=>'col-sm-5','value'=>$tanggal];
			$this->form[] = ['label'=>'Pelanggan','name'=>'id_customer','type'=>'select2','validation'=>'required','width'=>'col-sm-5','datatable'=>'tb_customer,name'];
			$this->form[] = ['label'=>'Alamat','name'=>'alamat_pelanggan','type'=>'select','validation'=>'required','width'=>'col-sm-5','datatable'=>'tb_alamat_pelanggan,keterangan','parent_select'=>'id_customer'];
			$this->form[] = ['label'=>'Detail','name'=>'alamat_detail','type'=>'text','width'=>'col-sm-10','readonly'=>true];
			
			$columns[] = ['label'=>'Gudang','name'=>'gudang_keterangan','type'=>'text','readonly'=>true];
			$columns[] = ['label'=>'Produk','name'=>'id_produk','required'=>true,'type'=>'datamodal','datamodal_table'=>'tb_produk','datamodal_columns'=>'keterangan,harga_jual,stok_jual,satuan_jual_keterangan,gudang_keterangan','datamodal_columns_alias'=>'Produk,Harga,Stok,Satuan,Gudang','datamodal_select_to'=>'harga_jual:harga,satuan_jual_keterangan:satuan_keterangan,gudang_keterangan:gudang_keterangan','datamodal_where'=>'stok_jual > 0 and harga_jual > 0 and jenis > 7 and deleted_at is null','datamodal_size'=>'large'];
			$columns[] = ['label'=>'Harga','name'=>'harga','type'=>'number','required'=>true];
			$columns[] = ['label'=>'Satuan','name'=>'satuan_keterangan','type'=>'text','readonly'=>true];
			$columns[] = ['label'=>'Kuantitas','name'=>'kuantitas','type'=>'number','required'=>true];
			$columns[] = ['label'=>'Tipe Diskon','name'=>'diskon_tipe','type'=>'radio','dataenum'=>'Nominal;Persen'];
			$columns[] = ['label'=>'Diskon','name'=>'diskon','type'=>'number'];
			$columns[] = ['label'=>'Sub Total','name'=>'subtotal','type'=>'number','formula'=>"[kuantitas] * [harga]","readonly"=>true];
			$columns[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'number',"readonly"=>true];
			$this->form[] = ['label'=>'Detil Penjualan','name'=>'penjualan_detail','type'=>'child','columns'=>$columns,'table'=>'tb_penjualan_detail','foreign_key'=>'id_penjualan'];
			
			$this->form[] = ['label'=>'Subtotal','name'=>'subtotal','type'=>'money','validation'=>'required|integer|min:0','width'=>'col-sm-5','readonly'=>'true'];
			$this->form[] = ['label'=>'Pajak (%)','name'=>'pajak','type'=>'number','width'=>'col-sm-5','value'=>0];
			$this->form[] = ['label'=>'Ongkos Kirim','name'=>'ongkos_kirim','type'=>'text','width'=>'col-sm-5','readonly'=>true];
			$this->form[] = ['label'=>'Tipe Diskon','name'=>'diskon_tipe','type'=>'radio','width'=>'col-sm-5','dataenum'=>'Nominal;Persen','value'=>'Nominal'];
			$this->form[] = ['label'=>'Diskon','name'=>'diskon','type'=>'number','width'=>'col-sm-5','value'=>0];
			$this->form[] = ['label'=>'Metode Bayar','name'=>'metode_pembayaran','type'=>'radio','validation'=>'required','width'=>'col-sm-5','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 7','value'=>'33'];
			$this->form[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'money','validation'=>'integer|min:0','width'=>'col-sm-5','readonly'=>'true'];
			$this->form[] = ['label'=>'Catatan','name'=>'keterangan','type'=>'text','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Kode','name'=>'kode','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>'true','value'=>$kode];
			//$this->form[] = ['label'=>'Tanggal','name'=>'tanggal','type'=>'datetime','validation'=>'required|date_format:Y-m-d H:i:s','width'=>'col-sm-10','value'=>$tanggal];
			//$this->form[] = ['label'=>'Pelanggan','name'=>'customer_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'tb_customer,name'];
			//
			//$columns[] = ['label'=>'Produk','name'=>'id_produk','required'=>true,'type'=>'datamodal','datamodal_table'=>'tb_produk','datamodal_columns'=>'keterangan,harga','datamodal_select_to'=>'harga:harga','datamodal_where'=>'','datamodal_size'=>'large'];
			//$columns[] = ['label'=>'Harga','name'=>'harga','type'=>'number','required'=>true, 'readonly'=>true];
			//$columns[] = ['label'=>'Kuantitas','name'=>'kuantitas','type'=>'number','required'=>true];
			//$columns[] = ['label'=>'Tipe Diskon','name'=>'diskon_tipe','type'=>'radio','dataenum'=>'Nominal;Persen', 'value'=>'Nominal'];
			//$columns[] = ['label'=>'Diskon','name'=>'diskon','type'=>'number'];
			//$columns[] = ['label'=>'Sub Total','name'=>'subtotal','type'=>'number','formula'=>"[kuantitas] * [harga]","readonly"=>true];
			//$columns[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'number',"readonly"=>true];
			//$this->form[] = ['label'=>'Detil Penjualan','name'=>'penjualan_detail','type'=>'child','columns'=>$columns,'table'=>'tb_penjualan_detail','foreign_key'=>'id_penjualan'];
			//
			//$this->form[] = ['label'=>'Subtotal','name'=>'subtotal','type'=>'money', 'validation'=>'required|integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			//$this->form[] = ['label'=>'Pajak (%)','name'=>'pajak','type'=>'number','width'=>'col-sm-10','value'=>'0'];
			//$this->form[] = ['label'=>'Tipe Diskon','name'=>'diskon_tipe','type'=>'radio','dataenum'=>'Nominal;Persen', 'value'=>'Nominal'];
			//$this->form[] = ['label'=>'Diskon','name'=>'diskon','type'=>'number','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Grand Total','name'=>'grand_total','type'=>'money','validation'=>'integer|min:0','width'=>'col-sm-10','readonly'=>'true'];
			//$this->form[] = ['label'=>'Keterangan','name'=>'keterangan','type'=>'text','width'=>'col-sm-10'];
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
			// if(CRUDBooster::myPrivilegeId() != 4){
			$this->addaction[] = ['title'=>'Proses Pesanan','icon'=>'fa fa-refresh','color'=>'info','url'=>CRUDBooster::mainpath('set-proses').'/[id]','showIf'=>'[status] == 25'];
			$this->addaction[] = ['title'=>'Kirim Pesanan','icon'=>'fa fa-truck','color'=>'danger','url'=>CRUDBooster::mainpath('set-kirim').'/[id]','showIf'=>'[status] == 26'];
			$this->addaction[] = ['title'=>'Pesanan Lunas','icon'=>'fa fa-check','color'=>'success','url'=>CRUDBooster::mainpath('set-terima').'/[id]','showIf'=>'[status] == 27'];
			// $this->addaction[] = ['title'=>'Pesanan Lunas','icon'=>'fa fa-check','color'=>'success','url'=>CRUDBooster::mainpath('set-terima').'/[id]','showIf'=>'[status] == 28 || [status] == 39'];
			$this->addaction[] = ['title'=>'Pesanan Belum Lunas','icon'=>'fa fa-money','color'=>'danger','url'=>CRUDBooster::mainpath('set-belum-lunas').'/[id]','showIf'=>'[status] == 27'];
			$this->addaction[] = ['title'=>'Cetak Nota Pesanan','icon'=>'fa fa-print','color'=>'warning','url'=>CRUDBooster::mainpath('set-print').'/[id]','showIf'=>'[status] != 25'];
			// }


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
			$this->table_row_color[] = ['condition'=>"[platform] == 'mobile'","color"=>"success"];

	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
	        $this->index_statistic = array();
			// $this->index_statistic[] = ['label'=>'Penjualan Web','count' => DB::table('tb_penjualan')->where('platform','web')->count(),'icon'=>'fa fa-chrome','color'=>'primary'];
			// $this->index_statistic[] = ['label'=>'Penjualan Mobile','count' => DB::table('tb_penjualan')->where('platform','mobile')->count(),'icon'=>'fa fa-mobile','color'=>'success'];
			$this->index_statistic[] = ['label'=>'Pesanan belum diproses','count' => DB::table('tb_penjualan')->where('status',25)->count(),'icon'=>'fa fa-refresh','color'=>'primary'];
			$this->index_statistic[] = ['label'=>'Pesanan belum dikirim','count' => DB::table('tb_penjualan')->where('status',26)->count(),'icon'=>'fa fa-truck','color'=>'info'];
			$this->index_statistic[] = ['label'=>'Pesanan belum dibayar','count' => DB::table('tb_penjualan')->where('status',39)->count(),'icon'=>'fa fa-money','color'=>'danger'];
			$this->index_statistic[] = ['label'=>'Pesanan sudah dibayar','count' => DB::table('tb_penjualan')->where('status',28)->count(),'icon'=>'fa fa-check','color'=>'success'];


	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
			$this->script_js = "

				var _stokPrd = 0;
				setInterval(function(){
					var _id = $('#detilpenjualanid_produk .input-id').val();
					var _url = '".CRUDBooster::apiPath('produk')."';
					
					if(_id != null && _id != ''){
						$.ajax({
							method: 'GET',
							url: _url,
							data: {id: _id},
							success: function(res){
								console.log(res);
								_stokPrd = res[0].stok_jual;
							},
							error: function(err){
								console.log(err);
							}
						});
					}
				}, 1000);

				function resetForm(){
					$('input[name=child-diskon_tipe][value=\"Nominal\"]').prop('checked',true);
					$('#detilpenjualanharga, #detilpenjualankuantitas, #detilpenjualandiskon, #detilpenjualansubtotal').val(0);							
				}

				resetForm();				

				$('#alamat_pelanggan').change(function(){
					var _id = $(this).val();
					var _url = '".CRUDBooster::apiPath('pelanggan/alamat')."';

					if(_id != ''){
						$.ajax({
							url: _url,
							method: 'GET',
							data: {id: _id},
							success: function(res){
								console.log(res);
								$('#alamat_detail').val(res);
							},
							error: function(err){
								console.log(err);
							}
						});
					}
				});
				
				$('#detilpenjualankuantitas').on('keydown keyup', function(e){
					if ($(this).val() > _stokPrd 
						&& e.keyCode !== 46 // keycode for delete
						&& e.keyCode !== 8 // keycode for backspace
					   ) {
					   e.preventDefault();
					   $(this).val(_stokPrd);
					}
				});

				setInterval(function() {

					var harga = $('#detilpenjualanharga').val();
					var diskon_tipe_ = $('input[name=child-diskon_tipe]:checked').val();
					var diskon_produk = $('#detilpenjualandiskon').val();
					var subtotal_produk = $('#detilpenjualansubtotal').val();
					var grand_total_produk = 0;

					if(diskon_tipe_ == 'Nominal'){
						grand_total_produk = subtotal_produk - diskon_produk;
					}else{
						var diskon_produk_ = (diskon_produk / 100) * subtotal_produk;
						grand_total_produk = subtotal_produk - diskon_produk_;
					}

					$('#detilpenjualangrand_total').val(grand_total_produk);
				
					var total = 0;
					$('#table-detilpenjualan tbody .grand_total').each(function() {
						total += parseInt($(this).text());
					})
					$('#grand_total').val(total);

					var subtotal = 0;
					subtotal += total;
					$('#subtotal').val(subtotal); 
					
					var pajak = $('#pajak').val();
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
			
					var minimal_belanja = ".CRUDBooster::getSetting('minimal_belanja').";
					var ongkir = ".CRUDBooster::getSetting('ongkos_kirim').";
					var _ongkir = 0;
					if(subtotal >= minimal_belanja){
						$('#ongkos_kirim').val('GRATIS');
						_ongkir = 0;
					}else{
						$('#ongkos_kirim').val(ongkir);
						_ongkir = ongkir;
					}

					grand_total_keseluruhan_pajak += _ongkir;
					$('#grand_total').val(grand_total_keseluruhan_pajak);

				},500);

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
			$this->style_css = "
				.txt_pesan{ color: #27ae60;	}
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
			// $now = date('Y-m-d');
			// $query->whereDate('tb_penjualan.created_at', $now);
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
			//Your code here
			if($column_index==1){
				$class = ($column_value == 'mobile') ? 'success' : 'primary';
				$column_value = '<span class="label label-'.$class.'">'.$column_value.'</span>';
			}			
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
			$postdata['status'] = 25;
			//$postdata['id_cabang'] = CRUDBooster::myCabang();
			$postdata['created_user'] = CRUDBooster::myName();
			$postdata['platform'] = 'web';
			
			if($postdata['ongkos_kirim'] = 'GRATIS'){
				$postdata['ongkos_kirim'] = 0;
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
			$penjualan = DB::table('tb_penjualan')->where('id',$id)->first();
			$penjualan_detail = DB::table('tb_penjualan_detail')->where('id_penjualan',$id)->get();

			foreach($penjualan_detail as $pd) {
				$produk = DB::table('tb_produk')->where('id',$pd->id_produk)->first();
				$array = array(
					'kode_penjualan'	=> $penjualan->kode,
					'kode_produk'		=> $produk->kode,
					'nama_produk'		=> $produk->keterangan,
					'satuan'			=> $produk->satuan,
					'tanggal_pengiriman'=> $penjualan->tanggal
				);
				$produk_stok = array(
					'tanggal'		=> $penjualan->tanggal,
					'kode_produk'	=> $pd->id_produk,
					'stok_masuk'	=> 0,
					'stok_keluar'	=> $pd->kuantitas,
					'keterangan'	=> 'Pengurangan stok dari penjualan '.$penjualan->kode
				);

				DB::table('tb_penjualan_detail')->where('id',$pd->id)->update($array);
				DB::table('tb_produk_stok_jual')->insert($produk_stok);
				DB::table('tb_produk')->where('id',$pd->id_produk)->update(['stok_jual'=> abs($produk->stok - $pd->kuantitas)]);
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
			// dd($postdata);
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
		
		public function getSetProses($id)
		{
			DB::table('tb_penjualan')->where('id',$id)->update(['status' => 26]);
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Status pesanan berhasil di ubah !","info");
		}
		public function getSetKirim($id)
		{
			DB::table('tb_penjualan')->where('id',$id)->update(['status' => 27]);
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Status pesanan berhasil di ubah !","info");
		}
		public function getSetTerima($id)
		{
			DB::table('tb_penjualan')->where('id',$id)->update(['status' => 28]);
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Status pesanan berhasil di ubah !","info");
		}
		public function getSetBelumLunas($id)
		{
			DB::table('tb_penjualan')->where('id',$id)->update(['status' => 39]);
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Status pesanan berhasil di ubah !","info");
		}

		public function getSetPrint($id) {
			$penjualan = DB::table('tb_penjualan as pj')
							->join('tb_customer as cs','pj.id_customer','=','cs.id')
							->join('tb_general as gn','pj.metode_pembayaran','=','gn.id')
							->select('pj.*','cs.name as pelanggan','gn.keterangan as metode_pembayaran')
							->where('pj.id',$id)
							->first();

			if($penjualan->diskon_tipe == 'Persen'){
				$penjualan->diskon = ($penjualan->diskon / 100) * $penjualan->subtotal;
		}
			$penjualan_detail = DB::table('tb_penjualan_detail')->where('id_penjualan',$id)->get();

			$logo = EscposImage::load("youji_mini.jpg", false);

			try {
				$connector = new WindowsPrintConnector("GP-5830");
				$printer = new Printer($connector);
				
				$printer->setJustification(Printer::JUSTIFY_CENTER);
				$printer -> bitImage($logo);
				$printer -> text("\n");
				$printer -> selectPrintMode();
				$printer -> text("Jl. Terusan Yonkav No. 03\nSingosari - Malang Kab.\n");
				$printer -> feed();		
				
				$printer -> text(new format("Faktur",$penjualan->kode));
				
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				$tanggal = date('H:i d/m/y', strtotime($penjualan->created_at));
				$printer -> text(new format("Pesanan",$tanggal));

				$tanggal = date('H:i d/m/y', strtotime($penjualan->tanggal));
				$printer -> text(new format("Pengiriman",$tanggal));

				$printer -> text(new format("Pembayaran",$penjualan->metode_pembayaran));

				$printer -> text("--------------------------------");
				foreach ($penjualan_detail as $d) {
					$printer -> text($d->nama_produk."\n");

					if($d->diskon != 0){
						if($d->diskon_tipe == "Persen"){
							$d->diskon = ($d->diskon / 100) * $d->subtotal;
						}
						$printer -> text(new itemdiscount($d->kuantitas.' x '.$d->harga,$d->grand_total,$d->diskon));
					}else{
						$printer -> text(new item($d->kuantitas.' x '.$d->harga,$d->subtotal));
					}
				}
				$printer -> text("--------------------------------");

				// if(strlen($order->discount) < 3){
				// 	$order->discount = ($order->discount / 100) * $order->total;
				// }
				$printer -> text(new item("Sub Total",$penjualan->subtotal));
				$printer -> text(new item("Pajak",$penjualan->pajak));
				$printer -> text(new item("Diskon",$penjualan->diskon));
				$printer -> text(new item("Grand Total",$penjualan->grand_total));
				$printer -> feed();

				$printer -> setJustification(Printer::JUSTIFY_CENTER);
				$printer -> text("--------------------------------");
				$printer -> text($penjualan->pelanggan."\n");
				$printer -> text("Terima Kasih Atas Pesanan Anda\n");
				$printer -> text("Selamat Menikmati\n");
				$printer -> feed(2);

				$printer -> cut();
				$printer -> close();

				CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Struk telah berhasil dicetak !","info");
			} catch (Exception $e) {
				CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Gagal, printer error !!!","danger");
			}			
			
			CRUDBooster::redirect($_SERVER['HTTP_REFERER'],"Struk telah berhasil dicetak !","info");
		 }
	}

class item
{
	private $name;
	private $price;
	private $rupiah;
	public function __construct($name = '', $price = '', $rupiah = false)
	{
		$this->name = $name;
		$this->price = number_format($price,0,',','.');
		$this->rupiah = $rupiah;
	}
	
	public function __toString()
	{
		$rightCols = 8;
		$leftCols = 24;
		if ($this->rupiah) {
			$leftCols = $leftCols / 2 - $rightCols / 2;
		}
		$left = str_pad($this->name, $leftCols) ;
		$sign = ($this->rupiah ? 'Rp ' : '');
		$right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
		return "$left$right\n";
	}
}	

class itemdiscount
{
	private $name;
	private $price;
	private $discount;
	private $rupiah;
	public function __construct($name = '', $price = '', $discount = '', $rupiah = false)
	{
		$this->name = $name;
		$this->discount = number_format($discount,0,',','.');
		$this->price = number_format($price,0,',','.');
		$this->rupiah = $rupiah;
	}
	
	public function __toString()
	{
		$rightCols = 8;
		$middleCols = 9;
		$leftCols = 15;
		if ($this->rupiah) {
			$leftCols = $leftCols / 2 - $rightCols / 2;
		}
		$left = str_pad($this->name, $leftCols) ;
		$middle = str_pad($this->discount, $middleCols) ;
		$sign = ($this->rupiah ? 'Rp ' : '');
		$right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
		return "$left$middle$right\n";
	}
}	

class format
{
	private $name;
	private $text;
	public function __construct($name = '', $text = '')
	{
		$this->name = $name;
		$this->text = $text;
	}
	
	public function __toString()
	{
		$rightCols = 20;
		$leftCols = 12;
		
		$left = str_pad($this->name, $leftCols) ;
		$right = str_pad($this->text, $rightCols, ' ', STR_PAD_LEFT);
		return "$left$right\n";
	}
}	
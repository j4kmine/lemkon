<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}
	
	public function index(){
		$this->load->model("mst_senpi");
		$result['kondisi'] = $this->mst_senpi->getAllKondisi();
		$result['buku_pas']=$this->mst_senpi->getRekapBukuPas();
		$result['senpi'] = $this->mst_senpi->getSenpiMap();
		$this->load->view("header");
		$this->load->view("dashboardView", $result);
		$this->load->view("footer");
	}
	
	public function showKondisiSenpi(){
		$idMap = $this->input->post('idMap', TRUE);
		$this->load->model("mst_senpi");
		$values = $this->mst_senpi->getKondisiSenpi($idMap);
		
		$labelValues = array();
		$senpiBaik = array();
		$senpiHil = array();
		$senpiRb = array();
		$senpiRr = array();
		
		foreach ($values as $row)
		{
			//echo $row->Provinsi.' '.$row->kondisi.' '.$row->jml_kondisi.'</br>';
			array_push($labelValues, 
                  array(
                      "label" => $row->legend
                  )
            );
            
            array_push($senpiBaik, 
                  array(
                      "value" => $row->baik
                  )
            );
            
            array_push($senpiHil, 
                  array(
                      "value" => $row->hilang
                  )
            );
            
            array_push($senpiRb, 
                  array(
                      "value" => $row->rusak_berat
                  )
            );
            
            array_push($senpiRr, 
                  array(
                      "value" => $row->rusak_ringan
                  )
            );  
           
		}
		
		$chartConfig =array( "chart"=>
                            array( 
                                "caption" => "Kondisi Senpi",
                                "xAxisName" => "Provinsi",
                                "yAxisName" => "Jumlah Senpi", 
                                //"numberSuffix" => "K", 
                                "theme" => "fusion",
                                "formatnumberscale" => "1",
                                "drawcrossline" => "1"
                            ),
                            "categories"=>
                            	array(
                            		"category" => $labelValues
                            	),
                            "dataset"=>[
                            	array( "seriesname" => "Kondisi Baik",
                            		"data"=>$senpiBaik
	                            ),
	                            array( "seriesname" => "Hilang",
	                            	"data"=>$senpiHil
	                            ),
	                            array( "seriesname" => "Rusak Berat",
	                            	"data"=>$senpiRb
	                            ),
	                            array( "seriesname" => "Rusak Ringan",
	                            	"data"=>$senpiRr
	                            )	
	                            ]
                            );
		
		$chartData = json_encode($chartConfig);
				
		echo($chartData);
	}
	
	public function showKondisiSenpi2(){
		$idMap = $this->input->post('idMap', TRUE);
		$this->load->model("mst_senpi");
		$values = $this->mst_senpi->getKondisiSenpi($idMap);
		/*$label="";
		$data['baik'] = "";
		$data['hil'] = "";
		$data['rb'] = "";
		$data['rr'] = "";			*/
		$labelValues = array();
		$senpiBaik = array();
		$senpiHil = array();
		$senpiRb = array();
		$senpiRr = array();
		
		foreach ($values as $row)
		{
			//echo $row->Provinsi.' '.$row->kondisi.' '.$row->jml_kondisi.'</br>';
			array_push($labelValues, 
                  array(
                      "label" => $row->legend
                  )
            );
            
            array_push($senpiBaik, 
                  array(
                      "value" => $row->baik
                  )
            );
            
            array_push($senpiHil, 
                  array(
                      "value" => $row->hilang
                  )
            );
            
            array_push($senpiRb, 
                  array(
                      "value" => $row->rusak_berat
                  )
            );
            
            array_push($senpiRr, 
                  array(
                      "value" => $row->rusak_ringan
                  )
            );  
            
            /*
			$label .=  "{'label': "."'".$row->legend."'},";
			$data['baik'] .= "{'value': '".$row->baik."'},";
			$data['hil'] .= "{'value': '".$row->hilang."'},";
			$data['rb'] .= "{'value': '".$row->rusak_berat."'},";
			$data['rr'] .= "{'value': '".$row->rusak_ringan."'},";				
			*/
		}
		
		$chartConfig =array( "chart"=>
                            array( 
                                "caption" => "Kondisi Senpi",
                                "xAxisName" => "Provinsi",
                                "yAxisName" => "Jumlah Senpi", 
                                //"numberSuffix" => "K", 
                                "theme" => "fusion",
                                "formatnumberscale" => "1",
                                "drawcrossline" => "1"
                            ),
                            "categories"=>
                            	array(
                            		"category" => $labelValues
                            	),
                            "dataset"=>[
                            	array( "seriesname" => "Kondisi Baik",
                            		"data"=>$senpiBaik
	                            ),
	                            array( "seriesname" => "Hilang",
	                            	"data"=>$senpiHil
	                            ),
	                            array( "seriesname" => "Rusak Berat",
	                            	"data"=>$senpiRb
	                            ),
	                            array( "seriesname" => "Rusak Ringan",
	                            	"data"=>$senpiRr
	                            )	
	                            ]
                            );
		
		$chartData = json_encode($chartConfig);		
		echo($chartData);
	}
	
	private function pembersih($str){
		$pjgStr = strlen($str)-1;
		$str = substr($str, 0, $pjgStr);
		return $str;
	}
}
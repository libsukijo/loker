<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/core/BaseController.php';

class Laporan extends BaseController
{  
  

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('transaksi_model');
        $this->load->model('tamu_model');
        $this->load->library('excel');
        $this->isLoggedIn();
    }

    public function index()
    {
        $data['page'] = 'Laporan';
        $this->load->view('dashboard/laporan/laporan', $data);
    }

    public function getData()
    {
        $tanggal = $this->input->post('tanggal');
        $fakultas = $this->tamu_model->getFakultas();

        if (!empty($tanggal)) {
            $tanggal1 = explode("-", $tanggal);
            $start = date_create($tanggal1[0]);
            $start1 = date_format($start, "Y-m-d");
            $end = date_create($tanggal1[1]);
            $end1 = date_format($end, "Y-m-d");
            $total_semua=0;
            $jumPengunjung = array();
            foreach ($fakultas as $key => $fak) {
                $getData = $this->transaksi_model->getJumlahPengujungTanggal($fak['fakultas'], $start1, $end1);
                $jumPengunjung[$key]['fakultas'] = $fak['fakultas'];
                $jumPengunjung[$key]['jumlah'] = (int) $getData->jumlah;
                $total_semua += (int) $getData->jumlah;
            }

            $data['tanggal'] = $tanggal;
            $data['data_peminjam'] = $jumPengunjung;
            $data['total']=$total_semua;
            $this->load->view('dashboard/laporan/laporan_tanggal', $data);
        } else {
            die('tes');
        }

    }

    function export_excel()
    {  
        
        // Read an Excel File
        $tmpfname = "example.xls";
        $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $objPHPExcel = $excelReader->load($tmpfname);

           /* Data */


        // Create a first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2')->setCellValue('A2','LAPORAN PEMINJAMAN LOKER');
        $objPHPExcel->getActiveSheet()->setCellValue('A4', "No");
        $objPHPExcel->getActiveSheet()->setCellValue('B4', "Fakultas");
        $objPHPExcel->getActiveSheet()->setCellValue('C4', "Jumlah");

        // Hide F and G column
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setVisible(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setVisible(false);

        // Set auto size
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        
        $tanggal=$this->input->get('tanggal');
        $data=$this->transaksi_model->getDataPengunjungExcel($tanggal);
        //var_dump($data);die();
        $no_urut=1;
        $no = 4;
        foreach ($data as $d) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $no, $no_urut)->setCellValue('B' . $no, $d->fakultas)
                ->setCellValue('C' . $no, $d->jumlah);
                $no_urut++;
                $no++;
        }
      

        // Set Font Color, Font Style and Font Alignment
        $stil = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000')
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        // $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($stil);

        // Save Excel xls File
        $filename = "Laporan_Pengunjung.xls";
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename);
        $objWriter->save('php://output');
    }
}

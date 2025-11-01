<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('laporan_model');
        $this->load->model('tamu_model');
        $this->load->library('excel');
    }


    public function index()
    {
        $data['fakultas'] = $this->tamu_model->getFakultas();
        $data['page'] = 'Laporan';

        $this->load->view('dashboard/laporan/laporan', $data);
    }

    public function ajax_list()
    {
        $list = $this->laporan_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $kunci) {
            $no++;
            $row = array();
            $row[] = $kunci->nama;
            $row[] = $kunci->no_loker;
            $row[] = $kunci->fakultas;
            $row[] = $kunci->tgl_pinjam;
            //add html for action
            $row[] = '';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->laporan_model->count_all(),
            "recordsFiltered" => $this->laporan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    function export_excel()
    {

        // Read an Excel File
        $tmpfname = "example.xls";
        $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $objPHPExcel = $excelReader->load($tmpfname);

        // Create a first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "ID");
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Nama");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "Fakultas");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "No Loker");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "Tanggal Pinjam");

        // Hide F and G column
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setVisible(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setVisible(false);

        // Set auto size
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

        /* Data */
        $postData = $this->input->get();
        $data = $this->laporan_model->getData($postData);

        $total = count($data);
        $no = 2;
        foreach ($data as $d) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $no, $d->nim)
                ->setCellValue('B' . $no, $d->nama)
                ->setCellValue('C' . $no, $d->fakultas)
                ->setCellValue('D' . $no, $d->no_loker)
                ->setCellValue('E' . $no, $d->tgl_pinjam);
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

/* End of file Controllername.php */

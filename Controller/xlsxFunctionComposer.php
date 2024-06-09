<?php
require_once '../bdConnection.php'; 
require_once '../Controller/standardFunctionsController.php'; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function fetchDataFromDatabase($pdo) {
    $sql = "SELECT
                cli.nome,
                cli.email,
                cli.dtnasc,
                cli.fone,
                cli.cpf,
                cli.rua,
                cli.complemento,
                cli.ncasa,
                cli.cep,
                cli.tipo,
                cli.ativo,
                cid.nomecidade
            FROM tb_clientes cli
            LEFT JOIN tb_cidades cid ON cli.codcid = cid.codcid";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generateXLSX($data, $filename = 'exportacao_dados.xlsx') {
    require '../vendor/autoload.php';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $header = ['Nome', 'E-mail', 'Data de nascimento', 'Contato', 'CPF', 'Rua', 'Complemento', 'Nº Casa', 'CEP', 'Tipo', 'Status', 'Cidade'];
    $sheet->fromArray([$header], null, 'A1');

    $row = 2;
    foreach ($data as $r) {
        $sheet->setCellValue('A' . $row, htmlspecialchars($r['nome']));
        $sheet->setCellValue('B' . $row, htmlspecialchars($r['email']));
        $sheet->setCellValue('C' . $row, formatDateToBrazilian($r['dtnasc'])); 
        $sheet->setCellValue('D' . $row, formatPhoneNumber($r['fone']));
        $sheet->setCellValue('E' . $row, formatCPF($r['cpf']));
        $sheet->setCellValue('F' . $row, htmlspecialchars($r['rua']));
        $sheet->setCellValue('G' . $row, htmlspecialchars($r['complemento']));
        $sheet->setCellValue('H' . $row, htmlspecialchars($r['ncasa']));
        $sheet->setCellValue('I' . $row, formatCEP($r['cep']));
        $sheet->setCellValue('J' . $row, htmlspecialchars($r['tipo'] == 'C' ? 'Cliente' : 'Administrador'));
        $sheet->setCellValue('K' . $row, htmlspecialchars($r['ativo'] == 'S' ? 'Ativo' : 'Inativo'));
        $sheet->setCellValue('L' . $row, htmlspecialchars($r['nomecidade']));
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $filePath = __DIR__ . '/' . $filename;
    $writer->save($filePath);

    return $filePath;
}

$pdo = conectar();

$data = fetchDataFromDatabase($pdo);

$filePath = generateXLSX($data);
echo "Arquivo XLSX gerado: $filePath";
?>

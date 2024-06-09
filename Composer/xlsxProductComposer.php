<?php
require_once '../bdConnection.php'; 
require_once '../Controller/standardFunctionsController.php'; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function fetchDataFromDatabase($pdo, $filters) {
    $sql = "SELECT 
                prod.codproduto,
                prod.nomeproduto,
                prod.precoproduto,
                prod.qtdprod,
                prod.ativo AS ativoproduct,
                cat.nomecategoria,
                img.img
            FROM tb_produtos prod
            INNER JOIN tb_categorias cat 
                ON prod.codcategoria = cat.codcategoria
            LEFT JOIN tb_imagens img 
                ON prod.codproduto = img.codproduto
            WHERE 1=1";

    $params = [];
    if (!empty($filters['nomeproduto'])) {
        $sql .= " AND prod.nomeproduto LIKE :nomeproduto";
        $params[':nomeproduto'] = '%' . $filters['nomeproduto'] . '%';
    }
    if (!empty($filters['precoproduto'])) {
        $sql .= " AND prod.precoproduto LIKE :precoproduto";
        $params[':precoproduto'] = '%' . $filters['precoproduto'] . '%';
    }
    if (!empty($filters['qtdprod'])) {
        $sql .= " AND prod.qtdprod LIKE :qtdprod";
        $params[':qtdprod'] = '%' . $filters['qtdprod'] . '%';
    }
    if (!empty($filters['nomecategoria'])) {
        $sql .= " AND cat.nomecategoria LIKE :nomecategoria";
        $params[':nomecategoria'] = '%' . $filters['nomecategoria'] . '%';
    }
    if (!empty($filters['ativoproduct'])) {
        $sql .= " AND prod.ativoproduct LIKE :ativoproduct";
        $params[':ativoproduct'] = '%' . $filters['ativoproduct'] . '%';
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generateXLSX($data, $filename = 'produtos.xlsx') {
    require '../vendor/autoload.php';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $header = ['Produto', 'Preço', 'Quantidade', 'Categoria', 'Status'];
    $sheet->fromArray([$header], null, 'A1');

    $row = 2;
    foreach ($data as $r) {
        $sheet->setCellValue('A' . $row, htmlspecialchars($r['nomeproduto']));
        $sheet->setCellValue('B' . $row, formatPrice($r['precoproduto']));
        $sheet->setCellValue('C' . $row, htmlspecialchars($r['qtdprod'])); 
        $sheet->setCellValue('D' . $row, htmlspecialchars($r['nomecategoria']));
        $sheet->setCellValue('E' . $row, htmlspecialchars($r['ativoproduct'] == 'S' ? 'Ativo' : 'Inativo'));
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $filePath = __DIR__ . '/' . $filename;
    $writer->save($filePath);

    return $filePath;
}

$pdo = conectar();

$filters = [
    'nomeproduto'   => $_GET['nomeproduto'] ?? '',
    'precoproduto'  => $_GET['precoproduto'] ?? '',
    'qtdprod'       => $_GET['qtdprod'] ?? '',
    'nomecategoria' => $_GET['nomecategoria'] ?? '',
    'ativoproduct'  => $_GET['ativoproduct'] ?? ''
];

$data = fetchDataFromDatabase($pdo, $filters);

$filePath = generateXLSX($data);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="produtos.xlsx"');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);

unlink($filePath);
?>
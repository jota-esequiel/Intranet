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
                prod.ativo AS ativoproduct,
                cat.nomecategoria,
                CASE prod.cor
                    WHEN '1' THEN 'Vermelho'
                    WHEN '2' THEN 'Azul'
                    WHEN '3' THEN 'Amarelo'
                    ELSE 'Desconhecido'
                END AS corProd,
                CASE prod.tamanho
                    WHEN 'P' THEN 'Pequeno'
                    WHEN 'M' THEN 'Médio'
                    WHEN 'G' THEN 'Grande'
                    ELSE 'Desconhecido'
                END AS tamanhoProd,
                img.img
            FROM tb_produtos prod
            INNER JOIN tb_categorias cat 
                ON prod.codcategoria = cat.codcategoria
            LEFT JOIN tb_imagens img 
                ON prod.codimg = img.codimg
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
    if (!empty($_POST['cor'])) {
        $sql .= " AND prod.cor = :cor";
        $params[':cor'] = $_POST['cor'];
    }
    if (!empty($_POST['tamanho'])) {
        $sql .= " AND prod.tamanho = :tamanho";
        $params[':tamanho'] = $_POST['tamanho'];
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

    $header = ['Produto', 'Preço', 'Categoria', 'Status', 'Cor', 'Tamanho'];
    $sheet->fromArray([$header], null, 'A1');

    $row = 2;
    foreach ($data as $r) {
        $sheet->setCellValue('A' . $row, htmlspecialchars($r['nomeproduto']));
        $sheet->setCellValue('B' . $row, formatarPrice($r['precoproduto']));
        $sheet->setCellValue('C' . $row, htmlspecialchars($r['nomecategoria'])); 
        $sheet->setCellValue('D' . $row, htmlspecialchars($r['ativoproduct'] == 'S' ? 'Ativo' : 'Inativo'));
        $sheet->setCellValue('E' . $row, htmlspecialchars($r['corProd']));
        $sheet->setCellValue('F' . $row, htmlspecialchars($r['tamanhoProd']));

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
    'nomecategoria' => $_GET['nomecategoria'] ?? '',
    'ativoproduct'  => $_GET['ativoproduct'] ?? '',
    'corProd'       => $_GET['corProd'] ?? '',
    'tamanhoProd'   => $_GET['tamanhoProd'] ?? '',
];

$data = fetchDataFromDatabase($pdo, $filters);

$filePath = generateXLSX($data);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="produtos.xlsx"');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);

unlink($filePath);
?>
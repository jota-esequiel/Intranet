<?php
require_once '../bdConnection.php'; 
require_once '../Controller/standardFunctionsController.php'; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function fetchDataFromDatabase($pdo, $filters) {
    $sql = "SELECT
                cli.*,
                cid.nomecidade
            FROM tb_clientes cli
            LEFT JOIN tb_cidades cid
                ON cli.codcid = cid.codcid
            WHERE 1=1";

    $params = [];
    if (!empty($filters['nome'])) {
        $sql .= " AND cli.nome LIKE :nome";
        $params[':nome'] = '%' . $filters['nome'] . '%';
    }
    if (!empty($filters['email'])) {
        $sql .= " AND cli.email LIKE :email";
        $params[':email'] = '%' . $filters['email'] . '%';
    }
    if (!empty($filters['cpf'])) {
        $sql .= " AND cli.cpf LIKE :cpf";
        $params[':cpf'] = '%' . $filters['cpf'] . '%';
    }
    if (!empty($filters['fone'])) {
        $sql .= " AND cli.fone LIKE :fone";
        $params[':fone'] = '%' . $filters['fone'] . '%';
    }
    if (!empty($filters['dtnasc'])) {
        $sql .= " AND cli.dtnasc LIKE :dtnasc";
        $params[':dtnasc'] = '%' . $filters['dtnasc'] . '%';
    }
    if (!empty($filters['rua'])) {
        $sql .= " AND cli.rua LIKE :rua";
        $params[':rua'] = '%' . $filters['rua'] . '%';
    }
    if (!empty($filters['complemento'])) {
        $sql .= " AND cli.complemento LIKE :complemento";
        $params[':complemento'] = '%' . $filters['complemento'] . '%';
    }
    if (!empty($filters['ncasa'])) {
        $sql .= " AND cli.ncasa LIKE :ncasa";
        $params[':ncasa'] = '%' . $filters['ncasa'] . '%';
    }
    if (!empty($filters['cep'])) {
        $sql .= " AND cli.cep LIKE :cep";
        $params[':cep'] = '%' . $filters['cep'] . '%';
    }
    if (!empty($filters['ativo'])) {
        $sql .= " AND cli.ativo LIKE :ativo";
        $params[':ativo'] = '%' . $filters['ativo'] . '%';
    }
    if (!empty($filters['tipo'])) {
        $sql .= " AND cli.tipo LIKE :tipo";
        $params[':tipo'] = '%' . $filters['tipo'] . '%';
    }

    if (!empty($filters['cidade'])) {
        $sql .= " AND cli.codcid = :cidade";
        $params[':cidade'] = $filters['cidade'];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generateXLSX($data, $filename = 'usuários.xlsx') {
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

$filters = [
    'nome'        => $_GET['nome'] ?? '',
    'email'       => $_GET['email'] ?? '',
    'cpf'         => $_GET['cpf'] ?? '',
    'fone'        => $_GET['fone'] ?? '',
    'dtnasc'      => $_GET['dtnasc'] ?? '',
    'rua'         => $_GET['rua'] ?? '',
    'complemento' => $_GET['complemento'] ?? '',
    'ncasa'       => $_GET['ncasa'] ?? '',
    'cep'         => $_GET['cep'] ?? '',
    'ativo'       => $_GET['ativo'] ?? '',
    'tipo'        => $_GET['tipo'] ?? '',
    'cidade'      => $_GET['cidade'] ?? ''
];

$data = fetchDataFromDatabase($pdo, $filters);

$filePath = generateXLSX($data);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="usuários.xlsx"');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);

unlink($filePath);
?>

<?php

$clientes = [];
const CHEQUE_ESPECIAL = 500;

function cadastrarCliente() {
    global $clientes;
    
    $nome = trim(readline("Digite o nome do cliente: "));
    $cpf = trim(readline("Digite o CPF do cliente: "));
    
    foreach ($clientes as $cliente) {
        if ($cliente['cpf'] === $cpf) {
            echo "CPF já cadastrado!\n";
            return;
        }
    }
    
    $clientes[] = [
        'nome' => $nome,
        'cpf' => $cpf,
        'contas' => []
    ];
    
    echo "Cliente cadastrado com sucesso!\n";
}

function criarConta() {
    global $clientes;
    
    if (count($clientes) === 0) {
        echo "Nenhum cliente cadastrado.\n";
        return;
    }
    
    listarClientes();
    $indice = (int)trim(readline("Selecione o cliente (número): ")) - 1;
    
    if (!isset($clientes[$indice])) {
        echo "Cliente inválido!\n";
        return;
    }
    
    $numeroConta = rand(10000, 99999);
    $clientes[$indice]['contas'][] = [
        'numero' => $numeroConta,
        'saldo' => 0,
        'extrato' => []
    ];
    
    echo "Conta número $numeroConta criada com sucesso!\n";
}

function operacoesContaBancaria() {
    global $clientes;
    
    if (count($clientes) === 0) {
        echo "Nenhum cliente cadastrado.\n";
        return;
    }
    
    listarClientes();
    $indiceCliente = (int)trim(readline("Selecione o cliente (número): ")) - 1;
    
    if (!isset($clientes[$indiceCliente])) {
        echo "Cliente inválido!\n";
        return;
    }
    
    $cliente = &$clientes[$indiceCliente];
    
    if (count($cliente['contas']) === 0) {
        echo "Este cliente não possui contas.\n";
        return;
    }
    
    echo "\nContas de {$cliente['nome']}:\n";
    foreach ($cliente['contas'] as $i => $conta) {
        echo ($i + 1) . ". Conta: {$conta['numero']} | Saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
    }
    
    $indiceConta = (int)trim(readline("Selecione a conta (número): ")) - 1;
    
    if (!isset($cliente['contas'][$indiceConta])) {
        echo "Conta inválida!\n";
        return;
    }
    
    $conta = &$cliente['contas'][$indiceConta];
    
    while (true) {
        echo "\n=== Operações Bancárias ===\n";
        echo "1. Depositar\n";
        echo "2. Sacar\n";
        echo "3. Consultar Saldo\n";
        echo "4. Visualizar Extrato\n";
        echo "5. Voltar\n";
        $opcao = trim(readline("Escolha uma opção: "));
        
        switch ($opcao) {
            case '1':
                depositar($conta, $cliente['nome']);
                break;
            case '2':
                sacar($conta, $cliente['nome']);
                break;
            case '3':
                consultarSaldo($conta);
                break;
            case '4':
                visualizarExtrato($conta);
                break;
            case '5':
                return;
            default:
                echo "Opção inválida!\n";
        }
    }
}

function depositar(&$conta, $nomeCliente) {
    $valor = (float)trim(readline("Digite o valor a depositar: R$ "));
    
    if ($valor <= 0) {
        echo "Valores negativos ou zero não são permitidos!\n";
        return;
    }
    
    $conta['saldo'] += $valor;
    $data = date('d/m/Y H:i:s');
    $conta['extrato'][] = "Depósito de R$ " . number_format($valor, 2, ',', '.') . " em $data";
    
    echo "Depósito realizado com sucesso! Novo saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
}

function sacar(&$conta, $nomeCliente) {
    $valor = (float)trim(readline("Digite o valor a sacar: R$ "));
    
    if ($valor <= 0) {
        echo "Valores negativos ou zero não são permitidos!\n";
        return;
    }
    
    $limiteDisponivel = $conta['saldo'] + CHEQUE_ESPECIAL;
    
    if ($valor > $limiteDisponivel) {
        echo "Saque não permitido! Saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') . 
             " | Limite especial: R$ " . number_format(CHEQUE_ESPECIAL, 2, ',', '.') . "\n";
        return;
    }
    
    $conta['saldo'] -= $valor;
    $data = date('d/m/Y H:i:s');
    $conta['extrato'][] = "Saque de R$ " . number_format($valor, 2, ',', '.') . " em $data";
    
    echo "Saque realizado com sucesso! Novo saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
}

function consultarSaldo($conta) {
    echo "\nSaldo da conta: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
    echo "Limite especial disponível: R$ " . number_format(CHEQUE_ESPECIAL, 2, ',', '.') . "\n";
}

function visualizarExtrato($conta) {
    echo "\n=== Extrato da Conta ===\n";
    
    if (count($conta['extrato']) === 0) {
        echo "Nenhuma transação realizada.\n";
        return;
    }
    
    foreach ($conta['extrato'] as $transacao) {
        echo "- $transacao\n";
    }
}

function listarClientes() {
    global $clientes;
    
    if (count($clientes) === 0) {
        echo "Nenhum cliente cadastrado.\n";
        return;
    }
    
    echo "\nClientes cadastrados:\n";
    foreach ($clientes as $index => $cliente) {
        echo ($index + 1) . ". Nome: " . $cliente['nome'] . " | CPF: " . $cliente['cpf'] . "\n";
    }
}

function exibirMenu() {
    echo "\n=== Agência Bancária ===\n";
    echo "1. Cadastrar Cliente\n";
    echo "2. Criar Conta\n";
    echo "3. Operações Bancárias\n";
    echo "4. Listar Clientes\n";
    echo "5. Sair\n";
    echo "Escolha uma opção: ";
}

while (true) {
    exibirMenu();
    $opcao = trim(fgets(STDIN));
    
    switch ($opcao) {
        case '1':
            cadastrarCliente();
            system('clear');
            break;
        case '2':
            criarConta();
            system('clear');
            break;
        case '3':
            operacoesContaBancaria();
            system('clear');
            break;
        case '4':
            listarClientes();
            break;
        case '5':
            echo "Saindo do programa. Até mais!\n";
            system('clear');
            exit;
        default:
            echo "Opção inválida. Tente novamente.\n";
    }
}

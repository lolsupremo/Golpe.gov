<?php

$clientes = [];
const CHEQUE_ESPECIAL = 500;

function cadastrarCliente()
{
    global $clientes;

    $nome = trim(readline("Digite o nome da vítima: "));
    $cpf = trim(readline("Digite o CPF da vítima: "));

    foreach ($clientes as $cliente) {
        if ($cliente['cpf'] === $cpf) {
            print "CPF já cadastrado!\n";
            return;
        }
    }

    $clientes[] = [
        'nome' => $nome,
        'cpf' => $cpf,
        'contas' => []
    ];

    print "Vítima cadastrada com sucesso!\n";
}

function criarConta()
{
    global $clientes;

    if (count($clientes) === 0) {
        print "Nenhuma vítima cadastrada.\n";
        return;
    }

    listarClientes();
    do {
        $continuar = false;
        $indice = (int)trim(readline("Selecione a vítima (número): ")) - 1;

        if (!isset($clientes[$indice])) {
            print "Vítima inválida!\n";
            $continuar = true;
        }
    } while ($continuar);

    do {
        print("Escolha o banco para a conta: \n");
        print("1. Banco da esquina\n");
        print("2. Banco dos golpistas\n");
        print("3. Banco da outra rua\n");
        print("4. Banco do Estevão\n");
        $bancoOpcao = trim(readline("Digite o número do banco: "));

        switch ($bancoOpcao) {
            case '1':
                $banco = "Banco da esquina";
                $continuar = false;
                break;
            case '2':
                $banco = "Banco dos golpistas";
                $continuar = false;
                break;
            case '3':
                $banco = "Banco da outra rua";
                $continuar = false;
                break;
            case '4':
                $banco = "Banco do Estevão";
                $continuar = false;
                break;
            default:
                print "Banco inválido!\n";
                $continuar = true;
                break;
        }
    } while ($continuar);

    $numeroConta = rand(10000, 99999);
    $clientes[$indice]['contas'][] = [
        'numero' => $numeroConta,
        'saldo' => 0,
        'Banco da conta' => $banco,
        'cheque_especial' => CHEQUE_ESPECIAL,
        'extrato' => []
    ];

    print "Conta número $numeroConta criada com sucesso no $banco!\n";
    sleep(3);
}

function operacoesContaBancaria()
{
    global $clientes;

    if (count($clientes) === 0) {
        print "Nenhuma vítima cadastrada.\n";
        return;
    }

    listarClientes();
    $indiceCliente = (int)trim(readline("Selecione a vítima (número): ")) - 1;

    if (!isset($clientes[$indiceCliente])) {
        print "Vítima inválida!\n";
        return;
    }

    $cliente = &$clientes[$indiceCliente];

    if (count($cliente['contas']) === 0) {
        print "Esta vítima não possui contas.\n";
        return;
    }

    print "\nContas de {$cliente['nome']}:\n";
    foreach ($cliente['contas'] as $i => $conta) {
        print ($i + 1) . ". Conta: {$conta['numero']} | Banco: {$conta['Banco da conta']} | Saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
    }

    $indiceConta = (int)trim(readline("Selecione a conta (número): ")) - 1;

    if (!isset($cliente['contas'][$indiceConta])) {
        print "Conta inválida!\n";
        return;
    }

    $conta = &$cliente['contas'][$indiceConta];

    while (true) {
        print "\n\033[31m                                                                                                       
                                      ▄▀▄▀                                                             
▄████▄ ▄▄▄▄  ▄▄▄▄▄ ▄▄▄▄   ▄▄▄   ▄▄▄▄  ▄▄▄  ▄▄▄▄▄  ▄▄▄▄   ▄▄▄▄  ▄▄▄▄▄    ▄████   ▄▄▄  ▄▄    ▄▄▄▄  ▄▄▄▄▄ 
██  ██ ██▄█▀ ██▄▄  ██▄█▄ ██▀██ ██▀▀▀ ██▀██ ██▄▄  ███▄▄   ██▀██ ██▄▄    ██  ▄▄▄ ██▀██ ██    ██▄█▀ ██▄▄  
▀████▀ ██    ██▄▄▄ ██ ██ ██▀██ ▀████ ▀███▀ ██▄▄▄ ▄▄██▀   ████▀ ██▄▄▄    ▀███▀  ▀███▀ ██▄▄▄ ██    ██▄▄▄ 
                                 ▄█                                                                    \n";
        print "1. Depositar\n";
        print "2. Sacar\n";
        print "3. Consultar Saldo\n";
        print "4. Visualizar Extrato\n";
        print "5. Voltar\033[0m\n";
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
                print "Opção inválida!\n";
        }
    }
}

function depositar(&$conta, $nomeCliente)
{
    $valor = (float)trim(readline("Digite o valor a depositar: R$ "));

    if ($valor <= 0) {
        print "Valores negativos ou zero não são permitidos!\n";
        return;
    }

    $conta['saldo'] += $valor;
    $data = date('d/m/YYYY H-3:i:s');
    $conta['extrato'][] = "Depósito de R$ " . number_format($valor, 2, ',', '.') . " em $data";

    print "Depósito realizado com sucesso! Novo saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
}

function sacar(&$conta, $nomeCliente)
{
    $valor = (float)trim(readline("Digite o valor a sacar: R$ "));

    if ($valor <= 0) {
        print "Valores negativos ou zero não são permitidos!\n";
        return;
    }

    $limiteDisponivel = $conta['saldo'] + CHEQUE_ESPECIAL;

    if ($valor > $limiteDisponivel) {
        print "Saque não permitido! Saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') .
            " | Limite especial: R$ " . number_format(CHEQUE_ESPECIAL, 2, ',', '.') . "\n";
        return;
    }

    $conta['saldo'] -= $valor;
    $data = date('d/m/Y H:i:s');
    $conta['extrato'][] = "Saque de R$ " . number_format($valor, 2, ',', '.') . " em $data";

    print "Saque realizado com sucesso! Novo saldo: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
}

function consultarSaldo($conta)
{
    print "\nSaldo da conta: R$ " . number_format($conta['saldo'], 2, ',', '.') . "\n";
    print "Limite especial disponível: R$ " . number_format(CHEQUE_ESPECIAL, 2, ',', '.') . "\n";
}

function visualizarExtrato($conta)
{
    print "\n\n\033[31m                                                                                          
██████ ▄▄ ▄▄ ▄▄▄▄▄▄ ▄▄▄▄   ▄▄▄ ▄▄▄▄▄▄ ▄▄▄    ▄▄▄▄   ▄▄▄    ▄█████  ▄▄▄  ▄▄  ▄▄ ▄▄▄▄▄▄ ▄▄▄  
██▄▄   ▀█▄█▀   ██   ██▄█▄ ██▀██  ██  ██▀██   ██▀██ ██▀██   ██     ██▀██ ███▄██   ██  ██▀██ 
██▄▄▄▄ ██ ██   ██   ██ ██ ██▀██  ██  ▀███▀   ████▀ ██▀██   ▀█████ ▀███▀ ██ ▀██   ██  ██▀██ 
                                                                                          \n\033[0m \n";

    if (count($conta['extrato']) === 0) {
        print "Nenhuma transação realizada.\n";
        return;
    }

    foreach ($conta['extrato'] as $transacao) {
        print "- $transacao\n";
    }
}

function listarClientes()
{
    global $clientes;

    $admin = trim(readline("Digite a senha de administrador: \n (dica: Melhor Turma de 2025) \n"));

    if ($admin === "1TDS") {


        if (count($clientes) === 0) {
            print "Nenhum cliente cadastrado.\n";
            return;
        }

        print "\nClientes cadastrados:\n";
        foreach ($clientes as $index => $cliente) {
            print ($index + 1) . ". Nome: " . $cliente['nome'] . " | CPF: " . $cliente['cpf'] . "\n";
        }
    } else {
        print "Senha incorreta. Acesso negado.\n";
    }
}

function exibirMenu()
{
    print "\n\033[34m                                                 
 ▄████   ▄▄▄  ▄▄    ▄▄▄▄  ▄▄▄▄▄        ▄▄▄▄  ▄▄▄  ▄▄ ▄▄ 
██  ▄▄▄ ██▀██ ██    ██▄█▀ ██▄▄        ██ ▄▄ ██▀██ ██▄██ 
 ▀███▀  ▀███▀ ██▄▄▄ ██    ██▄▄▄   ▄   ▀███▀ ▀███▀  ▀█▀  
                                                        \n";
    print "\033[33m1. Cadastrar Vitima\n";
    print "2. Criar Conta\n";
    print "3. Operações de Golpe\n";
    print "4. Listar Vitimas\n";
    print "5. Sair\n";
    print "Escolha uma opção: \033[0m";
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
            print "Saindo do programa. Até mais!\n";
            system('clear');
            exit;
        default:
            print "Opção inválida. Tente novamente.\n";
    }
}

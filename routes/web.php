<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['role:gerente']], function () {
    Route::prefix('gerente')->group(function () {

        // SEARCH
        Route::post('/leads/search', [App\Http\Controllers\Gerente\GerenteController::class, 'search']);

        // INDEX
        Route::get('/', [App\Http\Controllers\Gerente\GerenteController::class, 'index'])->name('gerente.index');
    });
});

Route::group(['middleware' => ['role:usuario_consulta']], function () {
    Route::prefix('usuario_consulta')->group(function () {

        // RELATORIO
        Route::get('/', [App\Http\Controllers\UsuarioConsulta\UsuarioConsultaController::class, 'index'])->name('usuario_consulta.index');
    });
});

Route::group(['middleware' => ['role:user']], function () {
    Route::prefix('user')->group(function () {

        // CONEXAO
        Route::get('/conexao', [App\Http\Controllers\Admin\ConexaoController::class, 'index']);

        // PROSPECTS SEARCH
        Route::post('/prospects/search', [App\Http\Controllers\Admin\ProspectsController::class, 'search']);

        // CAMPANHAS SEARCH
        Route::post('/campanhas/search', [App\Http\Controllers\User\CampanhasController::class, 'search']);

        // CAMPANHA UPATE STATUS
        Route::get('/campanhas', [App\Http\Controllers\User\CampanhasController::class, 'index']);

        // CAMPANHA UPATE STATUS
        Route::post('/campanha/update_status', [App\Http\Controllers\User\CampanhasController::class, 'update_status']);

        // CAMPANHAS
        Route::post('/campanha/store', [App\Http\Controllers\User\CampanhasController::class, 'store']);

        // CAMPANHAS
        Route::post('/campanha/delete', [App\Http\Controllers\User\CampanhasController::class, 'delete']);

        // BUSCAR LEADS
        Route::post('/leads/search_lista', [App\Http\Controllers\User\LeadsController::class, 'search_lista']);

        // CONTATOS PRIMEIRA ABERTURA
        Route::post('/contato/primeira_abertura', [App\Http\Controllers\User\ContatoController::class, 'primeira_abertura']);


        // SOLICITAR LISTAS
        Route::get('/listas', [App\Http\Controllers\User\SolicitacaoListaController::class, 'index']);

        // SOLICITAR LISTAS
        Route::post('/listas/store', [App\Http\Controllers\User\SolicitacaoListaController::class, 'store']);

        // ADICIONAR OBSERVAÇÕES
        Route::post('/contato/recuperar_nao_interessados', [App\Http\Controllers\User\ContatoController::class, 'recuperarRejeitado']);

        // REJEITADOS
        Route::get('/nao_interessados', [App\Http\Controllers\User\ContatoController::class, 'naoInteressados']);

        // ADICIONAR OBSERVAÇÕES
        Route::post('/contato/add-justificativa', [App\Http\Controllers\User\ContatoController::class, 'adicionarJustificativa']);

        // ADICIONAR OBSERVAÇÕES
        Route::post('/contato/update', [App\Http\Controllers\User\ContatoController::class, 'update']);

        // ADICIONAR OBSERVAÇÕES
        Route::post('/contato/add-observacoes', [App\Http\Controllers\User\ContatoController::class, 'adicionarObservacao']);

        // PARCELAS CONFIRMAR
        Route::post('/parcela/update', [App\Http\Controllers\User\ParcelaController::class, 'update']);

        // PARCELAS STORE
        Route::post('/parcelas/store', [App\Http\Controllers\User\ParcelaController::class, 'storeLista']);

        // PROPOSTA EMPREGADOS
        Route::post('/proposta/update-dependentes', [App\Http\Controllers\User\PropostaController::class, 'updateDependentes']);

        // PROPOSTA REENVIAR
        Route::post('/proposta/update-empregados', [App\Http\Controllers\User\PropostaController::class, 'updateEmpregados']);

        // PROPOSTA REENVIAR
        Route::post('/proposta/reenviar', [App\Http\Controllers\User\PropostaController::class, 'reenviar']);

        // PROPOSTA STORE
        Route::post('/proposta/store', [App\Http\Controllers\User\PropostaController::class, 'store']);

        // ENCAMINHAR LEAD
        Route::post('/proposta/new', [App\Http\Controllers\User\PropostaController::class, 'new']);

        // ENCAMINHAR LEAD
        Route::post('/contato/update-status', [App\Http\Controllers\User\ContatoController::class, 'updateStatus']);

        // RELATORIO
        Route::post('/leads/conectado/update', [App\Http\Controllers\User\LeadsController::class, 'atualizarContactado']);

        // RELATORIO
        Route::post('/leads/status/update', [App\Http\Controllers\User\LeadsController::class, 'atualizarStatus']);

        // RELATORIO
        Route::get('/relatorio', [App\Http\Controllers\User\RelatorioController::class, 'index']);

        // LEAD AVALIAR
        Route::post('/leads/avaliar', [App\Http\Controllers\User\LeadsController::class, 'avaliar']);

        // LEAD CREATE
        Route::post('/leads/store', [App\Http\Controllers\User\LeadsController::class, 'store']);

        // TAREFA LEAD SEARCH
        Route::post('/tarefa-lead/search', [App\Http\Controllers\User\TarefaLeadController::class, 'search']);

        // TAREFA LEAD STORE
        Route::post('/tarefa-lead/store', [App\Http\Controllers\User\TarefaLeadController::class, 'store']);

        // TAREFA LEAD DELETE
        Route::post('/tarefa-lead/delete', [App\Http\Controllers\User\TarefaLeadController::class, 'delete']);

        // TAREFA 
        Route::get('/tarefa-lead', [App\Http\Controllers\User\TarefaLeadController::class, 'index']);

        // TAREFA 
        Route::post('/tarefa-lead/salvar-anotacoes', [App\Http\Controllers\User\TarefaLeadController::class, 'salvar_anotacoes']);

        // TAREFAS SEARCH
        Route::post('/tarefas/search', [App\Http\Controllers\User\TarefasController::class, 'search']);

        // INDEX
        Route::get('/', [App\Http\Controllers\User\UserController::class, 'index'])->name('user.index');
    });
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::prefix('admin')->group(function () {

        // CAMPANHAS SEARCH
        Route::post('/campanhas/search', [App\Http\Controllers\Admin\CampanhasController::class, 'search']);

        // CAMPANHAS UPDATE
        Route::post('/campanha/update', [App\Http\Controllers\Admin\CampanhasController::class, 'update']);

        // CAMPANHAS
        Route::get('/campanhas', [App\Http\Controllers\Admin\CampanhasController::class, 'index']);

        // ORIGENS
        Route::post('/origens/search', [App\Http\Controllers\Admin\OrigemController::class, 'search']);

        // USER ORIGENS
        Route::post('/user-origens/add', [App\Http\Controllers\Admin\UserOrigemController::class, 'add']);

        // USER ORIGENS
        Route::post('/user-origens/delete', [App\Http\Controllers\Admin\UserOrigemController::class, 'delete']);

        // USER ORIGENS
        Route::post('/user-origens/search', [App\Http\Controllers\Admin\UserOrigemController::class, 'search']);


        // CONTATOS SEARCH
        Route::post('/contatos/search', [App\Http\Controllers\Admin\ContatoController::class, 'search']);


        // TAREFAS
        Route::get('/tarefas', [App\Http\Controllers\Admin\TarefasController::class, 'index']);

        // TAREFAS SEARCH
        Route::post('/tarefas/search', [App\Http\Controllers\Admin\TarefasController::class, 'search']);

        // TAREFAS STORE
        Route::post('/tarefa/store', [App\Http\Controllers\Admin\TarefasController::class, 'store']);

        // TAREFAS UPDATE
        Route::post('/tarefa/update', [App\Http\Controllers\Admin\TarefasController::class, 'searupdatech']);

        // TAREFAS DELETE
        Route::post('/tarefa/delete', [App\Http\Controllers\Admin\TarefasController::class, 'delete']);


        // TIPO PRODUTO SEARCH
        Route::post('/tipo-produto/search', [App\Http\Controllers\Admin\TipoProdutoController::class, 'search']);

        // TIPO PRODUTO UPDATE
        Route::post('/tipo-produto/update', [App\Http\Controllers\Admin\TipoProdutoController::class, 'update']);

        // TIPO PRODUTO DELETE
        Route::post('/tipo-produto/delete', [App\Http\Controllers\Admin\TipoProdutoController::class, 'delete']);

        // TIPO PRODUTO STORE
        Route::post('/tipo-produto/store', [App\Http\Controllers\Admin\TipoProdutoController::class, 'store']);

        // TIPO PRODUTO
        Route::get('/tipo-produto', [App\Http\Controllers\Admin\TipoProdutoController::class, 'index']);

        // PROPOSTAS RELATÓRIO SEARCH
        Route::post('/proposta-relatorio/search', [App\Http\Controllers\Admin\PropostaRelatorioController::class, 'search']);

        // PROPOSTAS RELATÓRIO UPDATE
        Route::post('/proposta-relatorio/update', [App\Http\Controllers\Admin\PropostaRelatorioController::class, 'update']);

        // PROPOSTAS RELATÓRIO DELETE
        Route::post('/proposta-relatorio/delete', [App\Http\Controllers\Admin\PropostaRelatorioController::class, 'delete']);

        // PROPOSTAS RELATÓRIO STORE
        Route::post('/proposta-relatorio/store', [App\Http\Controllers\Admin\PropostaRelatorioController::class, 'store']);

        // PROPOSTAS
        Route::get('/propostas', [App\Http\Controllers\Admin\PropostaRelatorioController::class, 'index']);

        // STATUS PROPOSTA STORE
        Route::post('/proposta-status/store', [App\Http\Controllers\Admin\StatusPropostaController::class, 'store']);

        // STATUS PROPOSTA  UPDATE
        Route::post('/proposta-status/update', [App\Http\Controllers\Admin\StatusPropostaController::class, 'update']);

        // STATUS PROPOSTA  DELETE
        Route::post('/proposta-status/delete', [App\Http\Controllers\Admin\StatusPropostaController::class, 'delete']);

        // STATUS PROPOSTA 
        Route::get('/proposta-status', [App\Http\Controllers\Admin\StatusPropostaController::class, 'index']);

        // PRODUTOS SEARCH
        Route::post('/produto/search', [App\Http\Controllers\Admin\ProdutoController::class, 'search']);

        // PRODUTOS STORE
        Route::post('/produto/store', [App\Http\Controllers\Admin\ProdutoController::class, 'store']);

        // PRODUTOS UPDATE
        Route::post('/produto/update', [App\Http\Controllers\Admin\ProdutoController::class, 'update']);

        // PRODUTOS DELETE
        Route::post('/produto/delete', [App\Http\Controllers\Admin\ProdutoController::class, 'delete']);

        // PRODUTOS
        Route::get('/produtos', [App\Http\Controllers\Admin\ProdutoController::class, 'index']);

        // OPERADORA STORE
        Route::post('/operadora/store', [App\Http\Controllers\Admin\OperadoraController::class, 'store']);

        // OPERADORA UPDATE
        Route::post('/operadora/update', [App\Http\Controllers\Admin\OperadoraController::class, 'update']);

        // OPERADORA DELETE
        Route::post('/operadora/delete', [App\Http\Controllers\Admin\OperadoraController::class, 'delete']);

        // OPERADORA
        Route::get('/operadoras', [App\Http\Controllers\Admin\OperadoraController::class, 'index']);

        // ADMINISTRADORA UPDATE
        Route::post('/administradora/update', [App\Http\Controllers\Admin\AdministradoraController::class, 'update']);

        // ADMINISTRADORA DELETE
        Route::post('/administradora/delete', [App\Http\Controllers\Admin\AdministradoraController::class, 'delete']);

        // ADMINISTRADORA STORE
        Route::post('/administradora/store', [App\Http\Controllers\Admin\AdministradoraController::class, 'store']);

        // ADMINISTRADORAS
        Route::get('/administradoras', [App\Http\Controllers\Admin\AdministradoraController::class, 'index']);

        // REJEITAR SOLICITAÇÕES
        Route::post('/solicitacoes/reject', [App\Http\Controllers\Admin\SolicitacoesController::class, 'reject']);

        // SOLICITAÇÕES - ENVIAR 
        Route::post('/solicitacoes/send', [App\Http\Controllers\Admin\SolicitacoesController::class, 'send']);

        // LISTAR SOLICITAÇÕES
        Route::get('/solicitacoes', [App\Http\Controllers\Admin\ListasController::class, 'solicitacoes']);

        // RELATORIO KANBAN SEARCH
        Route::post('/relatorio/kanban/search', [App\Http\Controllers\Admin\RelatorioKanbanController::class, 'search']);

        // RELATORIO KANBAN
        Route::get('/relatorio/kanban', [App\Http\Controllers\Admin\RelatorioKanbanController::class, 'index']);

        // RELATORIO FINANCEIRO PARCELAS A RECEBER
        Route::post('/parcelas/find-all', [App\Http\Controllers\Admin\ParcelaController::class, 'findAll']);

        // RELATORIO FINANCEIRO PARCELAS A RECEBER
        Route::post('/parcelas/update', [App\Http\Controllers\Admin\ParcelaController::class, 'update']);

        // RELATORIO FINANCEIRO PARCELAS A RECEBER
        Route::post('/parcelas/search/status', [App\Http\Controllers\Admin\FinanceiroController::class, 'searchStatus']);

        // RELATORIO FINANCEIRO PARCELAS A RECEBER
        Route::get('/parcelas/a_receber', [App\Http\Controllers\Admin\FinanceiroController::class, 'receber']);

        // RELATORIO FINANCEIRO PARCELAS A RECEBER
        Route::get('/parcelas/recebido', [App\Http\Controllers\Admin\FinanceiroController::class, 'recebido']);

        // PARCELAS STORE
        Route::post('/parcelas/store', [App\Http\Controllers\Admin\ParcelaController::class, 'storeLista']);

        // IMPORTAR LISTAS
        Route::post('/listas/store', [App\Http\Controllers\Admin\ListaController::class, 'store']);

        // LISTAS
        Route::get('/listas/importar', [App\Http\Controllers\Admin\ListaController::class, 'importar']);

        // LISTAS
        Route::get('/listas', [App\Http\Controllers\Admin\ListasController::class, 'index']);

        // NOVO - CRIAR LISTA
        Route::post('/lista/store', [App\Http\Controllers\Admin\ListasController::class, 'store']);

        // ADICIONAR OBSERVAÇÕES
        Route::post('/contato/add-observacoes', [App\Http\Controllers\Admin\ContatoController::class, 'adicionarObservacao']);

        // ATUALIZAR CONTATO
        Route::post('/contato/update-status', [App\Http\Controllers\Admin\ContatoController::class, 'updateStatus']);

        // PLANO DELETE
        Route::post('/plano/delete', [App\Http\Controllers\Admin\PlanoController::class, 'delete']);

        // PLANO UPDATE
        Route::post('/plano/update', [App\Http\Controllers\Admin\PlanoController::class, 'update']);

        // PLANO STORE
        Route::post('/plano/store', [App\Http\Controllers\Admin\PlanoController::class, 'store']);

        // TURNOS
        Route::get('/planos', [App\Http\Controllers\Admin\PlanoController::class, 'index']);

        // IMPORTAR LISTAS
        Route::post('/proposta/add-pendencias', [App\Http\Controllers\Admin\PropostaController::class, 'adicionarPendencias']);

        // IMPORTAR LISTAS
        Route::post('/proposta/update', [App\Http\Controllers\Admin\PropostaController::class, 'update']);

        // PROPOSTAS CADASTRAR
        Route::post('/proposta/cadastrar', [App\Http\Controllers\Admin\PropostaController::class, 'cadastrar']);

        // PROPOSTAS CADASTRADAS
        Route::get('/propostas/cadastradas', [App\Http\Controllers\Admin\PropostaController::class, 'cadastradas']);

        // PROPOSTAS A CADASTRAR
        Route::get('/propostas/pendente', [App\Http\Controllers\Admin\PropostaController::class, 'index']);

        // FINANCEIRO SEARCH
        Route::post('/financeiro/search', [App\Http\Controllers\Admin\FinanceiroController::class, 'search']);

        // FINANCEIRO
        Route::get('/financeiro/{status}', [App\Http\Controllers\Admin\FinanceiroController::class, 'index']);

        // LEAD CREATE
        Route::post('/relatorio/encaminhadas/search', [App\Http\Controllers\Admin\RelatorioController::class, 'relatorioEncaminhadasSearch']);

        // TURNOS
        Route::get('/encaminhadas', [App\Http\Controllers\Admin\RelatorioController::class, 'relatorioEncaminhadas']);

        // LEAD CREATE
        Route::post('/leads/store', [App\Http\Controllers\Admin\LeadsController::class, 'store']);

        // RELATÓRIO
        Route::get('/relatorio/origens', [App\Http\Controllers\Admin\RelatorioOrigensController::class, 'index']);

        // RELATÓRIO
        Route::post('/relatorio/origens/search', [App\Http\Controllers\Admin\RelatorioOrigensController::class, 'search']);

        // CORRETORES SEARCH
        Route::post('/corretores/search', [App\Http\Controllers\Admin\CorretoresController::class, 'search']);

        // USER TURNO - UPDATE DADOS NO TURNO
        Route::post('/user/update_dados_turno', [App\Http\Controllers\Admin\UsersController::class, 'updateDadosTurno']);

        // TURNO - BUSCAR CORRETORES 
        Route::post('/turnos/search_corretores', [App\Http\Controllers\Admin\TurnosController::class, 'searchCorretoresTurno']);

        // TURNO UPDATE
        Route::post('/turnos/update', [App\Http\Controllers\Admin\TurnosController::class, 'update']);

        // TURNO UPDATE STATUS
        Route::post('/turnos/update_status', [App\Http\Controllers\Admin\TurnosController::class, 'updateStatus']);

        // TURNO SEARCH
        Route::post('/turnos/search', [App\Http\Controllers\Admin\TurnosController::class, 'search']);

        // TURNO - BUSCAR CORRETORES 
        Route::post('/turnos/search_corretores', [App\Http\Controllers\Admin\TurnosController::class, 'searchCorretoresTurno']);

        // DELET TURNO
        Route::post('/turnos/delete', [App\Http\Controllers\Admin\TurnosController::class, 'delete']);

        // TURNOS
        Route::post('/turnos/user/delete', [App\Http\Controllers\Admin\TurnosController::class, 'removerUsuario']);

        // TURNOS
        Route::post('/turnos/ordem/update', [App\Http\Controllers\Admin\TurnosController::class, 'atualizarOrdem']);

        // TURNOS
        Route::post('/turnos/add', [App\Http\Controllers\Admin\TurnosController::class, 'addTurno']);

        // TURNOS
        Route::post('/turnos/store', [App\Http\Controllers\Admin\TurnosController::class, 'store']);

        // TURNOS
        Route::get('/turnos', [App\Http\Controllers\Admin\TurnosController::class, 'index'])->name('gerente.index');

        // ORIGENS
        Route::get('/origens', [App\Http\Controllers\Admin\OrigemController::class, 'index']);

        // ORIGENS
        Route::post('/origem/delete', [App\Http\Controllers\Admin\OrigemController::class, 'delete']);

        // ORIGENS
        Route::post('/origem/update', [App\Http\Controllers\Admin\OrigemController::class, 'update']);

        // ORIGENS
        Route::post('/origem/store', [App\Http\Controllers\Admin\OrigemController::class, 'store']);

        // RELATÓRIO
        Route::post('/ranking/search', [App\Http\Controllers\Admin\RelatorioController::class, 'searchRanking']);

        // RANKING
        Route::get('/ranking', [App\Http\Controllers\Admin\RelatorioController::class, 'ranking']);

        // RELATÓRIO
        Route::post('/relatorio/search', [App\Http\Controllers\Admin\RelatorioController::class, 'search']);

        // RELATÓRIO
        Route::get('/relatorio', [App\Http\Controllers\Admin\RelatorioController::class, 'index']);

        // ENCAMINHAR LEAD
        Route::post('/leads/encaminhar', [App\Http\Controllers\Admin\LeadsController::class, 'encaminhar']);

        // RELATÓRIO POR LIDER
        Route::post('/users/search-by-role', [App\Http\Controllers\Admin\UsersController::class, 'searchByRole']);

        // UPDATE USERS
        Route::post('/users/update', [App\Http\Controllers\Admin\UsersController::class, 'update']);

        // CREATE USERS
        Route::post('/users/create', [App\Http\Controllers\Admin\UsersController::class, 'store']);

        // ONLY CREATE USERS
        Route::post('/users/delete', [App\Http\Controllers\Admin\UsersController::class, 'delete']);

        // ONLY CREATE USERS
        Route::get('/users/new-create', [App\Http\Controllers\Admin\UsersController::class, 'newcreate']);

        // INDEX
        Route::get('/users/list', [App\Http\Controllers\Admin\UsersController::class, 'list'])->name('admin.users.list');

        // INDEX
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');
    });
});


Route::group(['middleware' => ['auth']], function () {

    // ENDEREÇO - BUSCAR CIDADES POR ESTADO
    Route::post('/endereco/cidades_por_estado', [App\Http\Controllers\EnderecoController::class, 'buscarCidadesPorEstado']);

    // ENDEREÇO - BUSCAR ESTADO POR NOME
    Route::post('/endereco/estado_por_nome', [App\Http\Controllers\EnderecoController::class, 'buscarEstadoPorNome']);

    // ENDEREÇO - BUSCAR CIDADE POR NOME
    Route::post('/endereco/cidade_por_nome', [App\Http\Controllers\EnderecoController::class, 'buscarCidadePorNome']);


    Route::post('/user/tarefa/concluir', [App\Http\Controllers\User\TarefaLeadController::class, 'concluir']);


    // CONTATOS SEARCH
    Route::post('/user/contatos/search', [App\Http\Controllers\User\ContatoController::class, 'search']);
});

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::get('/teste', function () {
    return view('teste');
});

Route::get('/list', function () {
    return view('list');
});

Route::get('/backoffice', function () {
    return view('new');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/corretor/{telefone}', [App\Http\Controllers\CorretorController::class, 'index'])->name('corretor.controller');
Route::post('/corretor/avaliar', [App\Http\Controllers\CorretorController::class, 'avaliar']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('/senha', [App\Http\Controllers\VerificaTrocaSenhaController::class, 'index'])->name('senha');
    Route::post('/updatesenha', [App\Http\Controllers\VerificaTrocaSenhaController::class, 'updatesenha']);
});

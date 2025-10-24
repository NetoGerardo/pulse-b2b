require('./bootstrap');

import { createApp } from "vue";
import router from "./router";
import VueSweetalert2 from "vue-sweetalert2";
import 'sweetalert2/dist/sweetalert2.min.css';
import VueClipboard from 'vue-clipboard2'

//USER
import UserIndex from "./components/user/Index.vue";
import CreateLead from "./components/user/CreateLead.vue";
import Relatorio from "./components/user/Relatorio.vue";
import Rejeitados from "./components/user/Rejeitados.vue";

//ADMIN
import CreateUser from "./components/admin/CreateUser.vue";
import AdminListUsers from "./components/admin/ListUsers.vue";
import ListarLeads from "./components/admin/ListarLeads.vue";
import AdminRelatorio from "./components/admin/Relatorio.vue";
import AdminRelatorioOrigens from "./components/admin/RelatorioOrigens.vue";
import AdminRanking from "./components/admin/Ranking.vue";
import ListarTurnos from "./components/turnos/Listar.vue";
import CriarTurnos from "./components/turnos/Criar.vue";
import AdminCreateLead from "./components/admin/CreateLead.vue";
import RelatorioEncaminhadas from "./components/admin/RelatorioEncaminhadas.vue";
import RelatorioAdminKanban from "./components/relatorio/RelatorioAdminKanban.vue";

//LISTAS
import ListarListas from "./components/listas/Listar.vue";
import NovaLista from "./components/listas/NovaLista.vue";

//GERENTE 
import IndexGerente from "./components/gerente/Index.vue";

//ORIGENS
import NovaOrigem from "./components/origem/NovaOrigem.vue";
import ListarOrigens from "./components/origem/ListarOrigens.vue";

//USUÁRIO CONSULTA
import UsuarioConsultaListarLeads from "./components/usuario-consulta/ListarLeads.vue";

//CORRETOR INDEX
import CorretorIndex from "./components/corretor/Index.vue";

//PROPOSTA
import NovaProposta from "./components/proposta/NovaProposta.vue";
import NovaPropostaButton from "./components/proposta/NovaPropostaButton.vue";
import PropostasPendentes from "./components/proposta/Pendentes.vue";
import PropostasCadastradas from "./components/proposta/Cadastradas.vue";
import KanbanAdmin from "./components/proposta/KanbanAdmin.vue";
import AnalisarProposta from "./components/proposta/AnalisarProposta.vue";
import CorrigirPendencias from "./components/proposta/CorrigirPendencias.vue";

//IMPORTAR LISTAS
import ImportarLista from "./components/listas/ImportarLista.vue";
import SolicitacoesLista from "./components/corretor/SolicitacoesLista.vue";
import SolicitacoesListasAdmin from "./components/admin/SolicitacoesListas.vue";

//PLANO
import NovoPlano from "./components/plano/NovoPlano.vue";
import ListarPlanos from "./components/plano/ListarPlanos.vue";

//FINANCEIRO
import CalendarioFinanceiro from "./components/financeiro/CalendarioFinanceiro.vue";
import CalendarioFinanceiroReceber from "./components/financeiro/CalendarioFinanceiroReceber.vue";
import CalendarioFinanceiroRecebido from "./components/financeiro/CalendarioFinanceiroRecebido.vue";
import DetalhesProposta from "./components/financeiro/DetalhesProposta.vue";

//PROPOSTAS RELATORIO
import ListarPropostasRelatorio from "./components/sistema-simplificado/ListarPropostasRelatorio.vue";
import CadastrarPropostasRelatorio from "./components/sistema-simplificado/CadastrarPropostasRelatorio.vue";

//SISTEMA SIMPLIFICADO
import AdminAdministradoras from "./components/sistema-simplificado/ListarAdministradora.vue";
import AdminCadastrarAdministradora from "./components/sistema-simplificado/CadastrarAdministradora.vue";

import AdminOperadoras from "./components/sistema-simplificado/ListarOperadora.vue";
import AdminCadastrarOperadora from "./components/sistema-simplificado/CadastrarOperadora.vue";

import AdminProdutos from "./components/sistema-simplificado/ListarProduto.vue";
import AdminCadastrarProduto from "./components/sistema-simplificado/CadastrarProduto.vue";

import AdminStatusProposta from "./components/sistema-simplificado/ListarStatusProposta.vue";
import AdminCadastrarStatusProposta from "./components/sistema-simplificado/CadastrarStatusProposta.vue";

import AdminTipoProduto from "./components/sistema-simplificado/ListarTipoProduto.vue";
import AdminCadastrarTipoProduto from "./components/sistema-simplificado/CadastrarTipoProduto.vue";


//TAREFAS
import AdminListarTarefas from "./components/admin/tarefas/Listar.vue";
import AdminCadastrarTarefa from "./components/admin/tarefas/Cadastrar.vue";
import AdminEditarTarefa from "./components/admin/tarefas/Editar.vue";

import UserTarefasContato from "./components/user/tarefas-contato/Listar.vue";

import DadosCardLead from "./components/corretor/DadosCardLead.vue";

//ADMIN
import EditUser from "./components/admin//users/Edit.vue";

import axios from 'axios';
import VueAxios from 'vue-axios'

//OPÇÃO 3
const vue = createApp({
    components: {}
})

// Vuetify
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components,
    directives,
})

vue.use(vuetify)

vue.use(VueAxios, axios)
vue.use(VueClipboard)
vue.use(VueSweetalert2);
vue.use(router)


vue.component('admin-edit-user', EditUser)

vue.component('user-tarefas-contato', UserTarefasContato)

vue.component('admin-cadastrar-tarefa', AdminCadastrarTarefa)
vue.component('admin-editar-tarefa', AdminEditarTarefa)
vue.component('admin-listar-tarefas', AdminListarTarefas)

vue.component('dados-card-lead', DadosCardLead)
vue.component('admin-tipo-produto', AdminTipoProduto)
vue.component('admin-cadastrar-tipo-produto', AdminCadastrarTipoProduto)
vue.component('admin-status-proposta', AdminStatusProposta)
vue.component('admin-cadastrar-status-proposta', AdminCadastrarStatusProposta)
vue.component('admin-produtos', AdminProdutos)
vue.component('admin-cadastrar-produto', AdminCadastrarProduto)
vue.component('admin-operadoras', AdminOperadoras)
vue.component('admin-cadastrar-operadoras', AdminCadastrarOperadora)
vue.component('admin-administradoras', AdminAdministradoras)
vue.component('admin-cadastrar-administradoras', AdminCadastrarAdministradora)
vue.component('user-cadastrar-proposta-relatorio', CadastrarPropostasRelatorio)
vue.component('user-propostas-relatorio', ListarPropostasRelatorio)
vue.component('admin-listas', SolicitacoesListasAdmin)
vue.component('admin-importar-lista', ImportarLista)
vue.component('solicitacoes-lista', SolicitacoesLista)
vue.component('nova-lista', NovaLista)
vue.component('listar-listas', ListarListas)
vue.component('user-index', UserIndex)
vue.component('user-create-lead', CreateLead)
vue.component('user-relatorio', Relatorio)
vue.component('admin-create-lead', AdminCreateLead)
vue.component('admin-create-user', CreateUser)
vue.component('admin-list-users', AdminListUsers)
vue.component('admin-list-leads', ListarLeads)
vue.component('admin-relatorio', AdminRelatorio)
vue.component('admin-ranking', AdminRanking)
vue.component('index-gerente', IndexGerente)
vue.component('admin-list-turnos', ListarTurnos)
vue.component('admin-create-turnos', CriarTurnos)
vue.component('usuario-consulta-listar-leads-dia', UsuarioConsultaListarLeads)
vue.component('nova-origem', NovaOrigem)
vue.component('listar-origens', ListarOrigens)
vue.component('admin-relatorio-encaminhadas', RelatorioEncaminhadas)
vue.component('admin-relatorio-origens', AdminRelatorioOrigens)
vue.component('nova-proposta', NovaProposta)
vue.component('nova-proposta-button', NovaPropostaButton)
vue.component('analisar-proposta', AnalisarProposta)
vue.component('corretor-index', CorretorIndex)
vue.component('calendario-financeiro', CalendarioFinanceiro)
vue.component('calendario-financeiro-receber', CalendarioFinanceiroReceber)
vue.component('calendario-financeiro-recebido', CalendarioFinanceiroRecebido)
vue.component('propostas-pendentes', PropostasPendentes)
vue.component('propostas-cadastradas', PropostasCadastradas)
vue.component('admin-kanban', KanbanAdmin)
vue.component('admin-create-plano', NovoPlano)
vue.component('admin-list-planos', ListarPlanos)
vue.component('corrigir-pendencias', CorrigirPendencias)
vue.component('importar-lista', ImportarLista)
vue.component('detalhes-proposta', DetalhesProposta)
vue.component('relatorio-admin-kanban', RelatorioAdminKanban)
vue.component('leads-rejeitados', Rejeitados)

vue.component('admin-kanban', KanbanAdmin)

vue.mount("#app")


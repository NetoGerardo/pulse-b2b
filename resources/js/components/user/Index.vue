<template>
  <button type="submit" class="btn btn-primary" @click="cadastrar_campanha = true">
    <i class="fa-solid fa-plus" style="margin-right: 5px"></i>
    Nova campanha
  </button>

  <div v-if="cadastrar_campanha">
    <user-cadastrar-campanha :estados="estados" :acaoRealizada="acaoRealizada" :close="close" />
  </div>

  <div class="card">
    <div class="card-body p-3">
      <h4 style="color: black">Última campanha</h4>

      <!-- ÚLTIMA CAMPANHA-->
      <table class="table table-hover" v-if="ultima_campanha">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Status</th>
            <th>Data de início</th>
            <th>Contatos</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              {{ ultima_campanha.nome }}
            </td>

            <td>
              <div v-if="ultima_campanha.status">
                <span v-if="ultima_campanha.status == 'Em andamento'" style="color: green">{{ ultima_campanha.status }}</span>
                <span v-else-if="ultima_campanha.status == 'Finalizada'">{{ ultima_campanha.status }}</span>
                <span v-else>{{ ultima_campanha.status }}</span>
              </div>
              <span v-else>-</span>
            </td>

            <td>
              {{ formatOnlyDate(ultima_campanha.data_inicio) }}
            </td>

            <td>
              {{ ultima_campanha.total_contatos }}
            </td>

            <td>
              <button v-if="ultima_campanha.status != 'Em andamento'" type="submit" class="btn btn-danger" @click="deletar()">
                <i class="fa-solid fa-trash"></i>
              </button>

              <button v-else-if="ultima_campanha.status != 'Finalizada'" type="submit" class="btn btn-warning" @click="concluirCampanha()">
                <i class="fa-solid fa-check"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <h5 v-else>Nenhuma campanha encontrada...</h5>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-3">
      <h4 style="color: black">Canais</h4>

      <!-- ÚLTIMA CAMPANHA-->
      <table class="table table-hover" v-if="prospects_canais">
        <thead>
          <tr>
            <th>Canal</th>
            <th>Interessados</th>
            <th>Não interessados</th>
            <th>Valor investido</th>
            <th>Total</th>
            <th>CPL</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="canal in prospects_canais" :key="canal.id">
            <td>
              {{ canal.nome }}
            </td>

            <td>
              {{ canal.interessados }}
            </td>

            <td>
              {{ canal.nao_interessados }}
            </td>

            <td>75</td>

            <td>
              {{ canal.total_prospects }}
            </td>

            <td>
              <span v-if="canal.interessados > 0">R${{ toCurrency(80 / canal.interessados) }}</span>
            </td>
          </tr>
        </tbody>
      </table>

      <h5 v-else>Nenhuma campanha encontrada...</h5>
    </div>
  </div>

  <!-- FILTROS -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Filtrar prospects</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group">
            <label for="user_type">Telefone</label>
            <input class="form-control" v-model="filtro_telefone" />
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="user_type">Nome fantasia</label>
            <input class="form-control" v-model="filtro_nome_fantasia" />
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="user_type">Razão Social</label>
            <input class="form-control" v-model="filtro_razao_social" />
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="user_type">CNPJ</label>
            <input class="form-control" v-model="filtro_cnpj" />
          </div>
        </div>

        <div class="col-sm-4">
          <label for="user_type">Status</label>
          <select class="form-control" v-model="filtro_status" @change="buscarProspects">
            <option value="">Todos</option>
            <option value="Qualificados">Somente qualificados</option>
            <option value="Desqualificados">Desqualificados</option>
          </select>
        </div>

        <div class="col-sm-4">
          <label for="user_type">Campanhas</label>
          <select class="form-control" v-model="filtro_campanhas" @change="buscarProspects">
            <option value="">Todas</option>
            <option value="Atual">Somente atual</option>
          </select>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="user_type">Data início</label>
            <input class="form-control" type="date" v-model="data_inicio" />
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label for="user_type">Data fim</label>
            <input class="form-control" type="date" v-model="data_fim" />
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <button type="button" class="btn btn-primary" @click="buscarProspects()">Buscar</button>

            <button type="button" class="btn btn-default" @click="limparFiltros()" style="margin-left: 15px">Limpar filtros</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="card-header" style="background-color: white">
        <div class="card-title" id="name" style="display: flex; align-items: center; justify-content: space-between">
          <h4 style="color: black; margin: 0">Prospects</h4>

          <div class="progress-btn" data-progress-style="fill-back" @click="buscarLotes">
            <div class="btn update-btn">
              <i class="fa-solid fa-arrow-rotate-left"></i>
              Atualizar
            </div>
            <div class="progress"></div>
          </div>
        </div>
      </div>

      <table class="tabela-prospects table table-hover">
        <thead>
          <tr>
            <th>Nome (Razão Social)</th>
            <th>Qualificação</th>
            <th>Status (Whatsapp)</th>
            <th>Contatos</th>
            <th>CNPJ</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="prospects.length === 0">
            <td :colspan="6" class="sem-dados">Nenhum prospect encontrado.</td>
          </tr>

          <tr v-for="prospect in prospects" :key="prospect.id">
            <td>{{ prospect.dados["Razao Social"] || "Não informado" }}</td>

            <td>
              <span :class="['status-badge', getStatusClassLigacao(prospect.status_ligacao)]">
                <span v-if="prospect.status_ligacao.toLowerCase() == 'interessado' || prospect.status_ligacao.toLowerCase() == 'muito interessado'">
                  {{ prospect.status_ligacao }}
                </span>

                <span v-else>Sem interesse</span>
              </span>
            </td>

            <td>
              <span :class="['status-badge', getStatusClass(prospect.status_whatsapp)]">
                {{ prospect.status_whatsapp || "N/A" }}
              </span>
            </td>

            <td class="coluna-contatos">
              <div v-if="prospect.telefone">
                <strong>Telefone:</strong>
                {{ prospect.telefone }}
              </div>
              <div v-if="prospect.dados['E-mail']">
                <strong>Email:</strong>
                {{ prospect.dados["E-mail"] }}
              </div>
            </td>

            <td>
              <span>
                {{ prospect.cnpj || "—" }}
              </span>
            </td>

            <!--
            <td class="coluna-dados">
              <ul v-if="prospect.dados && Object.keys(prospect.dados).length > 0">
                <li v-for="(valor, chave) in filtrarDados(prospect.dados)" :key="chave">
                  <strong>{{ chave }}:</strong>
                  {{ valor }}
                </li>
              </ul>
              <span v-else>Nenhum dado adicional.</span>
            </td>
            -->

            <td>
              <button
                type="button"
                class="btn btn-primary"
                @click="prospect_selecionado = prospect"
                data-toggle="modal"
                data-target="#detalhes-modal"
              >
                <i class="fa-solid fa-search"></i>
              </button>

              <button type="button" class="btn btn-primary" @click="copiarDadosProspect(prospect)"><i class="fa-solid fa-copy"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="card-tools p-2">
      <!-- TOTAIS  -->
      <div style="font-size: 13px" class="float-left" aria-live="polite">
        Exibindo do {{ inicio + 1 }} ao {{ inicio + prospects.length }}
        <p>Total {{ total }}</p>
      </div>

      <!-- PAGINAÇÃO  -->
      <ul class="pagination pagination-sm m-0 float-right">
        <li class="page-item"><a class="page-link" href="#" @click="selecionarPagina(1)">&Lt;</a></li>
        <li class="page-item"><a class="page-link" href="#" @click="selecionarPagina(pagina_atual - 1)">&lt;</a></li>

        <li v-for="pagina in paginas" :key="pagina" :value="pagina" class="page-item">
          <a :class="pagina === pagina_atual ? 'page-link current-page' : 'stopped'" class="page-link" href="#" @click="selecionarPagina(pagina)">
            {{ pagina }}
          </a>
        </li>

        <li class="page-item">
          <a class="page-link" href="#" @click="selecionarPagina(pagina_atual + 1)">&gt;</a>
        </li>
        <li class="page-item"><a class="page-link" href="#" @click="selecionarPagina(max_page)">&Gt;</a></li>
      </ul>
    </div>
  </div>

  <!-- CADASTRAR -->
  <div class="modal fade" id="detalhes-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: black">&times;</button>

            <div class="data-section">
              <h4 class="section-title">👤 Dados Principais</h4>
              <div class="data-grid">
                <div class="data-item full-width">
                  <span class="data-label">Razão Social</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Razao Social", "Razão Social", "Raz\u00e3o Social"]) }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">CNPJ</span>
                  <span class="data-value">{{ prospect_selecionado.cnpj || "—" }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Telefone</span>
                  <span class="data-value">{{ prospect_selecionado.telefone || "—" }}</span>
                </div>
                <div class="data-item full-width">
                  <span class="data-label">E-mail</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["E-mail"]) }}</span>
                </div>
              </div>
            </div>

            <div class="data-section">
              <h4 class="section-title">🏢 Localização e Atividade</h4>
              <div class="data-grid">
                <div class="data-item">
                  <span class="data-label">Bairro</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Bairro"]) }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Cidade / Município</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Cidade", "Municipio"]) }}</span>
                </div>
                <div class="data-item full-width">
                  <span class="data-label">Atividade Principal (CNAE)</span>
                  <span class="data-value">
                    {{ extrairDado(prospect_selecionado, ["Descricao da Atividade Principal", "Texto CNAE Principal"]) }}
                  </span>
                </div>
              </div>
            </div>

            <div class="data-section">
              <h4 class="section-title">⚖️ Detalhes da Empresa</h4>
              <div class="data-grid">
                <div class="data-item">
                  <span class="data-label">Natureza Jurídica</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Natureza Jurídica", "Natureza Juridica"]) }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Porte da Empresa</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Porte da Empresa", "Porte Empresa"]) }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Regime Tributário</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Regime Tributário", "Regime Tributario"]) }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Faturamento Estimado</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Faturamento Estimado"]) }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Colaboradores</span>
                  <span class="data-value">{{ extrairDado(prospect_selecionado, ["Colaboradores Estimados ", "Colaboradores"]) }}</span>
                </div>
              </div>
            </div>

            <div class="data-section">
              <h4 class="section-title">📈 Prospecção</h4>
              <div class="data-grid">
                <div class="data-item">
                  <span class="data-label">Status da Ligação</span>
                  <span class="data-value">{{ prospect_selecionado.status_ligacao || "—" }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Canal</span>
                  <span class="data-value">{{ prospect_selecionado.canal || "—" }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Qtd. Vidas</span>
                  <span class="data-value">{{ prospect_selecionado.vidas || extrairDado(prospect_selecionado, ["vidas"]) }}</span>
                </div>
                <div class="data-item">
                  <span class="data-label">Operadora Ofertada</span>
                  <span class="data-value">{{ prospect_selecionado.operadora || extrairDado(prospect_selecionado, ["operadora"]) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import sweetAlert from "../controller/sweetAlert";
import auxFormatacao from "../controller/auxFormatacao";
import currencyController from "../controller/currencyController";
import moment from "moment";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default {
  mixins: [sweetAlert, Swal, auxFormatacao, currencyController],

  props: ["estados", "ultima_campanha"],

  data() {
    return {
      prospect_selecionado: "",
      filtro_nome_fantasia: "",
      filtro_razao_social: "",
      filtro_cnpj: "",
      filtro_telefone: "",
      filtro_campanhas: "Atual",
      filtro_status: "",
      cadastrar_campanha: false,
      total_cadastrados: 0,
      indicacoes_recebidas: 0,
      total_positivas: 0,
      total_negativas: 0,
      prospects: [],
      inicio: 0,
      qtd_por_pagina: 25,
      paginas: [],
      pagina_atual: 1,
      prospects_canais: [],
      data_inicio: "",
      data_fim: "",
      prospect_selecionado: "",
    };
  },

  mounted() {
    console.log(this.ultima_campanha);

    //DEFININDO DATA DE HOJE
    this.data_inicio = new Date();
    this.data_inicio = this.formatSelectedDate(this.data_inicio);

    //DEFININDO DATA DE HOJE
    this.data_fim = new Date();
    this.data_fim = this.formatSelectedDate(this.data_fim);

    this.buscarProspects();
    this.buscarProspectsCanais();

    this.searchForever();
  },

  methods: {
    extrairDado(prospect, chaves, valorPadrao = "—") {
      let dadosObj = {};

      // 1. Garante que 'chaves' seja sempre um array
      const chavesParaBuscar = Array.isArray(chaves) ? chaves : [chaves];

      // 2. Processa o campo 'dados' (seja string ou objeto)
      if (prospect && prospect.dados) {
        if (typeof prospect.dados === "string") {
          try {
            dadosObj = JSON.parse(prospect.dados);
          } catch (e) {
            console.error(`Erro ao parsear JSON do prospect.dados:`, e);
            // 'dadosObj' continua como {}
          }
        } else if (typeof prospect.dados === "object" && prospect.dados !== null) {
          dadosObj = prospect.dados;
        }
      }

      // 3. Itera pelas chaves fornecidas e encontra a primeira que existe
      for (const chave of chavesParaBuscar) {
        const valor = dadosObj[chave];

        // 4. Verifica se o valor é válido (não é null, undefined ou "")
        if (valor !== null && valor !== undefined && valor !== "") {
          return valor; // Retorna o primeiro valor encontrado
        }
      }

      // 5. Se o loop terminar, nada foi encontrado
      return valorPadrao;
    },

    cortarString(texto, maxCaracteres) {
      const placeholder = "—";
      const sufixo = "...";

      // 1. Retorna placeholder se o texto for nulo, indefinido ou vazio
      if (!texto || typeof texto !== "string" || texto.trim() === "") {
        return placeholder;
      }

      // 2. Retorna o texto original se ele já for menor ou igual ao máximo
      if (texto.length <= maxCaracteres) {
        return texto;
      }

      // 3. Caso especial: se o máximo for muito pequeno, apenas corta
      if (maxCaracteres <= sufixo.length) {
        return texto.substring(0, maxCaracteres);
      }

      // 4. Corta a string e adiciona o sufixo "..."
      return texto.substring(0, maxCaracteres - sufixo.length) + sufixo;
    },

    formatarTelefone(tel) {
      if (!tel) return "—";

      // Remove tudo que não for dígito
      const digitos = tel.replace(/\D/g, "");

      // Caso 1: Formato 55119XXXXXXXX (13 dígitos)
      if (digitos.length === 13 && digitos.startsWith("55")) {
        const num = digitos.substring(2); // Remove o '55'
        const ddd = num.substring(0, 2);
        const part1 = num.substring(2, 7);
        const part2 = num.substring(7);
        return `(${ddd}) ${part1}-${part2}`;
      }

      // Caso 2: Formato 119XXXXXXXX (11 dígitos, celular)
      if (digitos.length === 11) {
        const ddd = digitos.substring(0, 2);
        const part1 = digitos.substring(2, 7);
        const part2 = digitos.substring(7);
        return `(${ddd}) ${part1}-${part2}`;
      }

      // Caso 3: Formato 11XXXXXXXX (10 dígitos, fixo)
      if (digitos.length === 10) {
        const ddd = digitos.substring(0, 2);
        const part1 = digitos.substring(2, 6);
        const part2 = digitos.substring(6);
        return `(${ddd}) ${part1}-${part2}`;
      }

      // Se não se encaixar em nenhum formato, retorna o original
      return tel;
    },

    /**
     * Recebe um objeto 'prospect' e copia seus dados formatados
     * para a área de transferência.
     */
    async copiarDadosProspect(prospect) {
      console.log("Dados");
      console.log(prospect);

      if (!prospect) {
        console.error("Prospect nulo ou indefinido.");
        return;
      }

      let dados = {};
      try {
        // Tenta parsear a string JSON que vem do banco
        if (typeof prospect.dados === "string") {
          // Se for string, faz o parse
          dados = JSON.parse(prospect.dados);
        } else if (typeof prospect.dados === "object" && prospect.dados !== null) {
          // Se já for objeto, apenas atribui
          dados = prospect.dados;
        }
      } catch (e) {
        console.error("Erro ao parsear a string JSON de dados:", e);
        // O método continuará, mas 'dados' estará vazio
      }

      console.log("Dados");
      console.log(dados);

      // --- Helper para checagem de Nulos/Vazios ---
      // Garante que 'null', 'undefined' ou "" virem '—'
      const v = (val) => (val === null || val === undefined || val === "" ? "—" : val);

      // --- Mapeamento dos Dados ---
      // Note o uso das chaves exatas do seu JSON, incluindo espaços no final
      const razaoSocial = v(dados["Razao Social"]);
      const cnpj = prospect.cnpj;
      const telefone = this.formatarTelefone(prospect.telefone);
      const email = v(dados["E-mail"]);

      const bairro = v(dados["Bairro"]) != "—" ? v(dados["Bairro"]) : v(dados["Bairro"]);
      const cidade = v(dados["Cidade"]) != "—" ? v(dados["Cidade"]) : v(dados["Municipio"]);

      const cnae = v(dados["Texto CNAE Principal"]) != "—" ? v(dados["Texto CNAE Principal"]) : v(dados["Descricao da Atividade Principal"]);

      const natureza = v(dados["Natureza Jurídica"]);
      const porte = v(dados["Porte Empresa"]) != "—" ? v(dados["Porte Empresa"]) : v(dados["Porte da Empresa"]);
      const regime = v(dados["Regime Tributário"]);
      const faturamento = v(dados["Faturamento Estimado"]);
      const colaboradores = v(dados["Colaboradores Estimados "]); // Note o espaço

      console.log("porte " + porte);
      console.log(v(dados["porte"]));

      // --- Dados Faltantes ---
      // ATENÇÃO: 'Quantidade de vidas' e 'Operadora ofertada' não
      // estavam no objeto 'prospect' que você forneceu.
      // Estou usando '—' como placeholder.
      // Se você tiver esses dados (ex: prospect.vidas), substitua '—' abaixo.
      const qtdVidas = v(prospect.vidas); // ou '—' se não existir
      const operadora = v(prospect.operadora); // ou '—' se não existir

      // --- Construção da String Final ---
      const textoParaCopiar = `👤 *Razão Social*: ${razaoSocial}
🆔 *CNPJ*: ${cnpj}
📞 *Telefone*: ${telefone}
📧 *E-mail*: ${email}

🏢 *Bairro*: ${bairro}
🏙 *Cidade*: ${cidade}
🏷 *Atividade Principal (CNAE)*: ${cnae}

⚖ *Natureza Jurídica*: ${natureza}
🏢 *Porte da Empresa*: ${porte}
💰 *Regime Tributário*: ${regime}
💵 *Faturamento Estimado*: ${faturamento}
👥 *Colaboradores*: ${colaboradores}

👪 *Quantidade de vidas*: ${qtdVidas}
🏥 *Operadora ofertada*: ${operadora}

📝 *Observação*:
Não ofertamos preços em nossas campanhas. Apenas confirmamos o interesse de CNPJs em cotar com a operadora informada.
👉 Sugerimos que dê continuidade ao atendimento e cite a operadora de interesse do cliente para melhor identificação.`;

      // --- Lógica de Copiar ---
      try {
        // Usa a API moderna do navegador
        await navigator.clipboard.writeText(textoParaCopiar);

        // Feedback para o usuário (opcional, mas recomendado)
        alert("Dados do prospect copiados para a área de transferência!");
      } catch (err) {
        console.error("Falha ao copiar dados: ", err);
        alert("Erro ao copiar os dados. Verifique as permissões do navegador.");
      }
    },

    formatSelectedDate(date) {
      return moment(date).format("yyyy-MM-DD");
    },

    limparFiltros() {
      this.filtro_nome_fantasia = "";
      this.filtro_razao_social = "";
      this.filtro_cnpj = "";
      this.filtro_telefone = "";
      this.filtro_campanhas = "Atual";
      this.filtro_status = "";
    },

    buscarProspectsCanais() {
      let data = {
        campanha_id: this.ultima_campanha.id,
      };

      axios
        .post(`/user/canais/prospects_canais`, data)
        .then((response) => {
          this.prospects_canais = response.data.prospects_canais;

          console.log("prospects_canais");
          console.log(this.prospects_canais);
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    searchForever() {
      let that = this;

      setTimeout(() => {
        that.buscarProspects();
        that.buscarProspectsCanais();
        that.searchForever();
      }, 5000);
    },

    formatarData(dataIso) {
      if (!dataIso) return "N/A";
      const data = new Date(dataIso);
      return data.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    },

    filtrarDados(dados) {
      const dadosFiltrados = { ...dados };
      // Remove chaves que já têm colunas próprias
      delete dadosFiltrados["Razao Social"];
      delete dadosFiltrados["E-mail"];
      return dadosFiltrados;
    },

    getStatusClassLigacao(status) {
      const statusNormalizado = status.toLowerCase();

      if (!status) return "status-default";

      if (
        statusNormalizado.includes("interessado") ||
        statusNormalizado.includes("muito interessado") ||
        statusNormalizado.includes("concluída") ||
        statusNormalizado.includes("mensagem enviada")
      ) {
        return "status-sucesso";
      } else {
        return "status-falha";
      }
    },

    getStatusClass(status) {
      if (!status) return "status-default";

      const statusNormalizado = status.toLowerCase();
      if (statusNormalizado.includes("não qualificada") || statusNormalizado.includes("recusou") || statusNormalizado.includes("falha no envio")) {
        return "status-falha";
      }

      if (
        statusNormalizado.includes("interessado") ||
        statusNormalizado.includes("muito interessado") ||
        statusNormalizado.includes("concluída") ||
        statusNormalizado.includes("mensagem enviada")
      ) {
        return "status-sucesso";
      }

      if (statusNormalizado.includes("pendente") || statusNormalizado.includes("agendado")) {
        return "status-pendente";
      }
      return "status-default";
    },

    buscarProspects() {
      this.isLoading = true;
      let campanha_id = this.ultima_campanha ? this.ultima_campanha.id : null;

      if (this.filtro_campanhas == "") {
        campanha_id = null;
      }

      let data = {
        data_inicio: this.formatDateToSearch(this.data_inicio),
        data_fim: this.formatDateToSearchTime(this.data_fim),
        nome_fantasia: this.filtro_nome_fantasia,
        razao_social: this.filtro_razao_social,
        cnpj: this.filtro_cnpj,
        telefone: this.filtro_telefone,
        campanha_id: campanha_id,
        inicio: this.inicio,
        tamanho: this.qtd_por_pagina,
        status: this.filtro_status,
      };

      console.log("Buscando prospects");
      console.log(data);

      axios
        .post(`/user/prospects/search`, data)
        .then((response) => {
          this.isLoading = false;

          this.prospects = response.data.prospects;
          this.total = response.data.total;

          console.log("prospects encontradas");
          console.log(response.data.prospects);

          this.pagination(response.data);
        })
        .catch((error) => {
          console.log(error);
          this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    formatDateToSearch(date) {
      return moment(date).format("yyyy-MM-DD 00:00:00");
    },

    formatDateToSearchTime(date) {
      return moment(date).format("yyyy-MM-DD 23:59:59");
    },

    pagination(data) {
      this.paginas = [];

      let length = data.total / this.qtd_por_pagina;
      let i = 0;

      this.max_page = Math.ceil(length);

      //DEFININDO MÁXIMO E MÍNIMO DE PÁGINAS
      if (length < 0) {
        length = 0;
      } else if (length > 10) {
        if (this.pagina_atual >= 10) {
          let minimo = this.pagina_atual - 10;

          length = Math.ceil(length);

          if (length - this.pagina_atual >= 10) {
            length = this.pagina_atual + 10;
            i = minimo;
          } else {
            i = this.pagina_atual - 10 + (length - this.pagina_atual);
          }

          //CASO O CALCULO O MINIMO SEJA IGUAL A PAGINA ATUAL - 1, REDUZIR 10 DA PAGINA ATUAL CASO ELA SEJA MAIOR QUE 10
          if (i == this.pagina_atual - 1 && this.pagina_atual >= 10) {
            i = this.pagina_atual - 10;
          }

          console.log("I = " + i);
        } else {
          length = 10;
        }
      }

      for (i; i < length; i++) {
        this.paginas.push(i + 1);
      }
    },

    alterarQtdPagina() {
      this.pagina_atual = 1;
      this.inicio = 0;

      this.buscarCampanhas();
    },

    concluirCampanha() {
      if (this.ultima_campanha.status == "Agendada") {
        this.showErrorMessageWithButton("Ops...", `Não é possível finalizar campanhas com o status "${this.ultima_campanha.status}"`);
      } else {
        this.$swal
          .fire({
            title: `<h3 style='color:#616060'>Deseja <b style="color:orange">finalizar</b> a campanha <br/><b>${this.ultima_campanha.nome}</b>?<br/>Esta ação é irreversível.</h3>`,
            icon: "warning",
            padding: "1.5em",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, quero finalizar!",
          })
          .then((result) => {
            if (result.value) {
              this.isLoading = true;

              let data = {
                id: this.ultima_campanha.id,
                status: "Finalizada",
              };

              axios
                .post(`/user/campanha/update_status`, data)
                .then((response) => {
                  this.showSuccessMessageWithButtonAndRefresh("Sucesso", "Campanha atualizada!");
                })
                .catch((error) => {
                  this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
                  console.log(error.response.data);
                });
            }
          });
      }
    },

    deletar() {
      if (this.ultima_campanha.status != "Agendada") {
        this.showErrorMessageWithButton("Ops...", `Não é possível excluir campanhas com o status "${this.ultima_campanha.status}"`);
      } else {
        this.$swal
          .fire({
            title: `<h3 style='color:#616060'>Deseja <b style="color:red">deletar</b> a campanha <br/><b>${this.ultima_campanha.nome}</b>?<br/>Esta ação é irreversível.</h3>`,
            icon: "warning",
            padding: "1.5em",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sim, quero deletar!",
          })
          .then((result) => {
            if (result.value) {
              this.isLoading = true;

              let data = {
                id: this.ultima_campanha.id,
              };

              axios
                .post(`/user/campanha/delete`, data)
                .then((response) => {
                  this.showSuccessMessageWithButtonAndRefresh("Sucesso", "Campanha deletada!");
                })
                .catch((error) => {
                  this.showErrorMessageWithButtonAndRefresh("Ops..", error.response.data);
                  console.log(error.response.data);
                });
            }
          });
      }
    },

    acaoRealizada(acao) {
      if (acao == "cadastrar") {
        this.cadastrar_campanha = false;
      }
    },

    close(acao) {
      if (acao == "cadastrar") {
        this.cadastrar_campanha = false;
      }
    },

    getUrl(telefone) {
      return "https://api.whatsapp.com/send?phone=" + telefone;
    },

    converterCNPJ(possui_cnpj) {
      if (possui_cnpj == 0) {
        return "Não";
      } else {
        return "Sim";
      }
    },

    formatDate(date) {
      return moment(date).format("DD/MM/YYYY HH:mm:ss");
    },

    formatOnlyDate(date) {
      return moment(date).format("DD/MM/YYYY");
    },

    confirmarNegativo(lead) {
      let html = "Acrescente um comentário à sua avaliação <b style='color:red'> Negativa?</b>";

      this.$swal.fire({
        title: "Confirmação",
        html: html,
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirmar",
        input: "text",
        inputAttributes: {
          autocapitalize: "off",
          required: false,
        },
        inputValidator: (value) => {
          if (!value) {
            return "Coloque uma justificativa!";
          } else {
            this.confirmar(lead, value, "NEGATIVA");
          }
        },
      });
    },

    confirmarPositivo(lead) {
      let html = "Que bom! Deseja acrescentar algum comentário à sua avaliação <b style='color:green'> Positiva?</b>";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Confirmar",
          input: "text",
          inputAttributes: {
            autocapitalize: "off",
          },
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.confirmar(lead, result.value, "POSITIVA");
          }
        });
    },

    confirmar(lead, comentario, avaliacao) {
      let data = {
        lead_id: lead.id,
        comentario: comentario,
        avaliacao: avaliacao,
      };

      axios
        .post(`/user/leads/avaliar`, data)
        .then((response) => {
          this.showSuccessMessage("Lead avaliado!");
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },
  },
};
</script>

<style scoped>
/* Estilos básicos para a tabela */
.tabela-container {
  font-family: Arial, sans-serif;
  width: 100%;
  margin: 20px auto;
  overflow-x: auto; /* Permite scroll horizontal em telas pequenas */
}

.tabela-prospects {
  width: 100%;
  border-collapse: collapse;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.tabela-prospects th,
.tabela-prospects td {
  border: 1px solid #ddd;
  padding: 12px 15px;
  text-align: left;
  vertical-align: top;
}

.tabela-prospects thead {
  background-color: #f4f6f8;
}

.tabela-prospects thead th {
  color: #333;
  font-weight: bold;
}

.tabela-prospects tbody tr:nth-of-type(even) {
  background-color: #f9f9f9;
}

.tabela-prospects tbody tr:hover {
  background-color: #f1f1f1;
}

.sem-dados {
  text-align: center;
  color: #777;
  padding: 30px;
  font-style: italic;
}

/* Estilo para a coluna de DADOS (JSON) */
.coluna-dados ul {
  list-style-type: none;
  padding-left: 0;
  margin: 0;
  font-size: 0.9em; /* Tamanho menor para os dados internos */
  max-width: 300px; /* Limita a largura */
}

.coluna-dados li {
  padding: 4px 0;
  border-bottom: 1px solid #eee;
  word-break: break-word; /* Quebra palavras longas */
}

.coluna-dados li:last-child {
  border-bottom: none;
}

.coluna-dados li strong {
  color: #555;
  margin-right: 5px;
}

/* Estilos para a coluna de Contatos */
.coluna-contatos div {
  margin-bottom: 5px;
  font-size: 0.9em;
}

/* Estilos para Ações */
.coluna-acoes button {
  margin-right: 5px;
  padding: 5px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.coluna-acoes button:first-of-type {
  background-color: #007bff;
  color: white;
}
.coluna-acoes button:last-of-type {
  background-color: #ffc107;
  color: #333;
}

/* Estilos para os Badges de Status */
.status-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.85em;
  font-weight: bold;
  color: white;
  white-space: nowrap;
}

.status-default {
  background-color: #6c757d;
}
.status-sucesso {
  background-color: #28a745;
}
.status-falha {
  background-color: #dc3545;
}
.status-pendente {
  background-color: #ffc107;
  color: #333;
}

.progress-btn {
  position: relative;
  width: 150px;
  height: 40px;
  display: inline-block;
  font-family: "RobotoDraft", "Roboto", sans-serif;
  color: #fff !important;
  font-weight: normal;
  font-size: 20px;
  transition: all 0.4s ease;
  border: 1px solid gray;
}

.progress-btn:not(.active) {
  cursor: pointer;
}

.progress-btn .btn {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  line-height: 40px; /* <<< MUDE AQUI (de 5px para 40px) */
  text-align: center;
  z-index: 10;
  opacity: 1;

  /* --- Mágica do Flexbox --- */
  /* Adicione estas 4 linhas: */
  display: flex;
  align-items: center; /* Alinha verticalmente */
  justify-content: center; /* Alinha horizontalmente */
  gap: 8px; /* (Opcional) Espaço entre o ícone e o texto */
}

.progress-btn .progress {
  width: 0%;
  z-index: 5;
  background: #1d743a;
  opacity: 0;
  transition: all 0.3s ease;
  height: 5px;
}

.progress-btn .progress {
  opacity: 1;
  animation: progress-anim 5s ease infinite;
}

.progress-btn[data-progress-style="fill-back"] .progress {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
}

@keyframes progress-anim {
  0% {
    width: 0%;
  }
  5% {
    width: 0%;
  }
  10% {
    width: 15%;
  }
  30% {
    width: 40%;
  }
  50% {
    width: 55%;
  }
  80% {
    width: 100%;
  }
  95% {
    width: 100%;
  }
  100% {
    width: 0%;
  }
}

.span-green {
  background-color: green;
}

.span-grwy {
  background-color: gray;
}
</style>

<style scoped>
/* O fundo escuro que cobre a tela */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  transition: opacity 0.3s ease;
}

/* O container branco do modal */
.modal-content {
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;

  /* Responsividade: ocupa 90% da largura, mas no máximo 700px */
  width: 90%;
  max-width: 700px;

  /* Responsividade: ocupa 90% da altura, permitindo scroll */
  max-height: 90vh;
}

/* Cabeçalho */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #eee;
}

.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  color: #333;
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 1.75rem;
  font-weight: bold;
  line-height: 1;
  color: #888;
  cursor: pointer;
  padding: 0;
}
.close-btn:hover {
  color: #000;
}

/* Corpo - onde os dados ficam (com scroll) */
.modal-body {
  padding: 1.5rem;
  overflow-y: auto; /* Adiciona scroll se o conteúdo for maior que a altura */
}

.data-section {
  margin-bottom: 1.5rem;
}
.data-section:last-child {
  margin-bottom: 0;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
  color: #333;
  border-bottom: 1px solid #f0f0f0;
  padding-bottom: 0.5rem;
  margin-top: 0;
  margin-bottom: 1rem;
}

/* Grid de dados (mobile-first: 1 coluna) */
.data-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem; /* Espaçamento entre os itens */
}

.data-item {
  display: flex;
  flex-direction: column;
}

.data-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: #555;
  margin-bottom: 0.25rem;
  text-transform: uppercase;
}

.data-value {
  font-size: 0.95rem;
  color: #222;
  word-break: break-word; /* Quebra palavras longas (ex: emails) */
}

/* Classe para itens que devem ocupar a largura total */
.data-item.full-width {
  grid-column: 1 / -1;
}

/* Rodapé */
.modal-footer {
  display: flex;
  justify-content: flex-end;
  padding: 1rem 1.5rem;
  border-top: 1px solid #eee;
  background-color: #f9f9f9;
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}

/* Estilos de botão (exemplo) */
.btn {
  padding: 0.6rem 1rem;
  border: none;
  border-radius: 5px;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-secondary {
  background-color: #e9ecef;
  color: #333;
}
.btn-secondary:hover {
  background-color: #d1d5da;
}
.btn-primary {
  background-color: #007bff;
  color: white;
  margin-left: 0.5rem;
}
.btn-primary:hover {
  background-color: #0056b3;
}

/* --- Media Query para Responsividade --- */

/* Em telas maiores (desktop), o grid de dados passa a ter 2 colunas */
@media (min-width: 600px) {
  .data-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>

<style scoped>
/* O fundo escuro que cobre a tela */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  transition: opacity 0.3s ease;
  padding: 1rem; /* Padding para evitar que o modal toque as bordas */
  overflow-y: auto; /* Permite scrollar o FUNDO se o modal for maior que a tela */
}

/* O container branco do modal (COM SEU ESTILO DE LARGURA) */
.modal-content {
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;
  max-height: 95vh; /* Impede que o modal seja maior que a tela */
  margin: 0; /* Resetamos a margem, pois o flex-center cuida disso */

  /* --- SEUS ESTILOS DE LARGURA (Desktop-First) --- */

  /* Default (acima de 1600px) */
  width: 1500px;
}

@media screen and (max-width: 1600px) {
  .modal-content {
    width: 1350px;
  }
}

@media screen and (max-width: 1350px) {
  .modal-content {
    width: 1250px;
  }
}

@media screen and (max-width: 1200px) {
  .modal-content {
    width: 1100px;
  }
}

@media screen and (max-width: 1100px) {
  .modal-content {
    width: 1000px;
  }
}

@media screen and (max-width: 960px) {
  .modal-content {
    width: 750px;
  }
}

@media screen and (max-width: 800px) {
  .modal-content {
    width: 600px;
  }
}

@media screen and (max-width: 600px) {
  .modal-content {
    width: 100%;
    min-width: 400px; /* Garante uma largura mínima no mobile */
    max-height: 100vh; /* Permite ocupar a tela toda */
    height: 100%;
    border-radius: 0;
  }
}
/* --- FIM DOS SEUS ESTILOS --- */

/* Cabeçalho */
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #eee;
}
.modal-header h3 {
  margin: 0;
  font-size: 1.25rem;
  color: #333;
}
.close-btn {
  background: transparent;
  border: none;
  font-size: 1.75rem;
  font-weight: bold;
  line-height: 1;
  color: #888;
  cursor: pointer;
  padding: 0;
}
.close-btn:hover {
  color: #000;
}

/* Corpo - onde os dados ficam (com scroll) */
.modal-body {
  padding: 1.5rem;
  overflow-y: auto; /* Adiciona scroll INTERNO se o conteúdo for maior */
}

.data-section {
  margin-bottom: 1.5rem;
}
.data-section:last-child {
  margin-bottom: 0;
}

.section-title {
  font-size: 1rem;
  font-weight: 600;
  color: #333;
  border-bottom: 1px solid #f0f0f0;
  padding-bottom: 0.5rem;
  margin-top: 0;
  margin-bottom: 1rem;
}

/* --- GRID DE DADOS ATUALIZADO (1, 2 ou 3 colunas) --- */
.data-grid {
  display: grid;
  gap: 1.25rem; /* Um pouco mais de espaço */

  /* Mobile: 1 coluna (Default) */
  grid-template-columns: 1fr;
}

/* Telas Médias (Tablet): 2 colunas */
@media (min-width: 768px) {
  .data-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Telas Grandes (Desktop): 3 colunas */
@media (min-width: 1200px) {
  .modal-content {
    /* Garante que o breakpoint de 1100px do seu CSS não conflite */
    width: 1100px;
  }

  /* Aplica 3 colunas quando o modal estiver largo */
  .data-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Aplicando suas larguras maiores */
@media (min-width: 1201px) {
  .modal-content {
    width: 1250px;
  }
  .data-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 1351px) {
  .modal-content {
    width: 1350px;
  }
  .data-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
@media (min-width: 1601px) {
  .modal-content {
    width: 1500px;
  }
  .data-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.data-item {
  display: flex;
  flex-direction: column;
}

.data-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: #555;
  margin-bottom: 0.25rem;
  text-transform: uppercase;
}

.data-value {
  font-size: 0.95rem;
  color: #222;
  word-break: break-word; /* Quebra palavras longas */
}

/* Classe para itens que devem ocupar a largura total */
.data-item.full-width {
  grid-column: 1 / -1; /* Ocupa todas as colunas disponíveis (1, 2 ou 3) */
}

/* Rodapé */
.modal-footer {
  display: flex;
  justify-content: flex-end;
  padding: 1rem 1.5rem;
  border-top: 1px solid #eee;
  background-color: #f9f9f9;
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}

/* Estilos de botão (exemplo) */
.btn {
  padding: 0.6rem 1rem;
  border: none;
  border-radius: 5px;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-secondary {
  background-color: #e9ecef;
  color: #333;
}
.btn-secondary:hover {
  background-color: #d1d5da;
}
.btn-primary {
  background-color: #007bff;
  color: white;
  margin-left: 0.5rem;
}
.btn-primary:hover {
  background-color: #0056b3;
}
</style>

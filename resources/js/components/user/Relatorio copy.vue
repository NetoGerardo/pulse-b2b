<template>

  <button @click="exibirLista('nao_contactados')" type="button" class="btn btn-success btn-sm">
    Mensagem não enviada
  </button>

  <button @click="exibirLista('todos')" type="button" class="btn btn-secondary btn-sm">
    Todos
  </button>


  <button @click="exibirLista('contactados')" type="button" class="btn btn-info btn-sm">
    Mensagem enviada
  </button>


  <div class="table-responsive">
    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th>Nome</th>
          <th>CNPJ?</th>
          <th>Data</th>
          <th>Bairro</th>
          <th>Ocupação</th>
          <th>WhatsApp</th>
          <th>Interessado?</th>
          <th>Mensagem enviada?</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lead in aux_leads" :key="lead" :value="lead">
          <td>{{ lead.nome }}</td>

          <td data-label="Possui Cnpj?">
            {{ converterCNPJ(lead.possui_cnpj) }}
          </td>

          <td data-label="Data">
            {{ formatDate(lead.created_at) }}
          </td>

          <td>{{ lead.bairro }}</td>

          <td>{{ lead.ocupacao }}</td>

          <td><a :href=getUrl(lead.telefone) class="float" target="_blank">
              <img alt="Avatar" class="table-avatar" style="width:20px;opacity: .8"
                src="/assets/images/whats-logo.png" /></a>
            {{ lead.telefone }}
          </td>

          <td v-if="!lead.data_avaliacao">
            <button style="width:50%" @click="confirmarPositivo(lead)" type="button" class="btn btn-success btn-sm">
              Sim
            </button>
            <button style="width:50%" @click="confirmarNegativo(lead)" type="button" class="btn btn-danger btn-sm">
              Não
            </button>
          </td>

          <td>
            <button v-if="lead.contactado == 0" style="width:100%" @click="confirmarContactado(lead)" type="button"
              class="btn btn-info btn-sm">
              Enviada
            </button>

            <span v-if="lead.contactado == 1" style="color: green; font-size: 15px"
              class="badge badge-light-info">Enviada</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import sweetAlert from "../controller/sweetAlert";
import moment from "moment";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default {
  mixins: [sweetAlert, Swal],

  props: ['leads'],

  data() {
    return {
      total_cadastrados: 0,
      indicacoes_recebidas: 0,
      total_positivas: 0,
      total_negativas: 0,
      aux_leads: [],
      aux_leads_contactados: [],
      aux_leads_nao_contactados: []
    };
  },

  mounted() {

    for (let i = 0; i < this.leads.length; i++) {

      if (this.leads[i].contactado == 1) {
        this.aux_leads_contactados.push(this.leads[i]);
      } else {
        this.aux_leads_nao_contactados.push(this.leads[i]);
      }

      if (this.leads[i].origem_lead == 'CADASTRO MANUAL') {
        this.total_cadastrados++;
      } else {
        this.indicacoes_recebidas++;
      }

      if (this.leads[i].data_avaliacao) {
        if (this.leads[i].avaliacao == 'POSITIVA') {
          this.total_positivas++;
        } else {
          this.total_negativas++;
        }
      }
    }

    this.aux_leads = this.aux_leads_nao_contactados;

  },

  methods: {

    exibirLista(tipo) {
      if (tipo == "todos") {
        this.aux_leads = this.leads;
      } else if (tipo == "contactados") {
        this.aux_leads = this.aux_leads_contactados;
      } else if (tipo == "nao_contactados") {
        this.aux_leads = this.aux_leads_nao_contactados;
      }
    },

    getUrl(telefone) {
      return "https://api.whatsapp.com/send?phone=" + this.limparTelefone(telefone);
    },

    limparTelefone(telefone) {

      telefone = telefone.replace(/([^\d])+/gim, '');

      //ADICIONANDO MANUALMENTE DDD
      if (telefone.length < 10) {
        telefone = "21" + telefone;
      }

      //ADICIONANDO CÓDIGO DO PAÍS
      if (telefone.substr(0, 2) != "55") {
        telefone = "55" + telefone;
      }

      return telefone;
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

    confirmarNegativo(lead) {
      let html = "Acrescente um comentário à sua avaliação <b style='color:red'> Negativa?</b>";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Confirmar",
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off',
            required: false
          },
          inputValidator: (value) => {
            if (!value) {
              return 'Coloque uma justificativa!'
            }
            else {
              this.confirmar(lead, value, "NEGATIVA")
            }
          }
        })
    },

    confirmarContactado(lead) {
      let html = "Que bom! Você contactou o cliente com o telefone <b style='color:green'>" + this.limparTelefone(lead.telefone) + "</b>";

      this.$swal
        .fire({
          title: "Confirmação",
          html: html,
          icon: "info",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Confirmar",
        })
        .then(result => {
          if (result.isConfirmed) {
            this.confirmarJaContactado(lead);
          }
        });
    },

    confirmarJaContactado(lead) {

      let indice = this.findLead(this.aux_leads_nao_contactados, lead.id);

      this.aux_leads_nao_contactados.splice(indice, 1)[0];

      this.aux_leads_contactados.push(lead);

      let data = {
        lead_id: lead.id,
      }

      axios
        .post(`/user/leads/conectado/update`, data)
        .then((response) => {
          this.showSuccessMessage("Contactado");
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });

    },

    findLead(aray, id) {
      let lead = aray.findIndex(element => element['id'] == id);

      return lead;
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
        })
        .then(result => {
          if (result.isConfirmed) {
            this.confirmar(lead, result.value, "POSITIVA");
          }
        });
    },

    atualizarStatus(lead) {
      let data = {
        lead_id: lead.id,
        status: lead.status_negociacao,
      }

      axios
        .post(`/user/leads/status/update`, data)
        .then((response) => {
          this.showSuccessMessage("Status atualizado!");
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },

    confirmar(lead, comentario, avaliacao) {
      let data = {
        lead_id: lead.id,
        comentario: comentario,
        avaliacao: avaliacao
      }

      axios
        .post(`/user/leads/avaliar`, data)
        .then((response) => {
          this.showSuccessMessage("Lead avaliado!");
          window.location.reload();
        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error.response.data);
          console.log(error.response.data);
        });
    },
  },
};
</script>

<template>
  <div class="table-responsive">
    <table id="zero_config" class="table table-striped table-bordered no-wrap">
      <thead>
        <tr>
          <th style="width: 5% ">Nome</th>
          <th>Categoria</th>
          <th>CNPJ?</th>
          <th>Data</th>
          <th>Bairro</th>
          <th>Ocupação</th>
          <th>Origem</th>
          <th>Status Sistema</th>
          <th>Ações</th>
          <th>Copiar</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lead in leads" :key="lead" :value="lead">

          <td style="word-break:break-word">{{ lead.nome }}</td>

          <td> <span class="badge font-12 text-white font-weight-medium badge-pill ml-2 d-lg-block d-md-none"
              :style=getStyleCategoria(lead)>{{
                  lead.categoria
              }}</span>
          </td>

          <td data-label="Possui Cnpj?">
            {{ converterCNPJ(lead.possui_cnpj) }}
          </td>

          <td data-label="Data">
            {{ formatDate(lead.created_at) }}
          </td>

          <td>{{ lead.bairro }}</td>

          <td>{{ lead.ocupacao }}</td>

          <td>{{ lead.origem_lead }}</td>

          <td :style=getStyle(lead)>{{ lead.avaliacao_sistema }}</td>

          <td>
            <button data-toggle="modal" data-target="#enviar-modal" style="width:100%" @click="selecionarLead(lead)"
              type="button" class="btn btn-primary btn-sm">
              Encaminhar
            </button>
          </td>

          <td>
            <button style="width:100%" @click="copiarLead(lead)" type="button" class="btn btn-success btn-sm">
              Copiar
            </button>
          </td>

        </tr>
      </tbody>
    </table>
  </div>
  <div class="modal fade" id="enviar-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <form @submit.prevent>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
              &times;
            </button>
            <h4 style="font-weight:bold">Encaminhar Indicação</h4>

            <div class="form-group">
              <label for="email">Nome</label>
              <input readonly="true" class="form-control" id="email" type="text" v-model="lead_selecionado.nome" />
            </div>

            <div class="form-group">
              <label for="user_type">Possui CNPJ?</label>
              <input readonly="true" class="form-control" id="cnpj" type="text" :value="converterCNPJ(lead_selecionado.possui_cnpj)" />
            </div>

            <div class="form-group">
              <label for="user_type">Ocupação?</label>
              <input readonly="true" class="form-control" id="telefone" type="text" v-model="lead_selecionado.ocupacao" />
            </div>

            <div class="form-group">
              <label for="email">Telefone</label>
              <input readonly="true" class="form-control" id="telefone" type="text" v-model="lead_selecionado.telefone" />
            </div>

            <div class="form-group">
              <label for="user_type">Bairro?</label>
              <input readonly="true" class="form-control" id="telefone" type="text" v-model="lead_selecionado.bairro" />
            </div>

            <div class="form-group">
              <label for="user_type">Possui Plano?</label>
              <input readonly="true" class="form-control" id="cnpj" type="text" :value="converterCNPJ(lead_selecionado.possui_plano)" />
            </div>

            <div class="form-group">
              <label for="user_type">Quantidade de vidas</label>
              <input readonly="true" class="form-control" id="senha" type="senha" v-model="lead_selecionado.qtd_vidas"
                autocomplete="senha" />
            </div>

            <div class="form-group">
              <label for="password" class="col-md-4 col-form-label text-md-end">Complemento</label>
              <textarea readonly="true" class="form-control" id="senha" type="senha" v-model="lead_selecionado.complemento"
                autocomplete="senha" rows="5" cols="40" />
            </div>

            <div class="form-group">
              <label for="user_type">Origem</label>
              <input readonly="true" class="form-control" id="senha" type="senha" v-model="lead_selecionado.origem_lead"
                autocomplete="senha" />
            </div>

            <div class="form-group">
              <label for="user_type">Corretor</label>
              <select class="form-control" id="user_type" v-model="corretor_selecionado">
                <option v-for="corretor in corretores" :key="corretor" :value="corretor">
                  {{ corretor.name }}
                </option>
              </select>
            </div>

            <div class="form-group">
                <label for="user_type">Enviar apenas documentação</label>
                <select class="form-control" id="user_type" v-model="enviar_apenas_documentacao">
                  <option :value="false"></option>
                  <option :value="true">Sim</option>
                </select>
              </div>

            <button data-toggle="modal" data-target="#enviar-modal" style="width:100%" @click="enviarLead()"
              type="button" class="btn btn-success btn-sm">
              Enviar
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import sweetAlert from "../controller/sweetAlert";
import moment from "moment";

export default {
  mixins: [sweetAlert],

  props: ['leads', 'corretores'],

  data() {
    return {
      aux_leads: "",
      corretor_selecionado: "",
      lead_selecionado: "",
      enviar_apenas_documentacao: false
    };
  },

  mounted() {

    this.aux_leads = this.leads;

    console.log(this.aux_leads);
  },

  methods: {

    getStyleCategoria(lead) {

      let color = "";

      if (lead.categoria == "Bronze") {
        color = "#CD7F32";
      } else if (lead.categoria == "Prata") {
        color = "silver";
      } else if (lead.categoria == "Ouro") {
        color = "goldenrod";
      }

      return "background-color:" + color + "; font-weight: bold";
    },

    getStyle(lead) {

      let color = "";

      if (lead.avaliacao_sistema == "Duplicada") {
        color = "#CCCC00";
      } else if (lead.avaliacao_sistema == "Válida") {
        color = "green";
      } else if (lead.avaliacao_sistema == "Inválida") {
        color = "red";
      } else if (lead.avaliacao_sistema == "Interurbana") {
        color = "purple";
      }

      console.log("RETORNANDO COR " + color);

      return "color:" + color + "; font-weight: bold";
    },

    /*
    copiarLead(lead) {
      navigator.clipboard.writeText(this.formatarMensagem(lead));

      this.showSuccessMessage("Dados copiados!");
    },
    */

    copiarLead(lead) {

      let that = this;

      this.$copyText(this.formatarMensagem(lead)).then(
        function (e) {
          console.log(e);
          that.showSuccessMessage("Dados copiados!");
        },
        function (e) {
          alert("Can not copy");
          console.log(e);
        }
      );
    },

    onCopy(text) {
      alert("Texto copiado " + text);
    },

    enviarLead() {
      let data = {
        lead_id: this.lead_selecionado.id,
        corretor_id: this.corretor_selecionado.id,
        enviar_apenas_documentacao: this.enviar_apenas_documentacao
      }

      axios
        .post(`/admin/leads/encaminhar`, data)
        .then((response) => {

          this.showSuccessMessage("Indicação encaminhada!");

          try {

            let indice = this.findLead(this.lead_selecionado.id);

            this.aux_leads.splice(indice, 1)[0];

            this.corretor_selecionado = "";

          } catch ($e) {
            console.log($e);
          }

        })
        .catch((error) => {
          this.showErrorMessageWithButton("Ops..", error);
          console.log(error);
        });
    },

    findLead(id) {
      let lead = this.aux_leads.findIndex(element => element['id'] == id);

      return lead;
    },

    formatarMensagem(lead) {

      let mensagem = "*Olá, você tem uma nova indicação*\n\n" +
        "*Nome:* " + lead.nome + "\n\n" +
        "*Telefone:* " + lead.telefone + "\n\n";

      if (lead.email && lead.email != "") {
        mensagem = mensagem + "*Email:* " + lead.email + "\n\n";
      }

      if (lead.ocupacao && lead.ocupacao != "") {
        mensagem = mensagem + "*Ocupação:* " + lead.ocupacao + "\n\n";
      }

      if (lead.bairro && lead.bairro != "") {
        mensagem = mensagem + "*Bairro:* " + lead.bairro + "\n\n";
      }

      if (lead.complemento && lead.complemento != "") {
        mensagem = mensagem + "*Complemento:* " + lead.complemento + "\n\n";
      }

      return mensagem;
    },

    selecionarLead(lead) {
      this.lead_selecionado = lead;
    },

    converterCNPJ(possui_cnpj) {
      if (possui_cnpj == 0) {
        return "Não";
      } else {
        return "Sim";
      }
    },

    formatarIdades(idades) {
      let json = JSON.parse(idades);

      let idades_txt = "";

      for (let i = 0; i < json.length; i++) {
        idades_txt = idades_txt + json[i].idade + " anos";

        if (i + 1 < json.length) {
          idades_txt = idades_txt + "\n";
        }
      }

      console.log("RETORNANDO");
      console.log(idades_txt);

      return idades_txt;
    },

    formatDate(date) {
      return moment(date).format("DD/MM/YYYY HH:mm:ss");
    },
  },
};
</script>

<template>
  <div v-if="lead">
    <span v-if="!lead.aberto_em" class="badge badge-secondary" style="background-color: orange; margin-bottom: 7px;">Novo</span>

    <h5 class="text-dark mb-0 font-14 font-weight-medium">{{ formatarNome(lead.nome) }}</h5>

    <div style="line-height: 2px; margin-top: 8px">
      <div>
        <p class="text-muted font-12">{{ lead.telefone }}</p>
      </div>
      <div>
        <p class="text-muted font-12">
          <b>Criado em:</b>
          {{ formatDate(lead.created_at) }}
        </p>

        <p class="text-muted font-12">
          <b>Atualizado em:</b>
          {{ formatDate(lead.updated_at) }}
        </p>

        <p class="text-muted font-12">
          <b>Origem:</b>
          {{ lead.origem_lead.nome }}
        </p>

        <p class="text-muted font-12">
          <b>Tarefas pendentes:</b>
          {{ tarefas_pendentes }}
        </p>
        <p class="text-muted font-12">
          <b>Tarefas concluídas:</b>
          {{ tarefas_concluidas }}
        </p>
        <p class="text-muted font-12">
          <b style="color: red">Tarefas atrasadas:</b>
          {{ tarefas_atrasadas }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";

export default {
  components: {},
  props: ["lead"],
  data() {
    return {
      indicacoes_cadastradas: [],
      tarefas_pendentes: 0,
      tarefas_concluidas: 0,
      tarefas_atrasadas: 0,
    };
  },

  mounted() {
    if (this.lead.tarefas) {
      for (let i = 0; i < this.lead.tarefas.length; i++) {
        // Ignora tarefas que já foram concluídas
        if (this.lead.tarefas[i].concluida == 1) {
          this.tarefas_concluidas++;
          continue;
        }

        this.tarefas_pendentes++;

        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);

        const dataVencimento = new Date(this.lead.tarefas[i].expira_em);
        dataVencimento.setHours(0, 0, 0, 0);

        //VENCIDO
        if (hoje > dataVencimento) {
          this.tarefas_atrasadas++;
        }

        //VENCE HOJE
        if (hoje.getTime() === dataVencimento.getTime()) {
          //
        }
      }
    }
  },

  methods: {
    formatarNome(nome_completo) {
      let array_nome = nome_completo.split(" ");

      let nome = nome_completo;

      if (array_nome.length >= 2) {
        nome = array_nome[0] + " " + array_nome[1];
      }

      return nome;
    },

    formatDate(date) {
      return moment(date).format("DD/MM/YYYY HH:mm:ss");
    },
  },
};
</script>

<style scoped>
.card-vencido {
  background-color: orange !important;
}

.card-vencido p {
  color: white !important;
}
</style>

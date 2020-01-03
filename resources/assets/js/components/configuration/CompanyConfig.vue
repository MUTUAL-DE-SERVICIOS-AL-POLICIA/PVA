<template>
  <v-container fluid>
    <template v-if="!loading">
      <v-toolbar>
        <v-toolbar-title>
          Datos Institucionales
        </v-toolbar-title>
        <v-spacer></v-spacer>
      </v-toolbar>
      <CompanyEdit/>
      <v-toolbar class="mt-4">
        <v-toolbar-title>
          Números de Empleador
        </v-toolbar-title>
        <v-tooltip color="white" role="button" bottom>
          <v-icon slot="activator" class="ml-4">info</v-icon>
          <div>
            <v-alert :value="true" type="error">CIUDADES SIN NÚMERO DE EMPLEADOR</v-alert>
            <v-alert :value="true" type="warning" class="black--text">NÚMEROS DE EMPLEADOR SIN CIUDADES ASOCIADAS</v-alert>
            <v-alert :value="true" type="success">CIUDAD ASOCIADA AL NÚMERO DE EMPLEADOR</v-alert>
          </div>
        </v-tooltip>
        <v-spacer></v-spacer>
        <EmployerNumberAdd v-show="employerNumbers.length <= maxEmployerNumbers" ref="EmployerNumberAdd" @updateEmployerNumbersList="updateEmployerNumbersList"/>
      </v-toolbar>
      <v-data-iterator
        :rows-per-page-items="[maxEmployerNumbers]"
        :total-items="maxEmployerNumbers"
        :items="employerNumbers"
        item-key="id"
        content-tag="v-layout"
        :expand="false"
        row
        wrap
      >
        <template v-slot:item="props">
          <v-flex
            xs12
            sm6
            md4
            lg3
          >
            <v-card :class="props.item.cities.length == 0 ? 'warning' : ''">
              <v-card-title>
                <h4>{{ props.item.number }}</h4>
                <v-spacer></v-spacer>
                <v-tooltip top>
                  <v-btn class="mx-0 px-0" slot="activator" @click="$refs.EmployerNumberAdd.openDialog(props.item)" icon>
                    <v-icon color="info">edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn class="mx-0 px-0" slot="activator" @click="bus.$emit('openDialogRemove', `employer_number/${props.item.id}`)" icon>
                    <v-icon color="error">delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
              </v-card-title>
              <v-switch
                v-model="props.expanded"
                :label="(props.expanded) ? 'Ocultar' : 'Ciudades asociadas'"
                class="pl-3 mt-0"
              ></v-switch>
              <v-divider></v-divider>
              <v-list v-if="props.expanded" dense class="mb-4 grey lighten-2">
                <template v-for="city in cities">
                  <v-list-tile :key="city.id">
                    <v-btn @click="switchEmployerNumberCity(props.item.id, city.id)" round :color="hasCity(props.item.cities, city.id) ? 'success' : (!city.employer_number_id ? 'error' : '')" :dark="hasCity(props.item.cities, city.id)">{{ city.name }}</v-btn>
                  </v-list-tile>
                </template>
              </v-list>
            </v-card>
          </v-flex>
        </template>
        <template v-slot:footer></template>
      </v-data-iterator>
    </template>
    <Loading v-else/>
    <RemoveItem :bus="bus"/>
  </v-container>
</template>

<script>
import Vue from 'vue'
import CompanyEdit from './CompanyEdit'
import EmployerNumberAdd from './EmployerNumberAdd'
import RemoveItem from '../RemoveItem'
import Loading from '../Loading'

export default {
  name: 'CompanyConfig',
  components: {
    RemoveItem,
    CompanyEdit,
    Loading,
    EmployerNumberAdd
  },
  data() {
    return {
      bus: new Vue(),
      loading: true,
      maxEmployerNumbers: 12,
      employerNumbers: [],
      cities: []
    }
  },
  beforeMount() {
    this.updateEmployerNumbersList()
  },
  mounted() {
    this.bus.$on('removed', employerNumberId => {
      this.employerNumbers = this.employerNumbers.filter(o => o.id != employerNumberId)
      this.updateEmployerNumbersList()
    })
  },
  methods: {
    updateEmployerNumbersList() {
      this.getEmployerNumbers()
      this.getCities()
    },
    async switchEmployerNumberCity(employerNumberId, cityId) {
      let employerNumber = this.employerNumbers.find(o => o.id == employerNumberId)
      try {
        // this.loading = true
        let res = await axios.patch(`city/${cityId}`, {
          employer_number_id: this.hasCity(employerNumber.cities, cityId) ? null : employerNumberId
        })
        this.updateEmployerNumbersList()
        this.toastr.success('Actualizado correctamente')
      } catch (e) {
        console.log(e)
        this.toastr.error('Error al actualizar')
      } finally {
        this.loading = false
      }
    },
    hasCity(citiesList, cityId) {
      return citiesList.map(o => o.id).includes(cityId)
    },
    async getEmployerNumbers() {
      try {
        this.loading = true
        let res = await axios.get(`employer_number`)
        this.employerNumbers = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    },
    async getCities() {
      try {
        this.loading = true
        let res = await axios.get(`city`)
        this.cities = res.data
      } catch (e) {
        console.log(e)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
<template>
  <div class="card">
    <p-progress-bar v-if="loadstate" />
    <!-- modal input -->
    <p-modal
      @event="allValidate('store', $v.store.$invalid)"
      title="Input New shipper"
      idModal="storeshipper"
      buttonText="Add Item"
    >
      <form>
        <div class="form-group">
          <label for="nameStore">Name shipper</label>
          <input
            :disabled ="loadstate"
            v-model="store.name_shipper"
            @input="$v.store.name_shipper.$touch"
            type="text"
            class="form-control"
            :class="statusValidation($v.store.name_shipper)"
            id="nameStore"
            placeholder="e.g. JNE"
          />
          <span v-if="$v.store.name_shipper.$error" class="error invalid-feedback">Harus diisi!</span>
        </div>
        <div class="form-group">
          <label for="apiKeyStore">API Key</label>
          <input
            :disabled ="loadstate"
            v-model="store.api_key"
            type="text"
            class="form-control"
            id="apiKeyStore"
            placeholder="e.g. 98123h12k3h9"
          />
        </div>
        <div class="form-group">
          <div class="custom-control custom-switch">
            <input
              :disabled="loadstate"
              v-model="store.status"
              type="checkbox"
              class="custom-control-input"
              id="statusStore"
            />
            <label class="custom-control-label" for="statusStore">Status</label>
          </div>
        </div>
      </form>
    </p-modal>
    <!-- modal edit -->
    <p-modal
      @event="allValidate('update', $v.itemTemp.$invalid)"
      title="Edit shipper"
      idModal="editshipper"
      buttonText="Save Changes"
    >
      <div class="form-group">
        <label for="name">Name shipper</label>
        <input
          :disabled="loadstate"
          v-model="itemTemp.name_shipper"
          @input="$v.itemTemp.name_shipper.$touch"
          :class="statusValidation($v.itemTemp.name_shipper)"
          type="text"
          class="form-control"
          id="name"
          placeholder="e.g. JNE"
        />
        <span v-if="$v.store.name_shipper.$error" class="error invalid-feedback">Harus diisi!</span>
      </div>
      <div class="form-group">
        <label for="apiKey">API Key</label>
        <input
          :disabled="loadstate"
          v-model="itemTemp.api_key"
          type="text"
          class="form-control"
          id="apiKey"
          placeholder="e.g. 98123h12k3h9"
        />
      </div>
    </p-modal>
    <div class="card-header">
      <h3 class="card-title">All shipper</h3>
      <button
        class="btn btn-info btn-sm float-right"
        data-toggle="modal"
        data-target="#modal-storeshipper"
      >
        + add Ekspedisi
      </button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table ref="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Api Key</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(shipper, index) in shippers" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ shipper.name_shipper }}
              <p-badge-new :anchor="shipper.created_at" />
              <p-badge-update :anchor="shipper.update_at" />
            </td>
            <td>{{ shipper.api_key }}</td>
            <td>
              <button
                @click="statusChange(shipper.id, shipper.status, index)"
                type="Submit"
                class="btn btn-xs"
                :class="shipper.status ? 'btn-success' : 'btn-danger'"
              >
                {{ shipper.status ? "Active" : "Deactive" }}
              </button>
            </td>

            <td>
              <button
                @click="idTempChange(shipper.id, index)"
                class="btn btn-success btn-sm"
                data-toggle="modal"
                data-target="#modal-editshipper"
              >
                <i class="far fa-edit"></i>
              </button>
            </td>
            <td>
              <button
                @click="deleteItem(shipper.id)"
                type="submit"
                class="btn btn-danger btn-sm"
              >
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</template>

<script>
import PModal from '../components/PModal.vue'
import { mapState } from 'vuex'
import { required } from 'vuelidate/lib/validators'

const $ = require('jquery')
const dt = require('datatables.net')
const errorAlert = Swal.mixin({
  title: 'Ooops!',
  text: 'Terjadi Kesalahan',
  icon: 'error',
  showConfirmButton: false,
  timer: 1500
})

export default {
  components: { PModal },
  data() {
    return {
      tooltipDel: false,
      shippers: [],
      idTemp: '',
      itemTemp: {
        name_shipper: '',
        api_key: '',
        index: 0
      },
      store: {
        name_shipper: '',
        api_key: '',
        status: 0
      }
    }
  },
  validations: {
    store: {
      name_shipper: {
        required
      }
    },
    itemTemp: {
      name_shipper: {
        required
      }
    }
  },
  beforeMount() {
    this.getshipper()
  },
  mounted() {
    this.dt = $(this.$refs.example1).DataTable()
    console.log('datatabel initialize')
  },
  watch: {
    shippers(val) {
      this.dt.destroy()
      this.$nextTick(() => {
        this.dt = $(this.$refs.example1).DataTable()
      })
    }
  },
  computed: {
    ...mapState('LoadState', [
      'loadstate'
    ])
  },
  methods: {
    async getshipper() {
      try {
        const { data: { data } } = await axios.get('/shipper/all')
        this.shippers = data
      } catch (error) {
        console.log('error', error)
      }
    },
    idTempChange: function (id, index) {
      this.itemTemp.name_shipper = this.shippers[index].name_shipper
      this.itemTemp.api_key = this.shippers[index].api_key
      this.idTemp = id
      this.index = index
    },
    storeItem: function () {
      console.log('modal new item', this.store)
      this.loadState(true)
      axios.post('/shipper/store', this.store)
        .then(response => {
          console.log(response.data.data)
          this.shippers.push(response.data.data)
          this.loadState(false)
          Swal.fire({
            title: "Berhasil!!",
            text: 'Data berhasil dtambahkan',
            icon: "success",
            position: "center",
            showConfirmButton: false,
            timer: 1500
          })
          // $('#modal-storeshipperMethod').modal('hide')
        }).catch(error => {
          this.loadState(false)
          errorAlert.fire()
          console.log('error', error)
        })
    },
    editItem: function () {
      this.loadState(true)
      axios.post(`/shipper/edit/${this.idTemp}`, this.itemTemp)
        .then(response => {
          let data = response.data.data
          this.shippers[this.index].name_shipper = data.name_shipper
          this.shippers[this.index].api_key = data.api_key
          this.loadState(false)
          Swal.fire({
            title: "Berhasil!!",
            text: 'Data berhasil diubah',
            icon: "success",
            position: "center",
            showConfirmButton: false,
            timer: 1500
          })
        }).catch(error => {
          this.loadState(false)
          errorAlert.fire()
          console.log('error', error)
        })
    },
    statusChange: function(id, status, index) {
      this.loadState(true)
      axios.get(`/shipper/active/${id}`)
        .then(response => {
          this.loadState(false)
          Swal.fire(
            'Changed!',
            status == 1 ? 'Status menjadi deactive' : 'Status menjadi active',
            'success'
          )
          this.shippers[index].status = this.shippers[index].status ? 0 : 1
        }).catch(error => {
          errorAlert.fire()
          console.log(error)
        })
    },
    deleteItem: function (id) {
      Swal.fire({
        title: 'Anda yakin?',
        text: "Anda tidak dapat mengembalikannya lagi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.loadState(true)
          axios.get(`/shipper/delete/${id}`)
            .then(response => {
              Swal.fire({
                title: "Hapus!!",
                text: 'Data ini telah dihapus',
                icon: "success",
                position: "center",
                showConfirmButton: false,
                timer: 1500
              })
              this.shippers = this.shippers.filter(item => item.id !== id)
              console.log('data', this.shippers)
              this.loadState(false)
            })
            .catch(error => {
              errorAlert.fire()
              console.log(error)
            })
        }
      })
    },
    statusValidation: function (field) {
      return {
        'is-invalid': field.$error,
        '': field.$dirty,
        'is-valid': field.$dirty && !field.$error
      }
    },
    allValidate: function (method, invalid) {
      if (!invalid) {
        method == 'store' ? this.storeItem() : this.editItem()
      } else {
        Swal.fire({
          title: 'Perhatian',
          text: "Harap isi semua kolom dengan benar!",
          icon: 'warning',
        })
      }
    },
    loadState: function(state) {
      this.$store.commit('LoadState/setLoadState', state)
    }
  }
}
</script>

<template>
  <div class="card">
    <p-progress-bar v-if="loadstate" />
    <!-- modal input -->
    <p-modal
      @event="allValidate('store', $v.store.$invalid)"
      title="Input New Payment Method"
      idModal="storepaymentMethod"
      buttonText="Add Item"
    >
      <form>
        <div class="form-group">
          <label for="nameStore">Name Payment</label>
          <input
            :disabled="loadstate"
            v-model="store.name_payment"
            @input="$v.store.name_payment.$touch"
            type="text"
            class="form-control"
            :class="statusValidation($v.store.name_payment)"
            id="nameStore"
            placeholder="e.g. Gopay"
          />
          <span v-if="$v.store.name_payment.$error" class="error invalid-feedback">Harus diisi!</span>
        </div>
        <div class="form-group">
          <label for="apiKeyStore">API Key</label>
          <input
            :disabled="loadstate"
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
      title="Edit Payment Method"
      idModal="editpaymentMethod"
      buttonText="Save Changes"
    >
      <div class="form-group">
        <label for="name">Name Payment</label>
        <input
          :disabled="loadstate"
          v-model="itemTemp.name_payment"
          @input="$v.itemTemp.name_payment.$touch"
          :class="statusValidation($v.itemTemp.name_payment)"
          type="text"
          class="form-control"
          id="name"
          placeholder="e.g. JNE"
        />
        <span v-if="$v.store.name_payment.$error" class="error invalid-feedback">Harus diisi!</span>
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
      <h3 class="card-title">All Payment Method</h3>
      <button
        class="btn btn-info btn-sm float-right"
        data-toggle="modal"
        data-target="#modal-storepaymentMethod"
      >
        + add Method
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
          <tr v-for="(payment, index) in payments" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ payment.name_payment }}
              <p-badge-new :anchor="payment.created_at" />
              <p-badge-update :anchor="payment.update_at" />
            </td>
            <td>{{ payment.api_key }}</td>
            <td>
              <button
                @click="statusChange(payment.id, payment.status, index)"
                type="Submit"
                class="btn btn-xs"
                :class="payment.status ? 'btn-success' : 'btn-danger'"
              >
                {{ payment.status ? "Active" : "Deactive" }}
              </button>
            </td>

            <td>
              <button
                @click="idTempChange(payment.id, index)"
                class="btn btn-success btn-sm"
                data-toggle="modal"
                data-target="#modal-editpaymentMethod"
              >
                <i class="far fa-edit"></i>
              </button>
            </td>
            <td>
              <button
                @click="deleteItem(payment.id)"
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
      payments: [],
      idTemp: '',
      itemTemp: {
        name_payment: '',
        api_key: '',
        index: 0
      },
      store: {
        name_payment: '',
        api_key: '',
        status: 0
      }
    }
  },
  validations: {
    store: {
      name_payment: {
        required
      }
    },
    itemTemp: {
      name_payment: {
        required
      }
    }
  },
  beforeMount() {
    this.getPayment()
  },
  mounted() {
    this.dt = $(this.$refs.example1).DataTable()
    console.log('datatabel initialize')
  },
  watch: {
    payments(val) {
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
    async getPayment() {
      try {
        const { data: { data } } = await axios.get('/payment/all')
        this.payments = data
      } catch (error) {
        console.log('error', error)
      }
    },
    idTempChange: function (id, index) {
      this.itemTemp.name_payment = this.payments[index].name_payment
      this.itemTemp.api_key = this.payments[index].api_key
      this.idTemp = id
      this.index = index
    },
    storeItem: function () {
      console.log('modal new item', this.store)
      this.loadState(true)
      axios.post('/payment/store', this.store)
        .then(response => {
          console.log(response.data.data)
          this.payments.push(response.data.data)
          this.loadState(false)
          Swal.fire({
            title: "Berhasil!!",
            text: 'Data berhasil ditambahkan',
            icon: "success",
            position: "center",
            showConfirmButton: false,
            timer: 1500
          })
          // $('#modal-storepaymentMethod').modal('hide')
        }).catch(error => {
          this.loadState(false)
          errorAlert.fire()
          console.log('error', error)
        })
    },
    editItem: function () {
      this.loadState(true)
      axios.post(`/payment/edit/${this.idTemp}`, this.itemTemp)
        .then(response => {
          let data = response.data.data
          this.payments[this.index].name_payment = data.name_payment
          this.payments[this.index].api_key = data.api_key
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
      axios.get(`/payment/active/${id}`)
        .then(response => {
          this.loadState(false)
          Swal.fire(
            'Changed!',
            status == 1 ? 'Status menjadi deactive' : 'Status menjadi active',
            'success'
          )
          this.payments[index].status = this.payments[index].status ? 0 : 1
        }).catch(error => {
          this.loadState(false)
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
          axios.get(`/payment/delete/${id}`)
            .then(response => {
              Swal.fire({
                title: "Hapus!!",
                text: 'Data ini telah dihapus',
                icon: "success",
                position: "center",
                showConfirmButton: false,
                timer: 1500
              })
              this.payments = this.payments.filter(item => item.id !== id)
              console.log('data', this.payments)
              this.loadState(false)
            })
            .catch(error => {
              this.loadState(false)
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

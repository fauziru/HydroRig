const NOTIF_MUTATIONS = {
  SET_PAYLOAD: 'SET_PAYLOAD',
  SET_ERROR: 'SET_ERROR'
}

const state = () => ({
  notifications: {},
  message:''
})

const actions = {
  readNotif ({},id) {
    axios.get(`/notification/read/${id}`)
  }
}

export default {
    namespaced: true,
    actions
}

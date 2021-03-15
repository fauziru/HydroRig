<template>
  <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="far fa-bell"></i>
      <span class="badge badge-warning navbar-badge">
        {{ notif.length }}
      </span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <span class="dropdown-item dropdown-header">
        {{ notif.length }} Notifications
      </span>
      <div class="dropdown-divider"></div>
      <span
        v-for="(item, index) in notif"
        :key="index"
        @click="readNotif(item.id)"
      >
        <a :href="item.data.link || '/home'" class="dropdown-item">
          <i class="mr-2" :class="item.type | typeNotif('icon')"></i>{{ item.data["data"] | truncate(18, '...')}}
          <span class="float-right text-muted text-sm">
            {{ item.data["created_at"] | cD }}
          </span>
        </a>
      </span>
      <div class="dropdown-divider"></div>
      <a
        href="/notification"
        @click="readAll($event)"
        class="dropdown-item dropdown-footer"
      >
        See All Notifications
      </a>
    </div>
  </li>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  props: {
    userId: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      notif: []
    };
  },
  created() {
    this.getNotif()
  },
  mounted() {
    console.log("mounted", this.userId)
    this.listenToNotifChannel(this.userId)
  },
  methods: {
    ...mapActions('Notification',[
      'readNotif'
    ]),
    async getNotif() {
      try {
        const { data: { data } } = await axios.get('/notification/unread')
        this.notif = data
      } catch (error) {
        console.log('error', error)
      }
    },
    readAll: () => {
      axios.get("/notification/readall")
    },
    listenToNotifChannel: function (id) {
      Echo.private(`App.User.${id}`).notification(notification => {
        this.getNotif()
        // Swal.fire({
        //   title: " Orderan baru Masuk",
        //   icon: "info",
        //   position: "top-end",
        //   showConfirmButton: false,
        //   toast: true,
        //   timer: 3000
        // });
        console.log('tes listen channel')
        $(document).Toasts("create", {
          title: "Notification",
          icon: 'far fa-bell fa-lg',
          autohide: true,
          delay: 3000,
          body: notification.payload['data']
        })
      })
    }
  }
};
</script>

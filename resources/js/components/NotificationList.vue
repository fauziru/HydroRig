<template>
  <div class="row">
    <div v-for="(item, index) in notifications" :key="index" class="col-12">
      <a :href="item.data.link || '#'" @click="readNotif(item.id)">
        <div class="info-box">
          <span class="info-box-icon" :class="item.type | typeNotif('color')">
            <i :class="item.type | typeNotif('icon')"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-number">{{ item.type | typeNotif('title') }}</span>
            <span class="info-box-text">{{ item.data.data }}</span>
            <span class="info-box-number">
              {{ item.data.created_at | cD }}
            </span>
          </div>
        </div>
      </a>
    </div>
    <partial-load v-if="loadState" class="col-12" />
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  props: {
    section: {
      type: String,
      default: 'all'
    },
    display: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      notifications: [],
      page: 1,
      loadState: true,
      maxPage: false
    }
  },
  beforeMount() {
    this.getNotification()
  },
  mounted() {
    this.scroll()
  },
  destroyed() {
    // this.section = ''
    console.log('destroy section', this.section)
  },
  methods: {
    ...mapActions('Notification', [
      'readNotif'
    ]),
    async getNotification() {
      this.loadState = true
      try {
        const { data: { data: { data } } } = await axios.get(`notification/${this.section}/${this.page}`)
        this.page++
        if (data.length > 0) {
          this.notifications.push(...data)
        } else {
          Swal.fire({
            title: "Semua data sudah dimuat",
            icon: "info",
            position: "center",
            showConfirmButton: false,
            toast: false,
            timer: 1000
          });
          this.maxPage = true
        }
        this.loadState = false
      } catch (error) {
        console.log('error', error)
        this.loadState = false
      }
    },
    scroll: function () {
      if(this.display == this.section){
        window.onscroll = () => {
          let bottomOfWindow =
            document.documentElement.scrollTop + window.innerHeight ===
            document.documentElement.offsetHeight
          if (bottomOfWindow && !this.maxPage && this.loadState === false) {
            console.log("reach bottom", this.section)
            console.log("update")
            this.getNotification()
          }
        }
      }
    }
  }
};
</script>

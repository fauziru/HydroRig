<template>
  <div class="row">
    <div class="col">
      <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0" id="tabs">
          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
              <a
                @click="changeTabs($event)"
                class="nav-link active"
                id="custom-tabs-three-all-tab"
                data-toggle="pill"
                href="#custom-tabs-three-all"
                role="tab"
                aria-controls="custom-tabs-three-all"
                aria-selected="true"
                >All</a
              >
            </li>
            <li class="nav-item">
              <a
                @click="changeTabs($event)"
                class="nav-link"
                id="custom-tabs-three-orders-tab"
                data-toggle="pill"
                href="#custom-tabs-three-orders"
                role="tab"
                aria-controls="custom-tabs-three-orders"
                aria-selected="true"
                >Orders</a
              >
            </li>
            <li class="nav-item">
              <a
                @click="changeTabs($event)"
                class="nav-link"
                id="custom-tabs-three-messages-tab"
                data-toggle="pill"
                href="#custom-tabs-three-messages"
                role="tab"
                aria-controls="custom-tabs-three-messages"
                aria-selected="false"
                >Messages</a
              >
            </li>
            <li class="nav-item">
              <a
                @click="changeTabs($event)"
                class="nav-link"
                id="custom-tabs-three-reviews-tab"
                data-toggle="pill"
                href="#custom-tabs-three-reviews"
                role="tab"
                aria-controls="custom-tabs-three-reviews"
                aria-selected="false"
                >Reviews</a
              >
            </li>
            <li class="nav-item">
              <a
                @click="changeTabs($event)"
                class="nav-link"
                id="custom-tabs-three-admin-tab"
                data-toggle="pill"
                href="#custom-tabs-three-admin"
                role="tab"
                aria-controls="custom-tabs-three-admin"
                aria-selected="false"
                >Admin Activity</a
              >
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent">
            <div
              class="tab-pane fade show active"
              id="custom-tabs-three-all"
              role="tabpanel"
              aria-labelledby="custom-tabs-three-all-tab"
            >
              <div class="container-fluid">
                <notification-list
                  v-if="section == 'all'"
                  :display="section"
                  section="all"
                />
              </div>
            </div>
            <div
              class="tab-pane fade show"
              id="custom-tabs-three-orders"
              role="tabpanel"
              aria-labelledby="custom-tabs-three-orders-tab"
            >
              <div class="container-fluid">
                <notification-list
                  v-if="section == 'order'"
                  :display="section"
                  section="order"
                />
              </div>
            </div>
            <div
              class="tab-pane fade"
              id="custom-tabs-three-messages"
              role="tabpanel"
              aria-labelledby="custom-tabs-three-messages-tab"
            >
              <div class="container-fluid">
                <notification-list
                  v-if="section == 'message'"
                  :display="section"
                  section="message"
                />
              </div>
            </div>
            <div
              class="tab-pane fade"
              id="custom-tabs-three-reviews"
              role="tabpanel"
              aria-labelledby="custom-tabs-three-reviews-tab"
            >
              <notification-list
                v-if="section == 'review'"
                :display="section"
                section="review"
              />
            </div>
            <div
              class="tab-pane fade"
              id="custom-tabs-three-admin"
              role="tabpanel"
              aria-labelledby="custom-tabs-three-admin-tab"
            >
              <notification-list
                v-if="section == 'adminactivity'"
                :display="section"
                section="adminactivity"
              />
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      section: 'all',
      key: [
        {'custom-tabs-three-all-tab': 'all'},
        {'custom-tabs-three-orders-tab': 'order'},
        {'custom-tabs-three-messages-tab': 'message'},
        {'custom-tabs-three-reviews-tab': 'review'},
        {'custom-tabs-three-admin-tab': 'adminactivity'}
      ]

    }
  },
  mounted() {
    this.changeTabs()
  },
  methods: {
    changeTabs: function (event) {
      if(event){
        const id = event.currentTarget.id
        const section = Object.values(this.key).filter(e => e[id])
        this.section = section[0][id]
      }
    }
  }
}
</script>

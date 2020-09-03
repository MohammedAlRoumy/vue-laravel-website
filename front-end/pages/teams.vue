<template>
  <div class="container">
    <div>
      <!--      <Divider orientation="center">-->
      <h1>teams ({{total}})</h1>
      <!--      </Divider>-->

      <p>teams happy with us</p>
      <loading v-if="loading"/>
      <Row v-else style="margin-top: 40px">
        <Col span="4" v-for="row in rows">
          <Card style="margin: 5px">
            <div style="text-align:center">
              <Avatar :src="$images(row.image)" size="large"/>
              <h3>{{row.name}}</h3>
              <p>{{row.job_title}}</p>
              <template v-if="row.facebook">
                <NuxtLink to="row.facebook">
                  <Icon type="logo-facebook"/>
                </NuxtLink>
              </template>
              <template v-if="row.linkedIn">
                <NuxtLink to="row.linkedIn">
                  <Icon type="logo-linkedin"/>
                </NuxtLink>
              </template>
            </div>
          </Card>
        </Col>
      </Row>
      <template v-if="currentPage != lastPage">
        <Button type="primary" :loading="loading" @click="fetchData">
          <span v-if="!loading">Click me!</span>
          <span v-else>Loading...</span>
        </Button>
      </template>
    </div>
  </div>
</template>

<script>
  import loading from "../components/loading";

  export default {
    comments: {
      loading
    },
    data() {
      return {
        rows: [],
        loading: false,
        lastPage: 1,
        nextURL: '',
        currentPage: 1,
        total: 0,
      }
    },
    mounted() {
      this.fetchData();
    },
    methods: {
      fetchData() {
        this.loading = true;
        let url = process.env.moduleUrl + 'teams';
        if (this.nextURL !== '' && this.nextURL != null) {
          url = this.nextURL;
        }
        this.$axios.$get(url).then((res) => {
          if (this.rows.length > 0) {
            this.rows = [...this.rows, ...res.payload.data]
          } else {
            this.rows = res.payload.data;
          }
          this.nextURL = res.payload.next_page_url;
          this.lastPage = res.payload.last_page;
          this.currentPage = res.payload.current_page;
          this.total = res.payload.total;
          this.loading = false;
        })
      }
    }
  }
</script>

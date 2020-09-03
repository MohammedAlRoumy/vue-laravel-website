<template>
  <div class="container">
    <div>
<!--      <Divider orientation="center">-->
        <h1>Clients ({{total}})</h1>
<!--      </Divider>-->

      <p>clients happy with us</p>
      <loading v-if="loading"/>
      <Row v-else style="margin-top: 40px">
        <Col span="6" v-for="row in rows">
          <Card style="margin: 5px">
            <div style="text-align:center">
              <img :src="$images(row.image)" width="100%">
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
        nextURL:'',
        currentPage: 1,
        total:0,
      }
    },
    mounted() {
      this.fetchData();
    },
    methods: {
      fetchData() {
        this.loading = true;
        let url = process.env.moduleUrl+'clients';
        if (this.nextURL!=='' && this.nextURL != null){
          url = this.nextURL;
        }
        this.$axios.$get(url).then((res) => {
          if (this.rows.length > 0){
            this.rows = [...this.rows, ...res.payload.data]
          }else {
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

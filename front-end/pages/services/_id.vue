<template>
  <div class="container">
    <div>
      <Divider orientation="center">
        <h1>Service Details</h1>
      </Divider>
      <loading v-if="loading"/>
      <Row v-else style="margin-top: 40px">
        <Col span="24">

          <div style="text-align:center">
            <img :src="$images(row.image,'org')">
          </div>
          <hr>
          <h1>{{row.name}}</h1>
          <p>{{row.description}}</p>
        </Col>
      </Row>
    </div>
  </div>
</template>

<script>
  import loading from "../../components/loading";

  export default {
    comments: {
      loading
    },
    data() {
      return {
        row: {},
        loading: true,
      }
    },
    mounted() {
      this.fetchData();
    },
    methods: {
      fetchData() {
        this.loading = true;
        let url = process.env.moduleUrl + 'services/' + this.$route.params.id;
        this.$axios.$get(url).then((res) => {

          this.row = res.payload;
          this.loading = false;
        })
      }
    }
  }
</script>

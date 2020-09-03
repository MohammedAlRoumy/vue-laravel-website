<template>
  <div class="container">
    <div>
      <!--      <Divider orientation="center">-->
      <h1>Our Works ({{total}})</h1>
      <!--      </Divider>-->

      <p>text text text text</p>
      <loading v-if="loading"/>
      <List item-layout="vertical">
        <ListItem v-for="row in rows" :key="row.id">
          <ListItemMeta :title="row.name"/>
          {{ row.description }}
          <template slot="action">
            <li>
              <NuxtLink :to="row.link"><Icon type="md-link"/>Visit</NuxtLink>
              <Icon type="ios-calendar-outline" />{{row.created_at}}
            </li>
          </template>
          <template slot="extra">
            <img :src="$images(row.image)" width="250px">
          </template>
        </ListItem>
      </List>
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
        let url = process.env.moduleUrl + 'our_works';
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
